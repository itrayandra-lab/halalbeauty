<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Posts;
use App\Models\PostTags;
use Illuminate\Support\Str;
use App\Models\PostCategory;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run()
    {
        $titles = [
            'Suscipit fugiat fugit voluptatem placeat recusandae.',
            'Pemerintah Resmikan Jalan Tol Baru di Wilayah Barat.',
            'Kebakaran Hebat Hanguskan Pasar Tradisional Semalam.',
            'Inovasi Kecantikan Baru Siap Guncang Dunia Startup.',
            'Tim Nasional Raih Kemenangan Dramatis di Final.',
            'Harga Bahan Pokok Melonjak Jelang Ramadhan.',
            'Peneliti Temukan Spesies Baru di Hutan Kalimantan.',
            'Warga Keluhkan Banjir yang Kian Parah di Musim Hujan.',
            'Kecelakaan Beruntun Terjadi di Jalan Raya Pagi Tadi.',
            'Festival Budaya Lokal Menarik Ribuan Pengunjung.',
            'Perkembangan Ekonomi Digital Capai Rekor Tertinggi.',
            'Polisi Amankan Pelaku Penipuan Berkedok Investasi.',
            'Kondisi Cuaca Ekstrem Diprediksi Landa Wilayah Selatan.',
            'Selebriti Tanah Air Umumkan Pernikahan Secara Mendadak.',
            'Pemerintah Siapkan Kebijakan Baru untuk Sektor Pendidikan.',
            'Pasar Modal Menguat, IHSG Ditutup di Zona Hijau.',
            'Kampanye Lingkungan Hidup Galang Ribuan Relawan.',
            'Halal Beauty Global Buka Kantor di Indonesia.',
            'Tren Fashion Musim Ini Dominasi Warna Pastel.',
            'Masyarakat Antusias Sambut Malam Tahun Baru.'
        ];

        $images = ['assets/app/posts/image-1.jpg', 'assets/app/posts/image-2.jpg', 'assets/app/posts/image-3.jpg', 'assets/app/posts/image-4.jpg', 'assets/app/posts/image-5.jpg', 'assets/app/posts/image-6.jpg'];
        $statusList = ['active', 'inactive'];
        $userIds = User::pluck('id')->toArray();
        $categories = PostCategory::all();
        $tags = PostTags::all();

        for ($i = 0; $i < 100; $i++) {
            $title = $titles[array_rand($titles)];
            $slug = Str::slug($title . '-' . Str::random(5));
            $image = $images[array_rand($images)];
            $status = $statusList[array_rand($statusList)];
            $createdBy = $userIds[array_rand($userIds)];
            $counter = rand(1, 1000);
            $publishedAt = date('Y-m-d H:i:s', strtotime('-' . rand(1, 365) . ' days'));

            $categoryIds = $categories->random(rand(1, 2))->pluck('id')->toArray();
            $tagIds = $tags->random(rand(1, 3))->pluck('id')->toArray();
            $tagsJson = json_encode($tagIds);

            Posts::create([
                'title' => $title,
                'slug' => $slug,
                'image' => $image,
                'content' => $this->generateRandomContent(),
                'status' => $status,
                'created_by' => $createdBy,
                'category_id' => $categoryIds[0] ?? null,
                'tags' => $tagsJson,
                'counter' => $counter,
                'published_at' => $publishedAt,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function generateRandomContent()
    {
        $sampleParagraphs = [
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus imperdiet.',
            'Vivamus luctus urna sed urna ultricies ac tempor dui sagittis.',
            'In condimentum facilisis porta. Sed nec diam eu diam mattis viverra.',
            'Nulla fringilla, orci ac euismod semper, magna diam.',
            'Ut ullamcorper, ligula eu tempor congue, eros est euismod turpis.'
        ];

        $content = '<h2>' . ucfirst($sampleParagraphs[array_rand($sampleParagraphs)]) . '</h2>';
        foreach (array_slice($sampleParagraphs, 0, rand(2, 4)) as $paragraph) {
            $content .= '<p>' . $paragraph . '</p>';
        }
        $content .= '<ul>';
        for ($i = 0; $i < 3; $i++) {
            $content .= '<li>Poin penting terkait topik ini #' . ($i + 1) . '</li>';
        }
        $content .= '</ul>';

        if (rand(0, 1)) {
            $content .= '<img src="https://picsum.photos/600/400" alt="Random Image" />';
        }

        return $content;
    }
}
