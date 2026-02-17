<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->info('BlogPostSeeder: Kategoriler ve yazılar oluşturuluyor...');

        DB::transaction(function () {
            BlogPost::query()->delete();
            $categories = $this->ensureCategories();
            $posts = BlogPostsData::get();

            foreach ($posts as $index => $postData) {
                $categorySlug = $postData['category_slug'];
                $categoryId = $categories[$categorySlug] ?? $categories['genel-nakliyat'];

                BlogPost::create([
                    'category_id' => $categoryId,
                    'title' => $postData['title'],
                    'slug' => $postData['slug'],
                    'meta_title' => $postData['meta_title'],
                    'meta_description' => $postData['meta_description'],
                    'excerpt' => $postData['excerpt'],
                    'content' => $postData['content'],
                    'published_at' => now()->subDays($index),
                    'featured' => $index < 6,
                ]);
            }
        });

        $this->command?->info('BlogPostSeeder: ' . BlogPost::count() . ' blog yazısı oluşturuldu.');
    }

    private function ensureCategories(): array
    {
        $items = [
            'genel-nakliyat' => ['name' => 'Genel Nakliyat', 'sort_order' => 0],
            'fiyat' => ['name' => 'Fiyat Rehberi', 'sort_order' => 1],
            'guven-karar' => ['name' => 'Güven & Karar', 'sort_order' => 2],
            'bolgesel-seo' => ['name' => 'Bölgesel Rehber', 'sort_order' => 3],
            'ozel-hizmet' => ['name' => 'Özel Hizmetler', 'sort_order' => 4],
            'karsilastirma-rehber' => ['name' => 'Karşılaştırma & Rehber', 'sort_order' => 5],
        ];

        $map = [];
        foreach ($items as $slug => $data) {
            $cat = BlogCategory::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name'],
                    'description' => $data['name'] . ' - NakliyePark blog kategorisi.',
                    'sort_order' => $data['sort_order'],
                ]
            );
            $map[$slug] = $cat->id;
        }
        return $map;
    }
}
