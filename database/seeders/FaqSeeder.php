<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            // Service Category
            [
                'category' => 'service',
                'question' => 'Hizmet veren gelmedi, ne yapmalıyım?',
                'answer' => 'Randevu saatinden 15 dakika sonra hizmet veren hala gelmediyse, "Mesaj Gönder" butonunu kullanarak iletişime geçebilir veya destek ekibimize bildirebilirsiniz.',
            ],
            [
                'category' => 'service',
                'question' => 'Yapılan işlemden memnun kalmadım.',
                'answer' => 'Memnuniyetiniz bizim için önemli. İşlem tamamlandıktan sonra 24 saat içinde "Sorun Bildir" butonunu kullanarak durumu bize iletebilir, ücretsiz telafi talep edebilirsiniz.',
            ],
            [
                'category' => 'service',
                'question' => 'Randevumu nasıl iptal edebilirim?',
                'answer' => 'Hizmet başlangıç saatinden en geç 2 saat öncesine kadar panelinizden iptal işlemi gerçekleştirebilirsiniz.',
            ],

            // Payment Category
            [
                'category' => 'payment',
                'question' => 'Ödeme seçenekleri nelerdir?',
                'answer' => 'Kredi kartı, banka kartı ve havale/EFT yöntemleri ile güvenli ödeme yapabilirsiniz.',
            ],
            [
                'category' => 'payment',
                'question' => 'Faturamı nasıl alabilirim?',
                'answer' => 'İşlem tamamlandıktan sonra faturanız otomatik olarak e-posta adresinize gönderilir. Ayrıca "Siparişlerim" sayfasından da indirebilirsiniz.',
            ],

            // Technical Category
            [
                'category' => 'technical',
                'question' => 'Şifremi unuttum, nasıl sıfırlarım?',
                'answer' => 'Giriş ekranında bulunan "Şifremi Unuttum" bağlantısına tıklayarak e-posta adresinize sıfırlama bağlantısı gönderebilirsiniz.',
            ],
            [
                'category' => 'technical',
                'question' => 'Bildirim alamıyorum.',
                'answer' => 'Profil ayarlarınızdan bildirim izinlerini kontrol edin. Ayrıca telefonunuzun uygulama ayarlarından bildirimlere izin verdiğinizden emin olun.',
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create([...$faq, 'is_active' => true]);
        }
    }
}
