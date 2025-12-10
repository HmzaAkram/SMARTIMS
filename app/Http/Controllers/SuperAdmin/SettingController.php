<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index()
    {
        // Check if settings table exists
        try {
            // Test database connection and table
            $tableExists = DB::select("SHOW TABLES LIKE 'settings'");
            
            if (empty($tableExists)) {
                // Table doesn't exist, use defaults
                $settings = $this->getDefaultSettings();
            } else {
                // Table exists, fetch from database
                $settings = [
                    'general' => $this->getGeneralSettings(),
                    'mail' => $this->getMailSettings(),
                    'billing' => $this->getBillingSettings(),
                    'security' => $this->getSecuritySettings(),
                ];
            }
        } catch (\Exception $e) {
            // On error, use defaults
            $settings = $this->getDefaultSettings();
        }
        
        return view('super-admin.settings.index', compact('settings'));
    }
    
    private function getDefaultSettings()
    {
        return [
            'general' => [
                'app_name' => config('app.name', 'SmartIMS'),
                'app_url' => config('app.url', 'http://localhost'),
                'timezone' => config('app.timezone', 'UTC'),
                'currency' => 'USD',
                'date_format' => 'Y-m-d',
                'time_format' => 'H:i:s',
            ],
            'mail' => [
                'mail_host' => config('mail.mailers.smtp.host', 'smtp.mailgun.org'),
                'mail_port' => config('mail.mailers.smtp.port', 587),
                'mail_username' => config('mail.mailers.smtp.username', ''),
                'mail_password' => '',
                'mail_encryption' => config('mail.mailers.smtp.encryption', 'tls'),
                'mail_from_address' => config('mail.from.address', 'hello@example.com'),
                'mail_from_name' => config('mail.from.name', 'SmartIMS'),
            ],
            'billing' => [
                'currency' => 'USD',
                'tax_rate' => 0,
                'invoice_prefix' => 'INV',
                'due_days' => 30,
                'late_fee' => 0,
            ],
            'security' => [
                'password_min_length' => 8,
                'password_require_numbers' => true,
                'password_require_symbols' => true,
                'password_require_mixed_case' => true,
                'login_attempts' => 5,
                'lockout_time' => 15,
                'session_timeout' => 60,
            ],
        ];
    }
    
    private function getGeneralSettings()
    {
        $defaults = [
            'app_name' => config('app.name', 'SmartIMS'),
            'app_url' => config('app.url', 'http://localhost'),
            'timezone' => config('app.timezone', 'UTC'),
            'currency' => 'USD',
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i:s',
        ];
        
        $dbSettings = Setting::getGroup('general');
        return array_merge($defaults, $dbSettings);
    }
    
    private function getMailSettings()
    {
        $defaults = [
            'mail_host' => config('mail.mailers.smtp.host', 'smtp.mailgun.org'),
            'mail_port' => config('mail.mailers.smtp.port', 587),
            'mail_username' => config('mail.mailers.smtp.username', ''),
            'mail_password' => '',
            'mail_encryption' => config('mail.mailers.smtp.encryption', 'tls'),
            'mail_from_address' => config('mail.from.address', 'hello@example.com'),
            'mail_from_name' => config('mail.from.name', 'SmartIMS'),
        ];
        
        $dbSettings = Setting::getGroup('mail');
        
        // Don't show actual password
        if (isset($dbSettings['mail_password']) && !empty($dbSettings['mail_password']) && $dbSettings['mail_password'] !== '********') {
            $dbSettings['mail_password'] = '********';
        }
        
        return array_merge($defaults, $dbSettings);
    }
    
    private function getBillingSettings()
    {
        $defaults = [
            'currency' => 'USD',
            'tax_rate' => 0,
            'invoice_prefix' => 'INV',
            'due_days' => 30,
            'late_fee' => 0,
        ];
        
        $dbSettings = Setting::getGroup('billing');
        return array_merge($defaults, $dbSettings);
    }
    
    private function getSecuritySettings()
    {
        $defaults = [
            'password_min_length' => 8,
            'password_require_numbers' => true,
            'password_require_symbols' => true,
            'password_require_mixed_case' => true,
            'login_attempts' => 5,
            'lockout_time' => 15,
            'session_timeout' => 60,
        ];
        
        $dbSettings = Setting::getGroup('security');
        
        // Convert string values to boolean for checkboxes
        $convertedSettings = [];
        foreach ($dbSettings as $key => $value) {
            if (in_array($key, ['password_require_numbers', 'password_require_symbols', 'password_require_mixed_case'])) {
                $convertedSettings[$key] = $value === '1' || $value === true || $value === 'true';
            } else {
                $convertedSettings[$key] = $value;
            }
        }
        
        return array_merge($defaults, $convertedSettings);
    }
    
    public function update(Request $request)
    {
        $section = $request->section;
        
        switch ($section) {
            case 'general':
                return $this->updateGeneralSettings($request);
                
            case 'mail':
                return $this->updateMailSettings($request);
                
            case 'billing':
                return $this->updateBillingSettings($request);
                
            case 'security':
                return $this->updateSecuritySettings($request);
                
            default:
                return back()->with('error', 'Invalid settings section');
        }
    }
    
    private function updateGeneralSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'timezone' => 'required|string',
            'currency' => 'required|string|size:3',
            'date_format' => 'required|string',
            'time_format' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Save to database
        try {
            foreach ($request->except(['_token', 'section']) as $key => $value) {
                Setting::setValue($key, $value, 'general');
            }
            
            return back()->with('success', 'General settings updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save settings: ' . $e->getMessage());
        }
    }
    
    private function updateMailSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer',
            'mail_username' => 'required|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Get current password if not changed
        $currentPassword = Setting::getValue('mail_password');
        $password = $request->mail_password;
        
        // If password field is empty or contains masked value, keep the old one
        if (empty($password) || $password === '********') {
            $password = $currentPassword;
        }
        
        // Save to database
        try {
            $data = $request->except(['_token', 'section', 'mail_password']);
            $data['mail_password'] = $password;
            
            foreach ($data as $key => $value) {
                Setting::setValue($key, $value, 'mail');
            }
            
            return back()->with('success', 'Mail settings updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save mail settings: ' . $e->getMessage());
        }
    }
    
    private function updateBillingSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency' => 'required|string|size:3',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'invoice_prefix' => 'required|string|max:10',
            'due_days' => 'required|integer|min:1|max:365',
            'late_fee' => 'nullable|numeric|min:0',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Save to database
        try {
            foreach ($request->except(['_token', 'section']) as $key => $value) {
                Setting::setValue($key, $value, 'billing');
            }
            
            return back()->with('success', 'Billing settings updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save billing settings: ' . $e->getMessage());
        }
    }
    
    private function updateSecuritySettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password_min_length' => 'required|integer|min:6|max:32',
            'password_require_numbers' => 'boolean',
            'password_require_symbols' => 'boolean',
            'password_require_mixed_case' => 'boolean',
            'login_attempts' => 'required|integer|min:1|max:10',
            'lockout_time' => 'required|integer|min:1|max:1440',
            'session_timeout' => 'required|integer|min:1|max:1440',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Convert boolean values to string for database
        $data = [
            'password_min_length' => $request->password_min_length,
            'password_require_numbers' => $request->has('password_require_numbers') ? '1' : '0',
            'password_require_symbols' => $request->has('password_require_symbols') ? '1' : '0',
            'password_require_mixed_case' => $request->has('password_require_mixed_case') ? '1' : '0',
            'login_attempts' => $request->login_attempts,
            'lockout_time' => $request->lockout_time,
            'session_timeout' => $request->session_timeout,
        ];
        
        // Save to database
        try {
            foreach ($data as $key => $value) {
                Setting::setValue($key, $value, 'security');
            }
            
            return back()->with('success', 'Security settings updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save security settings: ' . $e->getMessage());
        }
    }
    
    public function backupDatabase()
    {
        return back()->with('info', 'Database backup feature requires mysqldump installation. Please contact server administrator.');
    }
    
    public function clearCache()
    {
        try {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            
            return back()->with('success', 'All cache cleared successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }
}