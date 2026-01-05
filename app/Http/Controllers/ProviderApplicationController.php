<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Service;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProviderApplicationController extends Controller
{
    /**
     * Show the provider application form
     */
    public function create()
    {
        $services = Service::whereNull('parent_id')->with('children')->get();
        $cities = Location::whereNull('parent_id')->where('is_active', true)->with('children')->get();

        return view('provider.apply', compact('services', 'cities'));
    }

    /**
     * Handle the provider application submission
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'company_name' => 'required|string|max:255',
            'tax_number' => 'nullable|string|max:50',
            'service_categories' => 'required|array|min:1',
            'service_categories.*' => 'exists:services,id',
            'service_areas' => 'required|array|min:1',
            'service_areas.*' => 'exists:locations,id',
            'documents' => 'required|array|min:1',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            'terms_accepted' => 'required|accepted',
        ], [
            'name.required' => 'Ad soyad zorunludur.',
            'email.required' => 'E-posta adresi zorunludur.',
            'email.unique' => 'Bu e-posta adresi zaten kayıtlı.',
            'phone.required' => 'Telefon numarası zorunludur.',
            'company_name.required' => 'Firma adı zorunludur.',
            'service_categories.required' => 'En az bir hizmet kategorisi seçmelisiniz.',
            'service_areas.required' => 'En az bir hizmet bölgesi seçmelisiniz.',
            'documents.required' => 'En az bir belge yüklemelisiniz.',
            'terms_accepted.required' => 'Kullanım koşullarını kabul etmelisiniz.',
        ]);

        DB::beginTransaction();

        try {
            // Create user without password
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'user_type' => User::TYPE_PROVIDER,
                'password' => Hash::make(Str::random(32)), // Temporary random password
            ]);

            // Create provider profile
            $provider = Provider::create([
                'user_id' => $user->id,
                'company_name' => $validated['company_name'],
                'phone' => $validated['phone'],
                'tax_number' => $validated['tax_number'] ?? null,
                'service_categories' => $validated['service_categories'],
                'service_areas' => $validated['service_areas'],
                'verification_status' => Provider::STATUS_PENDING,
                'activation_token' => Str::random(64),
            ]);

            // Upload verification documents
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $document) {
                    $provider->addMedia($document)
                        ->toMediaCollection('verification_documents');
                }
            }

            DB::commit();

            // TODO: Send notification to admin about new application

            return redirect()
                ->route('provider.apply.success')
                ->with('success', 'Başvurunuz başarıyla alındı. En kısa sürede inceleyip size dönüş yapacağız.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Bir hata oluştu. Lütfen tekrar deneyin.']);
        }
    }

    /**
     * Show success page after application
     */
    public function success()
    {
        return view('provider.apply-success');
    }
}
