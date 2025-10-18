<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $query = Event::with(['user'])->orderBy('date_time', 'desc');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('subject', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
        }

        $events = $query->paginate(10);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'data' => $events]);
        }

        return view('back.pages.events', compact('events'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'subject' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'date_time' => 'required|date',
                'user_id' => 'nullable|exists:users,id',
            ]);

            $event = new Event();
            $event->subject = $validated['subject'];
            $event->description = $validated['description'] ?? null;
            $event->date_time = $validated['date_time'];
            $event->user_id = $validated['user_id'] ?? Auth::id();
            $event->likes = 0;
            $event->participants_count = 0;

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('events', 'public');
                $event->image = $path;
            }

            $event->save();

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Event created', 'data' => $event], 201);
            }

            return redirect()->route('admin.events')->with('success', 'Event created successfully');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    public function update(Request $request, Event $event)
    {
        $isAdmin = Auth::user() && (Auth::user()->role === 'admin' || Auth::user()->role === 'moderator');
        if (!$isAdmin && $event->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $validated = $request->validate([
                'subject' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'date_time' => 'required|date',
                'user_id' => 'nullable|exists:users,id',
                'likes' => 'nullable|integer|min:0',
                'participants_count' => 'nullable|integer|min:0',
            ]);

            $event->subject = $validated['subject'];
            $event->description = $validated['description'] ?? null;
            $event->date_time = $validated['date_time'];

            if ($isAdmin) {
                if (isset($validated['user_id'])) $event->user_id = $validated['user_id'];
                if (isset($validated['likes'])) $event->likes = $validated['likes'];
                if (isset($validated['participants_count'])) $event->participants_count = $validated['participants_count'];
            }

            if ($request->hasFile('image')) {
                if ($event->image) Storage::disk('public')->delete($event->image);
                $path = $request->file('image')->store('events', 'public');
                $event->image = $path;
            }

            $event->save();

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Event updated', 'data' => $event]);
            }

            return redirect()->route('admin.events')->with('success', 'Event updated successfully');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    public function destroy(Event $event)
    {
        $isAdmin = Auth::user() && (Auth::user()->role === 'admin' || Auth::user()->role === 'moderator');
        if (!$isAdmin && $event->user_id !== Auth::id()) abort(403);

        if ($event->image) Storage::disk('public')->delete($event->image);
        $event->delete();

        return redirect()->route('admin.events')->with('success', 'Event deleted');
    }

    public function getData(Event $event): JsonResponse
    {
        return response()->json($event->load('user'));
    }

    public function getUsers(): JsonResponse
    {
        $users = User::select('id', 'name', 'email')->where('is_active', true)->orderBy('name')->get();
        return response()->json($users);
    }

    // Frontend listing
    public function frontendIndex(Request $request): View
    {
        $events = Event::orderBy('date_time', 'desc')->paginate(9);
        $user = Auth::user();

        // Add helper flags to each event for the view
        $events->getCollection()->transform(function($event) use ($user) {
            $event->is_liked = $user ? $event->isLikedBy($user->id) : false;
            $event->is_participating = $user ? $event->isParticipatedBy($user->id) : false;
            return $event;
        });

        return view('front.pages.events', compact('events'));
    }

    // Public like
    public function like(Event $event): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        // Toggle like: attach if not exists, detach if exists
        if ($event->isLikedBy($user->id)) {
            $event->likes()->detach($user->id);
            // decrement cached counter if > 0
            if ($event->likes > 0) $event->decrement('likes');
            $liked = false;
        } else {
            $event->likes()->attach($user->id);
            $event->increment('likes');
            $liked = true;
        }

        $event->refresh();

        return response()->json([
            'success' => true,
            'likes' => $event->likes,
            'liked' => $liked,
        ]);
    }

    // Public participate
    public function participate(Event $event): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        if ($event->isParticipatedBy($user->id)) {
            $event->participants()->detach($user->id);
            if ($event->participants_count > 0) $event->decrement('participants_count');
            $participating = false;
        } else {
            $event->participants()->attach($user->id);
            $event->increment('participants_count');
            $participating = true;
        }

        $event->refresh();

        return response()->json([
            'success' => true,
            'participants_count' => $event->participants_count,
            'participating' => $participating,
        ]);
    }

    // Get event details for modal
    public function getEventDetails(Event $event): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'event' => [
                'id' => $event->id,
                'subject' => $event->subject,
                'description' => $event->description,
                'date_time' => $event->date_time,
                'likes' => $event->likes,
                'participants_count' => $event->participants_count,
                'is_liked' => $user ? $event->isLikedBy($user->id) : false,
                'is_participating' => $user ? $event->isParticipatedBy($user->id) : false,
            ],
        ]);
    }
}
