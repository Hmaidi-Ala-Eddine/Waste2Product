<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProductContactMail;
use App\Models\Product;
use App\Models\User;

class TestEmailCommand extends Command
{
    protected $signature = 'test:email';
    protected $description = 'Test email configuration';

    public function handle()
    {
        $this->info('Testing email configuration...');
        
        try {
            // Get a test product and user
            $product = Product::first();
            $user = User::first();
            
            if (!$product || !$user) {
                $this->error('No products or users found. Please seed the database first.');
                return;
            }
            
            // Test contact email
            $contactData = [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone' => '0123456789',
                'message' => 'This is a test message for email configuration.'
            ];
            
            $this->info('Sending test contact email...');
            Mail::to($user->email)->send(new ProductContactMail($product, $contactData));
            
            $this->info('✅ Test email sent successfully!');
            $this->info('Check your email inbox: ' . $user->email);
            
        } catch (\Exception $e) {
            $this->error('❌ Email test failed: ' . $e->getMessage());
            $this->error('Please check your email configuration in .env file');
        }
    }
}
