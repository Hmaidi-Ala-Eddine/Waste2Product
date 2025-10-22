<?php

namespace App\Services;

use App\Models\Product;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;

class ProductDescriptionAI
{
    /**
     * Generate a marketing description for a product using OpenAI
     */
    public function generateDescription(Product $product): string
    {
        try {
            // Prepare the prompt with product information
            $prompt = $this->buildPrompt($product);

            // Call Groq API (OpenAI compatible) - Using model from .env
            $response = OpenAI::chat()->create([
                'model' => env('OPENAI_MODEL', 'llama-3.1-8b-instant'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional marketing copywriter specializing in eco-friendly and sustainable products. Create compelling, SEO-friendly product descriptions that highlight the environmental benefits and appeal to conscious consumers.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 300,
                'temperature' => 0.7,
            ]);

            $description = $response->choices[0]->message->content ?? '';

            // Clean up the description
            return $this->cleanDescription($description);

        } catch (\OpenAI\Exceptions\RateLimitException $e) {
            Log::error('Groq Rate Limit Exceeded: ' . $e->getMessage());
            return $this->generateFallbackDescription($product);
        } catch (\OpenAI\Exceptions\APIException $e) {
            Log::error('Groq API Error: ' . $e->getMessage());
            return $this->generateFallbackDescription($product);
        } catch (\Exception $e) {
            Log::error('Groq General Error: ' . $e->getMessage());
            return $this->generateFallbackDescription($product);
        }
    }

    /**
     * Build the prompt for Groq API based on product information
     */
    private function buildPrompt(Product $product): string
    {
        $prompt = "Create a compelling, SEO-friendly product description for the following eco-friendly product:\n\n";
        
        $prompt .= "Product Name: {$product->name}\n";
        $prompt .= "Category: " . ucfirst($product->category) . "\n";
        
        if ($product->condition) {
            $prompt .= "Condition: " . ucfirst($product->condition) . "\n";
        }
        
        if ($product->price) {
            $prompt .= "Price: " . number_format($product->price, 2) . " TND\n";
        } else {
            $prompt .= "Price: FREE\n";
        }
        
        if ($product->description) {
            $prompt .= "Current Description: {$product->description}\n";
        }
        
        $prompt .= "\nRequirements:\n";
        $prompt .= "- Write in French (Tunisian market)\n";
        $prompt .= "- Highlight environmental benefits and sustainability\n";
        $prompt .= "- Make it appealing to eco-conscious consumers\n";
        $prompt .= "- Include relevant keywords for SEO\n";
        $prompt .= "- Maximum 1000 characters (strict limit)\n";
        $prompt .= "- Use persuasive marketing language\n";
        $prompt .= "- Mention the circular economy aspect\n";
        $prompt .= "- Focus on quality and value\n";
        
        return $prompt;
    }

    /**
     * Clean up the generated description
     */
    private function cleanDescription(string $description): string
    {
        // Remove any quotes or extra formatting
        $description = trim($description, '"\'');
        
        // Remove any "Description:" prefix if present
        $description = preg_replace('/^(Description|Description:|Description -):?\s*/i', '', $description);
        
        // Ensure proper sentence structure
        $description = trim($description);
        
        // Add period if missing at the end
        if (!empty($description) && !preg_match('/[.!?]$/', $description)) {
            $description .= '.';
        }
        
        // Enforce 1000 character limit
        if (strlen($description) > 1000) {
            $description = substr($description, 0, 1000);
        }
        
        return $description;
    }

    /**
     * Generate a fallback description if OpenAI API fails
     */
    private function generateFallbackDescription(Product $product): string
    {
        $category = ucfirst($product->category);
        $condition = $product->condition ? ucfirst($product->condition) : 'Bon';
        $price = $product->isFree() ? 'GRATUIT' : number_format($product->price, 2) . ' TND';
        
        $templates = [
            "🌟 Découvrez ce magnifique produit {$category} en excellent état ! " .
            "Cet article de qualité {$condition} est parfait pour tous ceux qui souhaitent " .
            "donner une seconde vie aux objets tout en contribuant à la protection de l'environnement. " .
            "Prix attractif : {$price}. " .
            "Rejoignez le mouvement éco-responsable et faites un choix intelligent pour notre planète !",
            
            "♻️ Article {$category} de qualité {$condition} disponible sur notre plateforme éco-responsable ! " .
            "En choisissant ce produit, vous participez activement à la réduction des déchets et à l'économie circulaire. " .
            "Prix : {$price}. " .
            "Un achat responsable qui fait la différence pour l'environnement.",
            
            "🌱 Ce produit {$category} en état {$condition} vous attend ! " .
            "Optez pour la durabilité et l'éco-responsabilité en donnant une seconde chance à cet objet. " .
            "Prix imbattable : {$price}. " .
            "Chaque achat contribue à un monde plus vert et plus durable."
        ];
        
        // Use product name to determine which template to use
        $templateIndex = strlen($product->name) % count($templates);
        $description = $templates[$templateIndex];
        
        // Ensure fallback descriptions also respect 1000 character limit
        if (strlen($description) > 1000) {
            $description = substr($description, 0, 1000);
        }
        
        return $description;
    }

    /**
     * Generate multiple description variations
     */
    public function generateMultipleDescriptions(Product $product, int $count = 3): array
    {
        $descriptions = [];
        
        for ($i = 0; $i < $count; $i++) {
            try {
                $descriptions[] = $this->generateDescription($product);
            } catch (\Exception $e) {
                Log::error("Failed to generate description variation {$i}: " . $e->getMessage());
                $descriptions[] = $this->generateFallbackDescription($product);
            }
        }
        
        return $descriptions;
    }
}
