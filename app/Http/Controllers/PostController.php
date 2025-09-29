<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    /**
     * Display a listing of posts.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Post::with(['user', 'comments'])
                    ->orderBy('created_at', 'desc');

        // Handle search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('username', 'LIKE', "%{$search}%");
                  });
            });
        }

        $posts = $query->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $posts,
            ]);
        }

        // Return admin view for posts management
        return view('back.pages.posts', compact('posts'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create(): View
    {
        return view('posts.create');
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'user_id' => 'nullable|exists:users,id', // Allow admin to specify user
            ]);

            $post = new Post();
            $post->title = $validatedData['title'];
            $post->description = $validatedData['description'];
            // Use provided user_id if available (admin), otherwise use authenticated user
            $post->user_id = $validatedData['user_id'] ?? Auth::id();
            $post->likes = 0;

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('posts', 'public');
                $post->image = $imagePath;
                \Log::info('Post image stored at: ' . $imagePath);
            }

            $post->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Post created successfully!',
                    'data' => $post->load(['user', 'comments.user']),
                ], 201);
            }

            return redirect()->route('admin.posts')
                           ->with('success', 'Post created successfully!');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }

            return redirect()->back()
                           ->withErrors($e->errors())
                           ->withInput();
        }
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post): View|JsonResponse
    {
        $post->load(['user', 'comments.user']);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $post,
            ]);
        }

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post): View
    {
        // Check if user owns the post
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, Post $post): RedirectResponse|JsonResponse
    {
        // Allow admin to edit any post, otherwise check ownership
        $isAdmin = Auth::user() && (Auth::user()->role === 'admin' || Auth::user()->role === 'moderator');
        if (!$isAdmin && $post->user_id !== Auth::id()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.',
                ], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'user_id' => 'nullable|exists:users,id', // Allow admin to change author
                'likes' => 'nullable|integer|min:0', // Allow admin to modify likes
            ]);

            $post->title = $validatedData['title'];
            $post->description = $validatedData['description'];
            
            // Allow admin to change author and likes
            if ($isAdmin) {
                if (isset($validatedData['user_id'])) {
                    $post->user_id = $validatedData['user_id'];
                }
                if (isset($validatedData['likes'])) {
                    $post->likes = $validatedData['likes'];
                }
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($post->image) {
                    Storage::disk('public')->delete($post->image);
                }
                
                $imagePath = $request->file('image')->store('posts', 'public');
                $post->image = $imagePath;
            }

            $post->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Post updated successfully!',
                    'data' => $post->load(['user', 'comments.user']),
                ]);
            }

            return redirect()->route('admin.posts')
                           ->with('success', 'Post updated successfully!');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }

            return redirect()->back()
                           ->withErrors($e->errors())
                           ->withInput();
        }
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post): RedirectResponse|JsonResponse
    {
        // Allow admin to delete any post, otherwise check ownership
        $isAdmin = Auth::user() && (Auth::user()->role === 'admin' || Auth::user()->role === 'moderator');
        if (!$isAdmin && $post->user_id !== Auth::id()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.',
                ], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        // Delete associated image if it exists
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Post deleted successfully!',
            ]);
        }

        return redirect()->route('admin.posts')
                       ->with('success', 'Post deleted successfully!');
    }

    /**
     * Like or unlike a post.
     */
    public function toggleLike(Post $post): JsonResponse
    {
        // This is a simplified implementation
        // In a real application, you might want to track which users liked which posts
        $post->incrementLikes();

        return response()->json([
            'success' => true,
            'message' => 'Post liked!',
            'likes' => $post->likes,
        ]);
    }

    /**
     * Get posts by a specific user.
     */
    public function userPosts(Request $request, $userId): View|JsonResponse
    {
        $posts = Post::with(['user', 'comments.user'])
                    ->where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $posts,
            ]);
        }

        return view('posts.user-posts', compact('posts'));
    }

    /**
     * Get users for admin dropdown (Admin only)
     */
    public function getUsers(): JsonResponse
    {
        $users = User::select('id', 'name', 'email')
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get();

        return response()->json($users);
    }

    /**
     * Get post data for editing (Admin only)
     */
    public function getData(Post $post): JsonResponse
    {
        $post->load(['user', 'comments']);

        return response()->json([
            'id' => $post->id,
            'user_id' => $post->user_id,
            'title' => $post->title,
            'description' => $post->description,
            'image' => $post->image,
            'likes' => $post->likes,
            'comments_count' => $post->comments->count(),
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
        ]);
    }

    /**
     * Display posts for frontend (Public)
     */
    public function frontendIndex(Request $request): View
    {
        $posts = Post::with(['user', 'comments'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(9);

        return view('front.pages.team', compact('posts'));
    }

    /**
     * Like/Unlike a post (Public)
     */
    public function like(Request $request, Post $post): JsonResponse
    {
        // Simple like system - increment likes
        $post->incrementLikes();

        return response()->json([
            'success' => true,
            'message' => 'Post liked!',
            'likes' => $post->likes,
        ]);
    }

    /**
     * Get post with comments (Public)
     */
    public function getPostWithComments(Post $post): JsonResponse
    {
        $post->load(['user', 'comments' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        return response()->json([
            'success' => true,
            'post' => [
                'id' => $post->id,
                'title' => $post->title,
                'description' => $post->description,
            ],
            'comments' => $post->comments->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'user_name' => $comment->user_name,
                    'comment' => $comment->comment,
                    'created_at' => $comment->created_at,
                ];
            }),
        ]);
    }

    /**
     * Add comment to post (Public)
     */
    public function addComment(Request $request, Post $post): JsonResponse
    {
        $validatedData = $request->validate([
            'comment' => 'required|string|max:1000',
            'user_name' => 'required|string|max:255',
        ]);

        $comment = new Comment();
        $comment->post_id = $post->id;
        $comment->user_id = 1; // Anonymous user ID or create a system for guest users
        $comment->user_name = $validatedData['user_name'];
        $comment->comment = $validatedData['comment'];
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully!',
            'comment' => [
                'id' => $comment->id,
                'user_name' => $comment->user_name,
                'comment' => $comment->comment,
                'created_at' => $comment->created_at,
            ],
        ]);
    }
}