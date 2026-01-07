<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\LocalizedString;
use Illuminate\Support\Str;

class ExtractTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extracts text content from blade views and saves to LocalizedString database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $viewPath = resource_path('views');
        $files = File::allFiles($viewPath);

        $count = 0;
        $skipped = 0;

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') continue; // .blade.php ends in .php

            $content = File::get($file->getPathname());
            
            // Generate group prefix from path
            $relativePath = str_replace([$viewPath . DIRECTORY_SEPARATOR, '.blade.php'], '', $file->getPathname());
            $relativePath = str_replace(DIRECTORY_SEPARATOR, '.', $relativePath);
            $groupKey = 'views.' . strtolower($relativePath);

            // Clean content for extraction
            // Remove Blade comments
            $content = preg_replace('/\{\{--.*?--\}\}/s', '', $content);
            // Remove Script and Style blocks
            $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $content);
            $content = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $content);
            // Remove Blade echo and directives (simple approximation)
            $content = preg_replace('/\{\{.*?\}\}/s', '', $content);
            $content = preg_replace('/\{!!.*?!!\}/s', '', $content);
            // Remove HTML attributes (a bit aggressive but needed to avoid capturing attributess)
            // Better: Match text between > and <
            
            preg_match_all('/>([^<]+)</', $content, $matches);

            if (!empty($matches[1])) {
                foreach ($matches[1] as $text) {
                    $text = trim($text);
                    $text = html_entity_decode($text);
                    
                    // Filter invalid text
                    if (empty($text)) continue;
                    if (is_numeric($text)) continue;
                    if (strlen($text) < 2) continue;
                    if (str_starts_with($text, '@')) continue; // Skip directives
                    if (str_contains($text, '$')) continue; // Skip variable snippets
                    if (preg_match('/^[&{};\s]+$/', $text)) continue; // efficient skip for punctuation only

                    // Generate key
                    $slug = Str::slug($text, '_');
                    if (strlen($slug) > 50) {
                        $slug = substr($slug, 0, 50);
                    }
                    if (empty($slug)) continue;

                    $key = $groupKey . '.' . $slug;

                    // Check if exists
                    $exists = LocalizedString::where('key', $key)->where('locale', 'tr')->exists();

                    if (!$exists) {
                        LocalizedString::create([
                            'key' => $key,
                            'locale' => 'tr',
                            'value' => $text,
                            'group' => $groupKey,
                            'description' => 'Extracted from ' . $relativePath
                        ]);
                        $this->info("Created: $key -> $text");
                        $count++;
                    } else {
                        $skipped++;
                    }
                }
            }
        }

        $this->info("Extraction Complete! $count new strings added. $skipped skipped.");
    }
}
