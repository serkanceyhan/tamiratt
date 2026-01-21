<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        // Önce temizle
        Faq::query()->delete();

        $faqs = [
            [
                'category' => 'Mobilya Yenileme',
                'question' => 'Mobilya yenileme işlemi için ürünleri şubeye mi teslim etmemiz gerekiyor?',
                'answer' => 'Yenileme işlemleriniz için mobilyalarınızı kapıdan teslim alarak yine sizlere teslim ediyoruz.',
                'order' => 1,
            ],
            [
                'category' => 'Mobilya Yenileme',
                'question' => 'Kendi ürünleriniz dışında mobilyaları yeniliyor musunuz?',
                'answer' => 'Tabii ki, marka farketmeksizin mobilyalarınızı yeniliyoruz.',
                'order' => 2,
            ],
            [
                'category' => 'Malzeme ve Tamir',
                'question' => 'Yenileme işlemleri sırasında eksik veya eskimiş malzemeler için nasıl bir çözüm sunuyorsunuz?',
                'answer' => 'Mobilya ihtiyaçları ve eksik malzeme tedariğini sizler için sağlayarak yenileme ve değişim işlemlerimizi gerçekleştiriyoruz.',
                'order' => 3,
            ],
            [
                'category' => 'Malzeme ve Tamir',
                'question' => 'Parça değişimi dışında aşınmış malzeme onarımı yapıyor musunuz?',
                'answer' => 'Aşınmış ve eskimiş dokular için de onarım işlemleri yapmaktayız.',
                'order' => 4,
            ],
            [
                'category' => 'Hizmet Kapsamı',
                'question' => 'Yenileme dışında değişim işlemleri gerçekleştiriyor musunuz?',
                'answer' => 'İstek ve beklentileriniz dahilinde mobilyalarınızı yenileme ve değişim işlemlerini gerçekleştiriyoruz.',
                'order' => 5,
            ],
            [
                'category' => 'Malzeme ve Tamir',
                'question' => 'Tamir işlemleri süresince ne tip malzemeler kullanıyorsunuz?',
                'answer' => 'Karbon kullanımından uzak kalarak çevre dostu malzemeler kullanıyoruz. Tamirat olarak küresel ısınmanın önüne geçmek için çevreci çözümlerle süreçlerimizi tamamlıyoruz.',
                'order' => 6,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create([
                ...$faq, 
                'is_active' => true
            ]);
        }
    }
}
