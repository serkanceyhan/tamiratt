# Laravel Filament Starter Kit

    Modern, component-based Laravel starter kit with Filament admin panel, Alpine.js interactions, and a beautiful landing page system.

## 🚀 Features

### Admin Panel (Filament 3.x)
- **Role & Permission Management** - Powered by Spatie Permission
- **TipTap Rich Text Editor** - Modern WYSIWYG editing
- **Activity Logs** - Track all user actions
- **Media Library** - Image and file management
- **Shield Integration** - Advanced access control

### CMS Features
- **Blog Management** - Posts with categories and tags
- **Page Management** - Dynamic page creation with SEO
- **Menu Builder** - Nested menu system with multiple locations
- **Content Organization** - Categories and tags for better organization

### Landing Page System
- **Component-Based Architecture** - Reusable Blade components
- **Alpine.js Interactions** - Lightweight reactive features
- **Quote/Contact Modal** - Email-ready form system
- **Instagram Feed Integration** - Social media showcase
- **Smooth Scroll & Back-to-Top** - Enhanced UX
- **Tailwind CSS** - Modern, utility-first styling

### Email System
- **Quote Request Mailing** - Automated email notifications
- **File Attachments** - Support for image uploads
- **HTML Email Templates** - Professional formatting

## 📋 Requirements

- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL/PostgreSQL/SQLite

## 🛠️ Installation

### 1. Clone the Repository
```bash
git clone <https://github.com/serkanceyhan/starter-kit.git>
cd tamiratt
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations
```bash
php artisan migrate --seed
```

### 6. Create Admin User
```bash
php artisan shield:super-admin
```

### 7. Build Assets
```bash
npm run build
```

### 8. Start Development Server
```bash
php artisan serve
```

Visit: `http://localhost:8000`  
Admin Panel: `http://localhost:8000/admin`

## 📧 Mail Configuration

For quote form functionality, configure SMTP in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

For local testing, use:
```env
MAIL_MAILER=log
```
Emails will be written to `storage/logs/laravel.log`

## 🎨 Component Structure

### Landing Page Components
Located in `resources/views/components/landing/`:
- `header.blade.php` - Navigation with CTA
- `hero.blade.php` - Hero section with before/after
- `services.blade.php` - Service offerings
- `process.blade.php` - How it works section
- `cta.blade.php` - Call-to-action section
- `instagram-feed.blade.php` - Social proof
- `footer.blade.php` - Site footer
- `quote-modal.blade.php` - Contact form modal
- `scroll-to-top.blade.php` - Back to top button

### Usage Example
```blade
<x-landing-layout>
    <x-landing.header />
    <x-landing.hero />
    <x-landing.services />
    <!-- Your content -->
    <x-landing.quote-modal />
</x-landing-layout>
```

## 🔧 Customization

### Colors
Edit `tailwind.config.js`:
```javascript
colors: {
    "primary": "#2463eb",
    "secondary": "#16A34A",
    // ...
}
```

### Mail Recipient
Update in `app/Http/Controllers/QuoteController.php`:
```php
Mail::to('your-email@example.com')->send(new QuoteRequestMail($validated, $fullPath));
```

## 🧪 Development

### Quick Start
```bash
composer run dev
```
This runs server, queue, logs, and vite concurrently.

### Individual Commands
```bash
php artisan serve        # Development server
npm run dev             # Vite dev server
php artisan queue:work  # Queue worker
php artisan pail        # Real-time logs
```

## 📦 Key Packages

- **Laravel 11** - PHP Framework
- **Filament 3** - Admin Panel
- **Alpine.js** - Reactive components
- **Tailwind CSS** - Utility-first CSS
- **Livewire 3** - Dynamic interfaces
- **Spatie Media Library** - File management
- **Spatie Permission** - ACL system
- **TipTap Editor** - Rich text editing

## 📝 License

MIT License - feel free to use in your projects!

## 🤝 Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for contribution guidelines.

## 🐛 Issues

Found a bug? Please create an issue with:
- Steps to reproduce
- Expected vs actual behavior
- Laravel/PHP version
- Error messages/logs
