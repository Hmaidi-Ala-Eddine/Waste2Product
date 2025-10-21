<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of events with search
     */
    public function index(Request $request)
    {
        $query = Event::with('author');
        
        // Handle search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('subject', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('author', function($authorQuery) use ($search) {
                      $authorQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Order and paginate results
        $events = $query->orderBy('date_time', 'desc')->paginate(5);
        
        // Preserve query parameters in pagination links
        $events->appends($request->query());
        
        return view('back.pages.events', compact('events'));
    }

    /**
     * Store a newly created event
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'date_time' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:5000',
            'author_id' => 'required|exists:users,id',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'event_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('events', $filename, 'public');
            $validated['image'] = $path;
        }

        Event::create($validated);

        return redirect()->route('admin.events')->with('success', 'Event created successfully!');
    }

    /**
     * Get event data for editing
     */
    public function getData($id)
    {
        $event = Event::with('author')->findOrFail($id);
        return response()->json($event);
    }

    /**
     * Update the specified event
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'date_time' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:5000',
            'author_id' => 'required|exists:users,id',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            
            $image = $request->file('image');
            $filename = 'event_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('events', $filename, 'public');
            $validated['image'] = $path;
        }

        $event->update($validated);

        return redirect()->route('admin.events')->with('success', 'Event updated successfully!');
    }

    /**
     * Delete the specified event
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        
        // Delete image if exists
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        
        $event->delete();

        return redirect()->route('admin.events')->with('delete_success', 'Event deleted successfully!');
    }

    /**
     * Get authors for dropdown
     */
    public function getAuthors()
    {
        $authors = User::where('is_active', true)
                      ->select('id', 'name', 'email')
                      ->get();
        
        return response()->json($authors);
    }

    /**
     * Display events for frontend (Public viewing)
     */
    public function frontendIndex(Request $request)
    {
        $query = Event::with('author');
        
        // Handle search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('subject', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        // Order by date_time descending to show most recent/upcoming events first
        $events = $query->orderBy('date_time', 'desc')->paginate(12);
        $events->appends($request->query());
        
        return view('front.pages.events', compact('events'));
    }

    /**
     * Participate in event (Authenticated users only)
     * Toggles between participate and cancel
     */
    public function participate(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $userId = Auth::id();
        
        // Check if user already participated
        $participation = EventParticipation::where('user_id', $userId)
                                          ->where('event_id', $id)
                                          ->first();
        
        if ($participation) {
            // Cancel participation - delete record and decrement
            $participation->delete();
            if ($event->engagement > 0) {
                $event->decrement('engagement');
            }
            $message = 'Participation cancelled';
        } else {
            // Register participation - create record and increment
            EventParticipation::create([
                'user_id' => $userId,
                'event_id' => $id,
            ]);
            $event->increment('engagement');
            $message = 'Successfully registered for the event!';
        }
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'engagement' => $event->engagement,
        ]);
    }

    /**
     * Store event from frontend (Admin only)
     */
    public function frontendStore(Request $request)
    {
        // Check if user is admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('front.events')->with('error', 'Unauthorized: Only admins can create events.');
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'date_time' => 'required|date|after:now',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string|max:5000',
        ]);

        // Set author as current user
        $validated['author_id'] = auth()->id();
        $validated['engagement'] = 0;

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'event_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('events', $filename, 'public');
            $validated['image'] = $path;
        }

        Event::create($validated);

        return redirect()->route('front.events')->with('success', 'Event created successfully!');
    }
}
