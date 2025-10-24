<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GroqService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.groq.com/openai/v1';
    protected $chatbotModel;
    protected $analysisModel;
    protected $maxRetries = 3;
    protected $timeout = 45;

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key');
        $this->chatbotModel = config('services.groq.chatbot_model', 'llama-3.3-70b-versatile');
        $this->analysisModel = config('services.groq.analysis_model', 'llama-3.1-8b-instant');
    }

    /**
     * Send a chat message to Groq API with retry logic
     *
     * @param array $messages
     * @param string|null $model
     * @param int $attempt
     * @return array|null
     */
    public function chat(array $messages, ?string $model = null, int $attempt = 1): ?array
    {
        if (!$this->apiKey) {
            Log::error('Groq API Key not configured');
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout($this->timeout)
            ->retry($this->maxRetries, 1000)
            ->post($this->baseUrl . '/chat/completions', [
                'model' => $model ?? $this->chatbotModel,
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 1500,
                'top_p' => 0.9,
                'stream' => false,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Log usage for monitoring
                if (isset($data['usage'])) {
                    Log::info('Groq API Usage', [
                        'tokens' => $data['usage']['total_tokens'] ?? 0,
                        'model' => $model ?? $this->chatbotModel
                    ]);
                }
                
                return $data;
            }

            // Log error with details
            Log::error('Groq API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'attempt' => $attempt
            ]);

            // Retry on specific errors
            if ($attempt < $this->maxRetries && in_array($response->status(), [429, 500, 502, 503, 504])) {
                sleep($attempt); // Exponential backoff
                return $this->chat($messages, $model, $attempt + 1);
            }

            return null;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Groq API Connection Error', [
                'message' => $e->getMessage(),
                'attempt' => $attempt
            ]);

            // Retry on connection errors
            if ($attempt < $this->maxRetries) {
                sleep($attempt);
                return $this->chat($messages, $model, $attempt + 1);
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Groq API Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return null;
        }
    }

    /**
     * Get chatbot response with enhanced context
     *
     * @param string $userMessage
     * @param array $conversationHistory
     * @return string|null
     */
    public function getChatbotResponse(string $userMessage, array $conversationHistory = []): ?string
    {
        // Get system context
        $systemContext = $this->getSystemContext();

        // Build messages array
        $messages = [
            ['role' => 'system', 'content' => $systemContext]
        ];

        // Add conversation history (limit to prevent token overflow)
        $historyLimit = 8; // Last 4 exchanges
        if (count($conversationHistory) > $historyLimit) {
            $conversationHistory = array_slice($conversationHistory, -$historyLimit);
        }

        foreach ($conversationHistory as $msg) {
            if (isset($msg['role']) && isset($msg['content'])) {
                $messages[] = $msg;
            }
        }

        // Add current user message
        $messages[] = ['role' => 'user', 'content' => $userMessage];

        // Get response from Groq
        $response = $this->chat($messages);

        if ($response && isset($response['choices'][0]['message']['content'])) {
            return trim($response['choices'][0]['message']['content']);
        }

        return null;
    }

    /**
     * Analyze text using Groq
     *
     * @param string $prompt
     * @param string $text
     * @return string|null
     */
    public function analyze(string $prompt, string $text): ?string
    {
        $messages = [
            ['role' => 'system', 'content' => $prompt],
            ['role' => 'user', 'content' => $text]
        ];

        $response = $this->chat($messages, $this->analysisModel);

        if ($response && isset($response['choices'][0]['message']['content'])) {
            return trim($response['choices'][0]['message']['content']);
        }

        return null;
    }

    /**
     * Get comprehensive system context
     *
     * @return string
     */
    protected function getSystemContext(): string
    {
        return "You are an expert AI assistant for Waste2Product, a leading waste collection and recycling platform in Tunisia.

**Platform Overview:**
Waste2Product connects citizens with verified waste collectors, promoting environmental sustainability and circular economy principles. Users can easily request waste collection services, become collectors, and trade recycled products.

**Core Features:**

1. **Waste Collection Requests**
   - Users submit requests specifying: waste type, quantity (kg), specific address, and governorate
   - System matches requests with available collectors
   - Real-time tracking and status updates
   - Waste types: Plastic, Glass, Metal, Paper/Cardboard, Electronic, Organic, Textiles, Mixed recyclables

2. **Verified Collector Network**
   - Professional collectors with various vehicle types (truck, van, motorcycle, bicycle, cart)
   - Coverage across all 24 Tunisia governorates
   - Rating system (1-5 stars) for quality assurance
   - Collectors earn from reselling recyclable materials

3. **Product Marketplace**
   - Buy and sell recycled/upcycled products
   - Shopping cart and secure checkout
   - Multiple payment methods
   - Product categories: furniture, decor, accessories, etc.

4. **Rating & Quality System**
   - Users rate collectors after collection completion
   - Leaderboard showcasing top-performing collectors
   - Builds trust and accountability

**Tunisia Coverage (24 Governorates):**
Tunis, Ariana, Ben Arous, Manouba, Nabeul, Zaghouan, Bizerte, Béja, Jendouba, Kef, Siliana, Kairouan, Kasserine, Sidi Bouzid, Sousse, Monastir, Mahdia, Sfax, Gafsa, Tozeur, Kebili, Gabès, Medenine, Tataouine

**How to Help Users:**

**For Waste Requests:**
1. Go to 'My Services' → 'My Waste Requests' (must be logged in)
2. Fill form: select waste type, enter quantity, choose governorate, provide specific address
3. Add optional description
4. Submit and wait for collector assignment
5. Track status: pending → accepted → collected

**To Become a Collector:**
1. Navigate to 'My Services' → 'Collector Application'
2. Complete application: company name, vehicle type, select service areas (governorates), capacity, bio
3. Submit for admin verification
4. Once verified, access 'Collector Dashboard' to accept requests

**Marketplace Shopping:**
1. Browse products in Shop
2. Add items to cart
3. Proceed to checkout
4. Choose payment method and complete order

**Your Communication Style:**
- Be warm, friendly, and encouraging about environmental responsibility
- Provide clear, step-by-step instructions
- Use emojis sparingly (♻️ 🌱 🚚 when relevant)
- Keep responses concise but comprehensive
- If unsure about specific details, suggest contacting support
- Emphasize the positive environmental impact
- Be professional yet approachable

**Response Format:**
- Start with a friendly greeting for first messages
- Use bullet points for multi-step processes
- Bold important terms using **text**
- End with an offer to help further

Remember: Your goal is to make waste management easy, accessible, and encourage environmental stewardship in Tunisia.";
    }

    /**
     * Detect waste type from description
     *
     * @param string $description
     * @return array
     */
    public function detectWasteType(string $description): array
    {
        // Map to exact dropdown values from WasteRequest model
        $wasteTypes = [
            'plastic' => 'Plastic',
            'paper' => 'Paper',
            'metal' => 'Metal',
            'glass' => 'Glass',
            'organic' => 'Organic Waste',
            'e-waste' => 'Electronic Waste',
            'textile' => 'Textile',
            'mixed' => 'Mixed Waste'
        ];

        $wasteTypesStr = implode(', ', array_keys($wasteTypes));

        $messages = [
            [
                'role' => 'system',
                'content' => "You are a waste classification expert for Waste2Product Tunisia. Analyze the user's description and categorize it into ONE of these EXACT types: {$wasteTypesStr}. For electronics/electronic devices, respond with 'e-waste'. Respond ONLY with the exact category name from the list. Be strict and accurate."
            ],
            [
                'role' => 'user',
                'content' => "Classify this waste: {$description}"
            ]
        ];

        try {
            $response = $this->chat($messages, $this->analysisModel);

            if ($response && isset($response['choices'][0]['message']['content'])) {
                $detectedType = strtolower(trim($response['choices'][0]['message']['content']));
                
                // Direct match first
                if (isset($wasteTypes[$detectedType])) {
                    return [
                        'success' => true,
                        'type' => $detectedType,
                        'label' => $wasteTypes[$detectedType],
                        'confidence' => 'high'
                    ];
                }
                
                // Fuzzy matching for common alternatives
                $aliases = [
                    'electronics' => 'e-waste',
                    'electronic' => 'e-waste',
                    'ewaste' => 'e-waste',
                    'hazardous' => 'mixed',
                ];
                
                foreach ($aliases as $alias => $actualType) {
                    if (str_contains($detectedType, $alias) || $detectedType === $alias) {
                        return [
                            'success' => true,
                            'type' => $actualType,
                            'label' => $wasteTypes[$actualType],
                            'confidence' => 'high'
                        ];
                    }
                }
                
                // Partial match
                foreach ($wasteTypes as $key => $label) {
                    if (str_contains($detectedType, $key) || str_contains($key, $detectedType)) {
                        return [
                            'success' => true,
                            'type' => $key,
                            'label' => $label,
                            'confidence' => 'medium'
                        ];
                    }
                }
            }

            return [
                'success' => false,
                'message' => 'Could not determine waste type'
            ];

        } catch (\Exception $e) {
            Log::error('Waste type detection failed', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Detection service temporarily unavailable'
            ];
        }
    }

    /**
     * Enhance waste description using AI
     *
     * @param string $description
     * @param string|null $wasteType
     * @param float|null $quantity
     * @param string|null $state
     * @return array
     */
    public function enhanceDescription(string $description, ?string $wasteType = null, ?float $quantity = null, ?string $state = null): array
    {
        // Map waste type to readable name
        $wasteTypeLabels = [
            'plastic' => 'Plastic',
            'paper' => 'Paper',
            'metal' => 'Metal',
            'glass' => 'Glass',
            'organic' => 'Organic Waste',
            'e-waste' => 'Electronic Waste',
            'textile' => 'Textile',
            'mixed' => 'Mixed Waste'
        ];
        
        $wasteTypeLabel = $wasteType && isset($wasteTypeLabels[$wasteType]) 
            ? $wasteTypeLabels[$wasteType] 
            : $wasteType;
            
        $wasteTypeContext = $wasteTypeLabel ? " The waste type is: {$wasteTypeLabel}." : "";
        $quantityContext = $quantity ? " The exact quantity is: {$quantity} kg." : "";
        $locationContext = $state ? " The location is in: {$state}, Tunisia." : "";

        $messages = [
            [
                'role' => 'system',
                'content' => "You are a waste description expert for Tunisia. Enhance the user's brief description to be more detailed and professional for waste collection purposes. Include relevant details about condition and characteristics. Keep it concise (2-3 sentences max). Be specific and factual.{$wasteTypeContext}{$quantityContext}{$locationContext} IMPORTANT: If a quantity is provided, you MUST use that exact quantity (do not estimate or change it). Always mention the quantity in kilograms (kg) if provided. If location is provided, mention it for better collector matching."
            ],
            [
                'role' => 'user',
                'content' => "Enhance this description: {$description}"
            ]
        ];

        try {
            $response = $this->chat($messages, $this->analysisModel);

            if ($response && isset($response['choices'][0]['message']['content'])) {
                $enhanced = trim($response['choices'][0]['message']['content']);
                
                return [
                    'success' => true,
                    'original' => $description,
                    'enhanced' => $enhanced
                ];
            }

            return [
                'success' => false,
                'message' => 'Could not enhance description'
            ];

        } catch (\Exception $e) {
            Log::error('Description enhancement failed', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Enhancement service temporarily unavailable'
            ];
        }
    }

    /**
     * Suggest team requirements for EcoIdea project
     *
     * @param string $title
     * @param string $description
     * @param string $wasteType
     * @param string $difficultyLevel
     * @param int|null $teamSize
     * @return array
     */
    public function suggestTeamRequirements(string $title, string $description, string $wasteType, string $difficultyLevel, ?int $teamSize = null): array
    {
        // Map waste type and difficulty to readable names
        $wasteTypeLabels = [
            'plastic' => 'Plastic',
            'paper' => 'Paper',
            'metal' => 'Metal',
            'glass' => 'Glass',
            'organic' => 'Organic Waste',
            'e-waste' => 'Electronic Waste (E-Waste)',
            'textile' => 'Textile',
            'mixed' => 'Mixed Waste'
        ];

        $difficultyLabels = [
            'easy' => 'Easy (Beginner-friendly)',
            'medium' => 'Medium (Intermediate skills required)',
            'hard' => 'Hard (Advanced expertise needed)'
        ];

        $wasteTypeLabel = $wasteTypeLabels[$wasteType] ?? $wasteType;
        $difficultyLabel = $difficultyLabels[$difficultyLevel] ?? $difficultyLevel;
        $teamSizeContext = $teamSize ? " The team size needed is: {$teamSize} members." : "";

        $messages = [
            [
                'role' => 'system',
                'content' => "You are an expert project manager specializing in environmental and waste management projects. Based on the project details provided, suggest specific team requirements listing the skills, roles, and expertise needed. Be concise, practical, and specific to the waste type and project complexity. Format as a clear list of skills/roles separated by commas (e.g., 'Environmental Engineer, Waste Management Specialist, Project Coordinator, Marketing/Communications'). Keep it under 200 characters. Focus on the most essential skills only."
            ],
            [
                'role' => 'user',
                'content' => "Suggest team requirements for this eco-innovation project:\n\nTitle: {$title}\nDescription: {$description}\nWaste Type: {$wasteTypeLabel}\nDifficulty: {$difficultyLabel}{$teamSizeContext}\n\nProvide a concise comma-separated list of required skills and roles."
            ]
        ];

        try {
            $response = $this->chat($messages, $this->analysisModel);

            if ($response && isset($response['choices'][0]['message']['content'])) {
                $suggested = trim($response['choices'][0]['message']['content']);
                
                return [
                    'success' => true,
                    'suggested' => $suggested
                ];
            }

            return [
                'success' => false,
                'message' => 'Could not generate team requirements'
            ];

        } catch (\Exception $e) {
            Log::error('Team requirements suggestion failed', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Suggestion service temporarily unavailable'
            ];
        }
    }

    /**
     * Check EcoIdea originality against existing ideas
     *
     * @param string $title
     * @param string $description
     * @param array $existingIdeas
     * @return array
     */
    public function checkIdeaOriginality(string $title, string $description, array $existingIdeas): array
    {
        if (empty($existingIdeas)) {
            return [
                'success' => true,
                'is_original' => true,
                'similarity_percentage' => 0,
                'message' => 'No existing ideas to compare against'
            ];
        }

        // Format existing ideas for AI comparison
        $existingIdeasText = '';
        foreach ($existingIdeas as $index => $idea) {
            $num = $index + 1;
            $existingIdeasText .= "Idea #{$num}:\nTitle: {$idea['title']}\nDescription: {$idea['description']}\n\n";
        }

        $messages = [
            [
                'role' => 'system',
                'content' => "You are an expert copyright and plagiarism detector for eco-innovation projects. Your job is to analyze if a new project idea is too similar to existing ones.

IMPORTANT: Almost ALL eco-projects involve 'transforming waste into something useful' - that's too general to count as similar!

CRITICAL RULES FOR DETERMINING SIMILARITY:
1. **HIGH Similarity (80-100%)**: Ideas are similar if they share:
   - Same specific waste type (e.g., both use plastic bottles, both use paper)
   - Same specific methodology/process (e.g., both make vertical gardens, both make furniture)
   - Same specific end product (e.g., both create planters, both create bags)
   - Same specific target application (e.g., both for schools, both for homes)

2. **LOW Similarity (0-30%)**: Ideas are DIFFERENT if they differ in:
   - Different waste types (plastic bottles vs organic waste vs e-waste)
   - Different end products (garden vs furniture vs clothing vs machine)
   - Different methodologies (composting vs crafting vs melting vs recycling)
   - Different target users or applications

3. **Be CONSISTENT**: Same inputs should always give same similarity score.

4. **Be SPECIFIC**: Don't say 'both transform waste' - that's too vague. Focus on WHAT waste, WHAT process, WHAT product.

EXAMPLES:
- 'Plastic bottle garden' vs 'Plastic bottle planter': 90% similar ✓
- 'Plastic bottle garden' vs 'Paper waste compost': 15% similar ✓
- 'Plastic bottle garden' vs 'E-waste recycling machine': 10% similar ✓
- 'Plastic bottle garden' vs 'Textile waste bags': 10% similar ✓

OUTPUT FORMAT (JSON only, no extra text):
{
    \"similarity_percentage\": <number 0-100>,
    \"most_similar_idea_number\": <number or null>,
    \"is_original\": <true or false>,
    \"reasoning\": \"<specific explanation focusing on waste type, methodology, and end product>\",
    \"similar_aspects\": [\"<list of similar aspects if any>\"]
}"
            ],
            [
                'role' => 'user',
                'content' => "NEW IDEA TO CHECK:\nTitle: {$title}\nDescription: {$description}\n\n---\n\nEXISTING IDEAS IN DATABASE:\n{$existingIdeasText}\n\nAnalyze if the new idea is too similar (>= 80%) to any existing idea. Return ONLY valid JSON."
            ]
        ];

        try {
            // Use temperature 0 for consistent, deterministic results
            $response = $this->chat($messages, $this->analysisModel, 0);

            if ($response && isset($response['choices'][0]['message']['content'])) {
                $aiResponse = trim($response['choices'][0]['message']['content']);
                
                // Try to extract JSON from response
                if (preg_match('/\{[\s\S]*\}/', $aiResponse, $matches)) {
                    $jsonData = json_decode($matches[0], true);
                    
                    if ($jsonData && isset($jsonData['similarity_percentage'])) {
                        $similarityPercentage = (float) $jsonData['similarity_percentage'];
                        $isOriginal = $similarityPercentage < 80;
                        
                        $result = [
                            'success' => true,
                            'is_original' => $isOriginal,
                            'similarity_percentage' => $similarityPercentage,
                            'reasoning' => $jsonData['reasoning'] ?? 'No reasoning provided',
                            'similar_aspects' => $jsonData['similar_aspects'] ?? [],
                            'most_similar_idea_number' => $jsonData['most_similar_idea_number'] ?? null
                        ];

                        if (!$isOriginal && isset($jsonData['most_similar_idea_number'])) {
                            $similarIdeaIndex = $jsonData['most_similar_idea_number'] - 1;
                            if (isset($existingIdeas[$similarIdeaIndex])) {
                                $result['similar_idea_id'] = $existingIdeas[$similarIdeaIndex]['id'];
                                $result['similar_idea_title'] = $existingIdeas[$similarIdeaIndex]['title'];
                            }
                        }

                        return $result;
                    }
                }

                // Fallback if JSON parsing fails
                return [
                    'success' => false,
                    'message' => 'Could not parse AI response'
                ];
            }

            return [
                'success' => false,
                'message' => 'Could not check originality'
            ];

        } catch (\Exception $e) {
            Log::error('Originality check failed', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Originality check service temporarily unavailable'
            ];
        }
    }

    /**
     * Generate AI report for waste requests
     *
     * @param array $data
     * @return array
     */
    public function generateWasteRequestsReport(array $data): array
    {
        $summary = $data['summary'];
        $byType = json_encode($data['by_type'], JSON_PRETTY_PRINT);
        $byStatus = json_encode($data['by_status'], JSON_PRETTY_PRINT);
        $byLocation = json_encode($data['by_location'], JSON_PRETTY_PRINT);
        $topCollectors = json_encode($data['top_collectors'], JSON_PRETTY_PRINT);

        $messages = [
            [
                'role' => 'system',
                'content' => "You are a professional data analyst for Waste2Product Tunisia. Generate a BEAUTIFUL, comprehensive, well-structured report analyzing waste collection data. 

FORMATTING RULES:
- Use emojis extensively (♻️ 📊 🎯 ⚡ 🌍 🚚 📈 💡 ⭐ 🏆 etc.)
- Use **bold** for important metrics and key points
- Use visual separators (═══, ───, •••)
- Use bullet points (•, ▪, ►)
- Create visual hierarchy with spacing
- Make it engaging, colorful, and professional
- Include percentages and comparisons
- Add visual progress indicators when relevant
- Use boxes/frames for key insights (╔═══╗ style)"
            ],
            [
                'role' => 'user',
                'content' => "Generate a STUNNING waste requests analytics report based on this data:

📊 SUMMARY STATISTICS:
- Total Requests: {$summary['total']}
- Pending Requests: {$summary['pending']}
- Accepted Requests: {$summary['accepted']}
- Collected Requests: {$summary['collected']}
- Cancelled Requests: {$summary['cancelled']}
- Total Weight: {$summary['total_weight']} kg

♻️ REQUESTS BY WASTE TYPE:
{$byType}

📈 REQUESTS BY STATUS:
{$byStatus}

🌍 REQUESTS BY LOCATION (Top 10):
{$byLocation}

🏆 TOP COLLECTORS:
{$topCollectors}

Generate a visually stunning report with:
1. 🎯 Executive Summary (with emojis and bold metrics)
2. 💡 Key Insights & Trends (use visual indicators)
3. ♻️ Waste Type Analysis (include percentages)
4. 🌍 Geographic Distribution Analysis (highlight hotspots)
5. 🚚 Collector Performance Analysis (top performers)
6. 🚀 Strategic Recommendations (actionable with emojis)

Make it PROFESSIONAL yet VISUALLY ENGAGING!"
            ]
        ];

        try {
            $response = $this->chat($messages, $this->chatbotModel);

            if ($response && isset($response['choices'][0]['message']['content'])) {
                return [
                    'success' => true,
                    'report' => trim($response['choices'][0]['message']['content'])
                ];
            }

            return [
                'success' => false,
                'message' => 'Could not generate report'
            ];

        } catch (\Exception $e) {
            Log::error('Report generation failed', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Report generation service temporarily unavailable'
            ];
        }
    }

    /**
     * Generate AI report for collectors
     *
     * @param array $data
     * @return array
     */
    public function generateCollectorsReport(array $data): array
    {
        $summary = $data['summary'];
        $byStatus = json_encode($data['by_status'], JSON_PRETTY_PRINT);
        $byVehicle = json_encode($data['by_vehicle'], JSON_PRETTY_PRINT);
        $topRated = json_encode($data['top_rated'], JSON_PRETTY_PRINT);
        $mostActive = json_encode($data['most_active'], JSON_PRETTY_PRINT);

        $messages = [
            [
                'role' => 'system',
                'content' => "You are a professional data analyst for Waste2Product Tunisia. Generate a BEAUTIFUL, comprehensive, well-structured report analyzing collector performance data.

FORMATTING RULES:
- Use emojis extensively (🚚 ⭐ 🏆 💪 🎯 📊 🌟 ✅ ⚡ 🔥 etc.)
- Use **bold** for important metrics and names
- Use visual separators (═══, ───, •••)
- Use bullet points (•, ▪, ►)
- Create visual hierarchy with spacing
- Make it engaging, colorful, and professional
- Include star ratings visually (⭐⭐⭐⭐⭐)
- Add visual progress/status indicators
- Use boxes/frames for key insights
- Highlight top performers with special formatting"
            ],
            [
                'role' => 'user',
                'content' => "Generate a STUNNING collectors performance report based on this data:

📊 SUMMARY STATISTICS:
- Total Collectors: {$summary['total']}
- Verified Collectors: {$summary['verified']}
- Pending Collectors: {$summary['pending']}
- Suspended Collectors: {$summary['suspended']}
- Average Rating: {$summary['avg_rating']}/5.0 ⭐
- Total Collections: {$summary['total_collections']}

✅ COLLECTORS BY VERIFICATION STATUS:
{$byStatus}

🚚 COLLECTORS BY VEHICLE TYPE:
{$byVehicle}

🌟 TOP RATED COLLECTORS:
{$topRated}

🏆 MOST ACTIVE COLLECTORS:
{$mostActive}

Generate a visually stunning report with:
1. 🎯 Executive Summary (with emojis and bold metrics)
2. 🚚 Collector Network Overview (visual breakdown)
3. 📈 Performance Analysis (highlight achievers)
4. ⭐ Rating & Quality Assessment (use star ratings)
5. 🚗 Vehicle Fleet Analysis (vehicle type distribution)
6. 🚀 Strategic Recommendations (growth opportunities with emojis)

Make it PROFESSIONAL yet VISUALLY ENGAGING! Celebrate top performers!"
            ]
        ];

        try {
            $response = $this->chat($messages, $this->chatbotModel);

            if ($response && isset($response['choices'][0]['message']['content'])) {
                return [
                    'success' => true,
                    'report' => trim($response['choices'][0]['message']['content'])
                ];
            }

            return [
                'success' => false,
                'message' => 'Could not generate report'
            ];

        } catch (\Exception $e) {
            Log::error('Report generation failed', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Report generation service temporarily unavailable'
            ];
        }
    }

    /**
     * Moderate comment content - detect and censor inappropriate language
     *
     * @param string $comment
     * @return array
     */
    public function moderateComment(string $comment): array
    {
        // Cache the result for 1 hour to avoid re-processing same comment
        $cacheKey = 'moderated_comment_' . md5($comment);
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $messages = [
            [
                'role' => 'system',
                'content' => "You are a strict content moderation AI. Your job is to CENSOR inappropriate words.

BAD WORDS TO CENSOR (replace with ***):
- Profanity: fuck, shit, damn, bitch, ass, bastard, hell, dick, cock, pussy, etc.
- Slurs and hate speech
- Sexual/explicit terms
- Threats or violent language
- Personal attacks: stupid, idiot, fool, dumb, etc.

RULES:
1. If comment is CLEAN → Return: {\"is_appropriate\": true, \"censored_text\": \"<exact original text>\"}
2. If comment has BAD WORDS → Replace EACH bad word with *** → Return: {\"is_appropriate\": false, \"censored_text\": \"<censored version>\", \"violations\": [\"profanity\"]}

EXAMPLES:
Input: \"This is great\"
Output: {\"is_appropriate\": true, \"censored_text\": \"This is great\"}

Input: \"fuck this\"
Output: {\"is_appropriate\": false, \"censored_text\": \"*** this\", \"violations\": [\"profanity\"]}

Input: \"you are stupid and a bitch\"
Output: {\"is_appropriate\": false, \"censored_text\": \"you are *** and a ***\", \"violations\": [\"profanity\", \"insult\"]}

CRITICAL: 
- ALWAYS replace bad words with ***
- NEVER leave bad words uncensored
- Return ONLY valid JSON"
            ],
            [
                'role' => 'user',
                'content' => "Moderate and censor this comment:\n\n\"{$comment}\"\n\nReturn JSON with censored_text field."
            ]
        ];

        try {
            $response = $this->chat($messages, $this->analysisModel);

            if ($response && isset($response['choices'][0]['message']['content'])) {
                $aiResponse = trim($response['choices'][0]['message']['content']);
                
                // Extract JSON from response
                if (preg_match('/\{[\s\S]*\}/', $aiResponse, $matches)) {
                    $jsonData = json_decode($matches[0], true);
                    
                    if ($jsonData && isset($jsonData['is_appropriate'])) {
                        // Ensure censored_text is never null or empty
                        $censoredText = $jsonData['censored_text'] ?? null;
                        if (empty($censoredText) || $censoredText === 'null') {
                            $censoredText = $comment; // Use original if AI returned null/empty
                        }
                        
                        $isAppropriate = $jsonData['is_appropriate'];
                        
                        // SAFETY CHECK: If AI says inappropriate but censored text = original, manually censor
                        if (!$isAppropriate && $censoredText === $comment) {
                            Log::warning('AI detected inappropriate but did not censor, applying manual censoring', [
                                'original' => $comment
                            ]);
                            $censoredText = $this->manualCensor($comment);
                        }
                        
                        $result = [
                            'success' => true,
                            'is_appropriate' => $isAppropriate,
                            'original_text' => $comment,
                            'censored_text' => $censoredText,
                            'violations' => $jsonData['violations'] ?? []
                        ];

                        // Cache the result
                        Cache::put($cacheKey, $result, 3600); // 1 hour
                        
                        return $result;
                    }
                }

                // Fallback: if JSON parsing fails, return original
                Log::warning('Comment moderation JSON parsing failed', ['response' => $aiResponse]);
                return [
                    'success' => false,
                    'is_appropriate' => true,
                    'original_text' => $comment,
                    'censored_text' => $comment,
                    'message' => 'Could not parse moderation response'
                ];
            }

            return [
                'success' => false,
                'is_appropriate' => true,
                'original_text' => $comment,
                'censored_text' => $comment,
                'message' => 'Moderation service unavailable'
            ];

        } catch (\Exception $e) {
            Log::error('Comment moderation failed', [
                'error' => $e->getMessage(),
                'comment' => substr($comment, 0, 100)
            ]);
            
            return [
                'success' => false,
                'is_appropriate' => true,
                'original_text' => $comment,
                'censored_text' => $comment,
                'message' => 'Moderation service error'
            ];
        }
    }

    /**
     * Manual fallback censoring using regex
     *
     * @param string $text
     * @return string
     */
    private function manualCensor(string $text): string
    {
        // Common bad words to censor (case-insensitive)
        $badWords = [
            'fuck', 'shit', 'damn', 'bitch', 'ass', 'bastard', 'hell', 'dick', 
            'cock', 'pussy', 'cunt', 'whore', 'slut', 'fag', 'nigger', 'nigga',
            'stupid', 'idiot', 'fool', 'dumb', 'moron', 'retard', 'loser'
        ];
        
        foreach ($badWords as $word) {
            // Replace with *** (preserving word boundaries)
            $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
            $text = preg_replace($pattern, '***', $text);
        }
        
        return $text;
    }

    /**
     * Check API health
     *
     * @return bool
     */
    public function healthCheck(): bool
    {
        try {
            $messages = [
                ['role' => 'user', 'content' => 'test']
            ];

            $response = $this->chat($messages);
            
            return $response !== null;

        } catch (\Exception $e) {
            return false;
        }
    }
}
