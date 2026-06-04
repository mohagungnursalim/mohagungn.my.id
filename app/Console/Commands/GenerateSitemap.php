<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Post;
use App\Models\Category;

#[Signature('sitemap:generate')]
#[Description('Generate the sitemap')]
class GenerateSitemap extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating sitemap...');

        $sitemap = Sitemap::create()
            ->add(Url::create('/')->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
            ->add(Url::create('/kategori')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

        // Add Categories
        $categories = Category::all();
        foreach ($categories as $category) {
            $sitemap->add(
                Url::create("/kategori/{$category->slug}")
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        }

        // Add Posts
        $posts = Post::where('is_published', true)->get();
        foreach ($posts as $post) {
            $sitemap->add(
                Url::create("/artikel/{$post->slug}")
                    ->setLastModificationDate($post->updated_at)
                    ->setPriority(0.9)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully.');
    }
}
