<?php

namespace App\Http\Controllers\Web\Backend\Settings;

use App\Http\Controllers\Web\Controller;
use App\Models\CredentialSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class StripeSettingController extends Controller {
    /**
     * Display stripe settings page.
     *
     * @return View
     */
    public function index(): View {
        return view('backend.layouts.settings.stripe_settings');
    }
    /**
     * Display stripe settings page.
     *
     * @return View
     */
    public function google(): View {
        return view('backend.layouts.settings.google_settings');
    }

    public function edit()
    {
        // Retrieve the first policy entry or create a new instance if none exists
        $credential = CredentialSetting::first() ?? new CredentialSetting();
        return view('backend.layouts.credential', compact('credential'));
    }

    public function update(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'paypal_mode' => 'nullable|string',
            'paypal_client_id' => 'nullable|string',
            'paypal_client_secret_id' => 'nullable|string',
        ]);

        // Retrieve or create the policy record
        $credential = CredentialSetting::first() ?? new CredentialSetting();

        // Update policy content
        $credential->paypal_mode = $request->paypal_mode;
        $credential->paypal_client_id = $request->paypal_client_id;
        $credential->paypal_client_secret_id = $request->paypal_client_secret_id;
        $credential->save();

        // Update .env file
        $this->updateEnv([
            'PAYPAL_MODE' => $request->paypal_mode,
            'PAYPAL_SANDBOX_CLIENT_ID' => $request->paypal_client_id,
            'PAYPAL_SANDBOX_CLIENT_SECRET' => $request->paypal_client_secret_id,
        ]);

        // Clear cache for changes to take effect
        Artisan::call('config:clear');

        // Redirect back with success message
        return back()->with('t-success', 'Credentials successfully updated');
    }

    // Private function to update .env datas
    private function updateEnv($data)
    {
        $envPath = base_path('.env');

        if (File::exists($envPath)) {
            $envContent = File::get($envPath);

            foreach ($data as $key => $value) {
                $pattern = "/^{$key}=.*/m";
                $replacement = "{$key}={$value}";

                if (preg_match($pattern, $envContent)) {
                    $envContent = preg_replace($pattern, $replacement, $envContent);
                } else {
                    $envContent .= "\n{$key}={$value}";
                }
            }

            File::put($envPath, $envContent);
        }
    }

}
