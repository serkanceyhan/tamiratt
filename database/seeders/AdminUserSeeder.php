<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'admin@tamiratt.com';
        
        $user = User::firstOrNew(['email' => $email]);
        $user->name = 'Sistem Yöneticisi';
        $user->password = Hash::make('Tamiratt2026!');
        $user->user_type = User::TYPE_ADMIN;
        $user->email_verified_at = now();
        $user->save();
        
        // Filament Shield kullanılıyorsa super_admin rolü ata
        // if (class_exists(\Spatie\Permission\Models\Role::class)) {
            // $role = Role::firstOrCreate(['name' => 'super_admin']);
            // $user->assignRole($role);
        // }

        $this->command->info("Admin kullanıcısı oluşturuldu/güncellendi: {$email}");
        $this->command->info("Şifre: Tamiratt2026!");
    }
}
