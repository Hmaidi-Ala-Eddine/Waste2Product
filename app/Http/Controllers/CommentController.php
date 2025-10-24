<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    /**
     * Display a listing of comments for a specific post.
     */
    public function index(Request $request, Post $post): View|JsonResponse
    {
        $comments = Comment::with('user')
                          ->where('post_id', $post->id)
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $comments,
            ]);
        }

        return view('comments.index', compact('comments', 'post'));
    }

    /**
     * Show the form for creating a new comment.
     */
    public function create(Post $post): View
    {
        return view('comments.create', compact('post'));
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Post $post): RedirectResponse|JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'comment' => 'required|string|max:1000',
            ]);

            $user = Auth::user();
            
            $comment = new Comment();
            $comment->post_id = $post->id;
            $comment->user_id = $user->id;
            $comment->user_name = $user->name ?? $user->username ?? 'Anonymous';
            $comment->comment = $validatedData['comment'];
            $comment->save();

            if ($request->expectsJson()) {
                // Initialize variables BEFORE try-catch
                $isModerated = false;
                $moderatedText = $comment->comment;
                
                // Moderate the comment once and reuse the result
                try {
                    $groqService = app(\App\Services\GroqService::class);
                    $moderation = $groqService->moderateComment($comment->comment);
                    
                    $isModerated = !($moderation['is_appropriate'] ?? true);
                    $moderatedText = $moderation['censored_text'] ?? $comment->comment;
                    
                    // Ensure we actually have censored text
                    if (empty($moderatedText) || $moderatedText === 'null') {
                        $moderatedText = $comment->comment;
                        $isModerated = false;
                    }
                    
                    \Log::info('Comment moderation result', [
                        'comment_id' => $comment->id,
                        'original' => $comment->comment,
                        'is_appropriate' => $moderation['is_appropriate'] ?? true,
                        'is_moderated' => $isModerated,
                        'censored' => $moderatedText,
                        'violations' => $moderation['violations'] ?? [],
                    ]);
                    
                } catch (\Exception $e) {
                    \Log::error('Comment moderation failed', [
                        'error' => $e->getMessage(),
                        'comment_id' => $comment->id
                    ]);
                    
                    // Variables already initialized above
                    $isModerated = false;
                    $moderatedText = $comment->comment;
                }
                
                // Force log before returning
                \Log::info('Returning comment response', [
                    'moderated_text' => $moderatedText,
                    'is_moderated' => $isModerated,
                    'original' => $comment->comment
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Comment added successfully!',
                    'comment' => [
                        'id' => $comment->id,
                        'user_name' => $comment->user_name,
                        'user_avatar' => $comment->user->profile_picture_url ?? asset('images/default-avatar.png'),
                        'comment' => $comment->comment,
                        'moderated_comment' => $moderatedText,
                        'is_moderated' => $isModerated,
                        'created_at' => $comment->created_at->diffForHumans(),
                    ],
                    'data' => $comment->load('user'),
                ], 201);
            }

            return redirect()->route('admin.posts')
                           ->with('success', 'Comment added successfully!');

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
     * Display the specified comment.
     */
    public function show(Comment $comment): View|JsonResponse
    {
        $comment->load(['user', 'post']);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $comment,
            ]);
        }

        return view('comments.show', compact('comment'));
    }

    /**
     * Show the form for editing the specified comment.
     */
    public function edit(Comment $comment): View
    {
        // Check if user owns the comment
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('comments.edit', compact('comment'));
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(Request $request, Comment $comment): RedirectResponse|JsonResponse
    {
        // Check if user owns the comment
        if ($comment->user_id !== Auth::id()) {
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
                'comment' => 'required|string|max:1000',
            ]);

            $comment->comment = $validatedData['comment'];
            $comment->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Comment updated successfully!',
                    'data' => $comment->load(['user', 'post']),
                ]);
            }

            return redirect()->route('admin.posts')
                           ->with('success', 'Comment updated successfully!');

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
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment): RedirectResponse|JsonResponse
    {
        // Check if user owns the comment
        if ($comment->user_id !== Auth::id()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.',
                ], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        $post = $comment->post;
        $comment->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully!',
            ]);
        }

        return redirect()->route('admin.posts')
                       ->with('success', 'Comment deleted successfully!');
    }

    /**
     * Get comments by a specific user.
     */
    public function userComments(Request $request, $userId): View|JsonResponse
    {
        $comments = Comment::with(['user', 'post'])
                          ->where('user_id', $userId)
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $comments,
            ]);
        }

        return view('comments.user-comments', compact('comments'));
    }

    /**
     * Get latest comments for a post (AJAX endpoint).
     */
    public function getLatestComments(Post $post): JsonResponse
    {
        $comments = Comment::with('user')
                          ->where('post_id', $post->id)
                          ->orderBy('created_at', 'desc')
                          ->limit(10)
                          ->get();

        return response()->json([
            'success' => true,
            'data' => $comments,
        ]);
    }
}