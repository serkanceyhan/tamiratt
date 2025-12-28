<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate {--ping : Ping Google after generation}';
    protected $description = 'Generate sitemap and optionally ping Google';

    public function handle()
    {
        $this->info('Generating sitemap...');
        
        // Clear sitemap cache
        Cache::forget('sitemap_services');
        
        // Trigger sitemap generation by accessing the URL
        try {
            $response = Http::get(url('/sitemap-services.xml'));
            
            if ($response->successful()) {
                $this->info('✅ Sitemap generated successfully');
                
                // Count URLs (rough estimate from response size)
                $urlCount = substr_count($response->body(), '<url>');
                $this->info("📄 Generated {$urlCount} URLs");
                
                // Ping Google if requested
                if ($this->option('ping')) {
                    $this->info('Pinging Google...');
                    
                    try {
                        Http::get('https://www.google.com/ping?sitemap=' . url('/sitemap.xml'));
                        $this->info('✅ Google pinged successfully');
                    } catch (\Exception $e) {
                        $this->warn('⚠️  Could not ping Google: ' . $e->getMessage());
                    }
                }
                
                $this->newLine();
                $this->info('🎉 Sitemap is ready at: ' . url('/sitemap.xml'));
                
            } else {
                $this->error('❌ Failed to generate sitemap');
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
