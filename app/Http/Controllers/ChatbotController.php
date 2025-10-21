<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GroqService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class ChatbotController extends Controller
{
    protected $groqService;

    public function __construct(GroqService $groqService)
    {
        $this->groqService = $groqService;
    }

    /**
     * Handle chat message with enhanced error handling
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chat(Request $request)
    {
        // Rate limiting - 30 requests per minute per IP
        $key = 'chatbot:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 30)) {
            return response()->json([
                'success' => false,
                'error' => 'rate_limit',
                'message' => 'Too many requests. Please wait a moment and try again.',
            ], 429);
        }

        RateLimiter::hit($key, 60);

        // Validate input
        $validated = $request->validate([
            'message' => 'required|string|min:1|max:1000',
        ]);

        $userMessage = trim($validated['message']);

        // Basic spam/abuse detection
        if ($this->isSpamMessage($userMessage)) {
            return response()->json([
                'success' => false,
                'error' => 'invalid_input',
                'message' => 'Please send a meaningful message.',
            ], 400);
        }

        try {
            // Get conversation history from session
            $conversationHistory = Session::get('chatbot_history', []);

            // Limit conversation history
            if (count($conversationHistory) > 10) {
                $conversationHistory = array_slice($conversationHistory, -10);
            }

            // Get AI response
            $aiResponse = $this->groqService->getChatbotResponse($userMessage, $conversationHistory);

            if (!$aiResponse) {
                Log::warning('Chatbot: Empty response from Groq API', [
                    'user_message' => $userMessage,
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'success' => false,
                    'error' => 'api_error',
                    'message' => '🤖 Oops! I\'m having trouble connecting right now. Please try again in a moment.',
                    'retry' => true,
                ], 500);
            }

            // Update conversation history
            $conversationHistory[] = ['role' => 'user', 'content' => $userMessage];
            $conversationHistory[] = ['role' => 'assistant', 'content' => $aiResponse];

            Session::put('chatbot_history', $conversationHistory);

            // Log successful interaction
            Log::info('Chatbot: Successful interaction', [
                'message_length' => strlen($userMessage),
                'response_length' => strlen($aiResponse),
            ]);

            return response()->json([
                'success' => true,
                'response' => $aiResponse,
                'timestamp' => now()->format('H:i'),
                'messageId' => uniqid('msg_'),
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot: Exception during chat', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_message' => $userMessage,
            ]);

            return response()->json([
                'success' => false,
                'error' => 'server_error',
                'message' => '😔 Something went wrong on our end. Please try again.',
                'retry' => true,
            ], 500);
        }
    }

    /**
     * Clear chat history
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearHistory()
    {
        try {
            Session::forget('chatbot_history');

            return response()->json([
                'success' => true,
                'message' => 'Chat history cleared successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear history',
            ], 500);
        }
    }

    /**
     * Get chat history
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHistory()
    {
        $conversationHistory = Session::get('chatbot_history', []);

        return response()->json([
            'success' => true,
            'history' => $conversationHistory,
            'count' => count($conversationHistory),
        ]);
    }

    /**
     * Get quick reply suggestions
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuickReplies()
    {
        $quickReplies = [
            ['text' => '📦 How do I submit a waste request?', 'icon' => '📦'],
            ['text' => '🚚 How to become a collector?', 'icon' => '🚚'],
            ['text' => '📍 What areas do you cover?', 'icon' => '📍'],
            ['text' => '♻️ What waste types are accepted?', 'icon' => '♻️'],
            ['text' => '⭐ How does the rating system work?', 'icon' => '⭐'],
            ['text' => '🛒 How to use the marketplace?', 'icon' => '🛒'],
        ];

        return response()->json([
            'success' => true,
            'quickReplies' => $quickReplies,
        ]);
    }

    /**
     * Health check endpoint
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function health()
    {
        $apiHealthy = $this->groqService->healthCheck();

        return response()->json([
            'success' => true,
            'status' => $apiHealthy ? 'healthy' : 'degraded',
            'groq_api' => $apiHealthy ? 'online' : 'offline',
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Simple spam detection
     *
     * @param string $message
     * @return bool
     */
    protected function isSpamMessage(string $message): bool
    {
        // Check for excessive repetition
        if (preg_match('/(.)\1{10,}/', $message)) {
            return true;
        }

        // Check for too many special characters
        $specialChars = preg_match_all('/[^a-zA-Z0-9\s\?\!\.\,\'\"]/', $message);
        if ($specialChars && $specialChars > strlen($message) * 0.5) {
            return true;
        }

        // Check if message is just spaces
        if (trim($message) === '') {
            return true;
        }

        return false;
    }

    /**
     * Detect waste type from description using AI
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detectWasteType(Request $request)
    {
        // Rate limiting
        $key = 'ai-detect:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 20)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests. Please wait a moment.',
            ], 429);
        }

        RateLimiter::hit($key, 60);

        // Validate input
        $validated = $request->validate([
            'description' => 'required|string|min:3|max:500',
        ]);

        try {
            $result = $this->groqService->detectWasteType($validated['description']);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Waste type detection error', [
                'error' => $e->getMessage(),
                'description' => $validated['description']
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Could not detect waste type. Please try again.',
            ], 500);
        }
    }

    /**
     * Enhance description using AI
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function enhanceDescription(Request $request)
    {
        // Rate limiting
        $key = 'ai-enhance:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 15)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests. Please wait a moment.',
            ], 429);
        }

        RateLimiter::hit($key, 60);

        // Validate input
        $validated = $request->validate([
            'description' => 'required|string|min:3|max:500',
            'waste_type' => 'nullable|string|in:plastic,paper,metal,glass,organic,e-waste,textile,mixed',
            'quantity' => 'nullable|numeric|min:0.01|max:999999.99',
            'state' => 'nullable|string|max:100',
        ]);

        try {
            $result = $this->groqService->enhanceDescription(
                $validated['description'],
                $validated['waste_type'] ?? null,
                $validated['quantity'] ?? null,
                $validated['state'] ?? null
            );

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Description enhancement error', [
                'error' => $e->getMessage(),
                'description' => $validated['description']
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Could not enhance description. Please try again.',
            ], 500);
        }
    }
}
