<?php

namespace Database\Seeders;

use App\Models\LocalizedString;
use Illuminate\Database\Seeder;

class LocalizedStringSeeder extends Seeder
{
    public function run(): void
    {
        $strings = [
            // Home Page - Ana Sayfa
            'home.welcome_title' => 'Ofis Mobilyası Tamirat Çözümleri',
            'home.welcome_subtitle' => 'Profesyonel, hızlı ve uygun fiyatlı hizmet',
            'home.cta_button' => 'Ücretsiz Teklif Al',
            'home.features_title' => 'Neden Bizi Seçmelisiniz?',
            'home.services_title' => 'Hizmetlerimiz',
            'home.services_subtitle' => 'Ofisiniz İçin Profesyonel Çözümler',
            
            // Navigation - Menü
            'nav.home' => 'Ana Sayfa',
            'nav.services' => 'Hizmetler',
            'nav.how_it_works' => 'Nasıl Çalışır?',
            'nav.sustainability' => 'Sürdürülebilirlik',
            'nav.about' => 'Hakkımızda',
            'nav.contact' => 'İletişim',
            'nav.blog' => 'Blog',
            'nav.login' => 'Giriş Yap',
            'nav.logout' => 'Çıkış Yap',
            
            // Email - E-posta
            'email.new_quote_request' => 'Yeni Teklif Talebi',
            'email.company_name' => 'Şirket Adı',
            'email.full_name' => 'Ad Soyad',
            'email.email_address' => 'E-posta',
            'email.service_type' => 'Hizmet Türü',
            'email.message_details' => 'Mesaj / Detaylar',
            'email.no_message' => 'Mesaj yok',
            'email.footer_note' => 'Bu e-posta Tamirat.com üzerinden gönderilmiştir.',
            
            // Footer - Alt Bilgi
            'footer.company_description' => 'Ofis mobilyaları için sürdürülebilir, ekonomik ve profesyonel tamirat çözümleri.',
            'footer.quick_links' => 'Hızlı Bağlantılar',
            'footer.services' => 'Hizmetler',
            'footer.corporate' => 'Kurumsal',
            'footer.about' => 'Hakkımızda',
            'footer.sustainability' => 'Sürdürülebilirlik Raporu',
            'footer.references' => 'Referanslar',
            'footer.career' => 'Kariyer',
            'footer.contact_us' => 'Bize Ulaşın',
            'footer.location' => 'Maslak, İstanbul',
            'footer.phone' => '+90 (212) 555 0123',
            'footer.email' => 'info@tamirat.com',
            'footer.copyright' => '© {year} Flux360. Tüm hakları saklıdır.',
            'footer.privacy_policy' => 'Gizlilik Politikası',
            'footer.terms_of_service' => 'Kullanım Şartları',
            
            // Contact - İletişim
            'contact.title' => 'İletişime Geçin',
            'contact.subtitle' => 'Size nasıl yardımcı olabiliriz?',
            'contact.address_label' => 'Adres',
            'contact.phone_label' => 'Telefon',
            'contact.email_label' => 'E-posta',
            'contact.form_company' => 'Şirket Adı',
            'contact.form_name' => 'Ad Soyad',
            'contact.form_email' => 'E-posta',
            'contact.form_service' => 'Hizmet Türü',
            'contact.form_message' => 'Mesajınız / Mobilya Sayısı',
            'contact.form_photo' => 'Fotoğraf Yükle (İsteğe Bağlı)',
            'contact.form_submit' => 'Teklifi Gönder',
            'contact.form_submitting' => 'Gönderiliyor...',
            'contact.form_privacy_note' => 'Bilgileriniz güvenle saklanır. Ücretsiz keşif ve teklif sürecidir.',
            'contact.drag_drop' => 'Tıkla veya sürükle bırak',
            'contact.file_type' => 'PNG, JPG (max. 5MB)',
            
            // Services - Hizmetler
            'services.title' => 'Hizmetlerimiz',
            'services.subtitle' => 'Ofisiniz İçin Profesyonel Çözümler',
            'services.view_details' => 'Detayları Gör',
            'services.all_services' => 'Tüm Hizmetler',
            
            // SEO Pages
            'seo.city_service_title' => '{city} {service}',
            'seo.district_service_title' => '{district}, {city} {service}',
            'seo.professional_service' => 'Profesyonel Hizmet',
            'seo.all_districts' => 'Tüm İlçeler',
            'seo.active_cities' => 'Hizmet Verdiğimiz İller',
            'seo.location_info' => '{location} Bölgesinde Profesyonel Hizmet',
            'seo.turkey_wide' => 'Türkiye Genelinde Profesyonel Hizmet',
            
            // Quote Modal - Teklif Formu
            'quote.title' => 'Ücretsiz Teklif Al',
            'quote.close' => 'Kapat',
            
            // Buttons - Butonlar
            'btn.get_quote' => 'Teklif Al',
            'btn.submit' => 'Gönder',
            'btn.cancel' => 'İptal',
            'btn.close' => 'Kapat',
            'btn.save' => 'Kaydet',
            'btn.delete' => 'Sil',
            'btn.edit' => 'Düzenle',
            'btn.view' => 'Görüntüle',
            'btn.back' => 'Geri',
            'btn.next' => 'İleri',
            'btn.upload' => 'Yükle',
            
            // Forms - Genel Form
            'form.required_field' => 'Bu alan zorunludur',
            'form.invalid_email' => 'Geçersiz e-posta adresi',
            'form.success_message' => 'Mesajınız başarıyla gönderildi',
            'form.error_message' => 'Bir hata oluştu, lütfen tekrar deneyin',
            'form.file_selected' => 'Dosya seçildi',
            'form.remove_file' => 'Dosyayı kaldır',
            
            // General - Genel
            'general.read_more' => 'Devamını Oku',
            'general.loading' => 'Yükleniyor...',
            'general.search' => 'Ara',
            'general.filter' => 'Filtrele',
            'general.all' => 'Tümü',
            'general.new' => 'Yeni',
            'general.active' => 'Aktif',
            'general.inactive' => 'Pasif',
            'general.yes' => 'Evet',
            'general.no' => 'Hayır',
            
            // Provider Application - Hizmet Veren Başvurusu
            'provider.apply_title' => 'Pazaryerimize Katılın',
            'provider.apply_subtitle' => 'Ofis mobilyası tamir alanında uzmanıysanız, binlerce iş fırsatına erişin. Başvurunuz 24 saat içinde değerlendirilir.',
            'provider.badge_text' => 'Hizmet Veren Programı',
            'provider.section_personal' => 'Kişisel Bilgiler',
            'provider.section_company' => 'Firma Bilgileri',
            'provider.section_expertise' => 'Uzmanlık Alanları',
            'provider.section_expertise_desc' => 'Hangi hizmetlerde uzmanlaştığınızı seçin',
            'provider.section_areas' => 'Hizmet Bölgeleri',
            'provider.section_areas_desc' => 'Hangi illerde hizmet verebilirsiniz?',
            'provider.section_documents' => 'Belgeler',
            'provider.section_documents_desc' => 'Vergi levhası, kimlik veya ustalık belgesi (PDF, JPG, PNG - Max 5MB)',
            'provider.field_name' => 'Ad Soyad',
            'provider.field_phone' => 'Telefon',
            'provider.field_email' => 'E-posta',
            'provider.field_company' => 'Firma / Ticari Unvan',
            'provider.field_tax' => 'Vergi Numarası',
            'provider.terms_text' => 'Kullanım Koşulları ve Gizlilik Politikası\'nı okudum, kabul ediyorum.',
            'provider.submit_button' => 'Başvuruyu Gönder',
            'provider.upload_text' => 'Dosyaları sürükleyin veya',
            'provider.upload_click' => 'buraya tıklayın',
            'provider.no_categories' => 'Henüz hizmet kategorisi tanımlanmamış.',
            'provider.no_areas' => 'Henüz bölge tanımlanmamış.',
            'provider.benefit_fast' => 'Hızlı Onay',
            'provider.benefit_fast_desc' => 'Başvurular 24 saat içinde değerlendirilir ve size bildirilir.',
            'provider.benefit_high' => 'Yüksek Potansiyel',
            'provider.benefit_high_desc' => 'Aylık binlerce iş talebi arasından size uygun olanları seçin.',
            'provider.benefit_support' => '7/24 Destek',
            'provider.benefit_support_desc' => 'Teknik destek ekibimiz her zaman yanınızda.',
        ];

        foreach ($strings as $key => $value) {
            LocalizedString::updateOrCreate(
                ['key' => $key, 'locale' => 'tr'],
                [
                    'value' => $value,
                    'group' => explode('.', $key)[0], // İlk kısım grup
                ]
            );
        }

        // Cache temizle
        LocalizedString::clearCache();
        
        $this->command->info('✅ ' . count($strings) . ' çeviri eklendi!');
    }
}
