<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class CleanupImages extends Command
{
    protected $signature = 'images:cleanup {--dry-run : Show what would be cleaned without actually doing it}';
    protected $description = 'Clean up orphaned image references in database';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
        }
        
        $this->info('🧹 Starting image cleanup...');
        
        // Clean up Posts
        $this->cleanupPosts($dryRun);
        
        // Clean up Products  
        $this->cleanupProducts($dryRun);
        
        $this->info('✅ Image cleanup completed!');
    }
    
    private function cleanupPosts($dryRun)
    {
        $this->info('📝 Checking Posts...');
        
        $posts = Post::whereNotNull('image')->where('image', '!=', '')->get();
        $cleaned = 0;
        
        foreach ($posts as $post) {
            if (!Storage::disk('public')->exists($post->image)) {
                $this->warn("❌ Missing file: {$post->image} (Post ID: {$post->id})");
                
                if (!$dryRun) {
                    $post->update(['image' => null]);
                    $cleaned++;
                }
            } else {
                $this->line("✅ Valid: {$post->image}");
            }
        }
        
        if (!$dryRun && $cleaned > 0) {
            $this->info("🧹 Cleaned {$cleaned} orphaned post image references");
        }
    }
    
    private function cleanupProducts($dryRun)
    {
        $this->info('🛍️ Checking Products...');
        
        $products = Product::whereNotNull('image_path')->where('image_path', '!=', '')->get();
        $cleaned = 0;
        
        foreach ($products as $product) {
            if (!Storage::disk('public')->exists($product->image_path)) {
                $this->warn("❌ Missing file: {$product->image_path} (Product ID: {$product->id})");
                
                if (!$dryRun) {
                    $product->update(['image_path' => null]);
                    $cleaned++;
                }
            } else {
                $this->line("✅ Valid: {$product->image_path}");
            }
        }
        
        if (!$dryRun && $cleaned > 0) {
            $this->info("🧹 Cleaned {$cleaned} orphaned product image references");
        }
    }
}
