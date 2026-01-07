<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class GhostUserService
{
    /**
     * Find existing user by phone or create a ghost user
     * 
     * Ghost users:
     * - Have no password
     * - is_ghost = true
     * - Can later be converted to full users
     */
    public function findOrCreateByPhone(string $phone, ?string $name = null, ?string $email = null): User
    {
        // Normalize phone number
        $phone = $this->normalizePhone($phone);

        // Try to find existing user
        $user = User::where('phone', $phone)->first();

        if ($user) {
            return $user;
        }

        // Create ghost user
        return User::create([
            'name' => $name ?? 'Müşteri ' . substr($phone, -4),
            'email' => $email ?? $this->generateTempEmail($phone),
            'phone' => $phone,
            'user_type' => 'customer',
            'is_ghost' => true,
            'password' => bcrypt(Str::random(32)), // Random password, user can't login with it
        ]);
    }

    /**
     * Convert a ghost user to a full user with password
     */
    public function convertToFullUser(User $user, string $password, ?string $name = null, ?string $email = null): User
    {
        if (!$user->is_ghost) {
            return $user; // Already a full user
        }

        $data = [
            'password' => bcrypt($password),
            'is_ghost' => false,
        ];

        if ($name) {
            $data['name'] = $name;
        }

        if ($email && !str_starts_with($user->email, 'ghost_')) {
            $data['email'] = $email;
        } elseif ($email) {
            $data['email'] = $email;
        }

        $user->update($data);

        return $user->refresh();
    }

    /**
     * Normalize phone number to standard format
     * Input: 5XX XXX XX XX, 0 5XX XXX XX XX, +90 5XX XXX XX XX
     * Output: 5XXXXXXXXX (10 digits)
     */
    public function normalizePhone(string $phone): string
    {
        // Remove all non-digits
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Remove country code if present
        if (str_starts_with($phone, '90') && strlen($phone) === 12) {
            $phone = substr($phone, 2);
        }

        // Remove leading zero
        if (str_starts_with($phone, '0') && strlen($phone) === 11) {
            $phone = substr($phone, 1);
        }

        return $phone;
    }

    /**
     * Generate a temporary email for ghost users
     */
    private function generateTempEmail(string $phone): string
    {
        return 'ghost_' . $phone . '@tamiratt.local';
    }

    /**
     * Check if a phone number already exists
     */
    public function phoneExists(string $phone): bool
    {
        $phone = $this->normalizePhone($phone);
        return User::where('phone', $phone)->exists();
    }

    /**
     * Get user by phone
     */
    public function getUserByPhone(string $phone): ?User
    {
        $phone = $this->normalizePhone($phone);
        return User::where('phone', $phone)->first();
    }
}
