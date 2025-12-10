<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'general' => [
                'app_name' => config('app.name', 'SmartIMS'),
                'app_url' => config('app.url'),
                'timezone' => config('app.timezone'),
                'currency' => config('app.currency', 'USD'),
                'date_format' => config('app.date_format', 'Y-m-d'),
                'time_format' => config('app.time_format', 'H:i:s'),
            ],
            'mail' => [
                'mail_host' => config('mail.mailers.smtp.host'),
                'mail_port' => config('mail.mailers.smtp.port'),
                'mail_username' => config('mail.mailers.smtp.username'),
                'mail_password' => config('mail.mailers.smtp.password') ? '********' : '',
                'mail_encryption' => config('mail.mailers.smtp.encryption'),
                'mail_from_address' => config('mail.from.address'),
                'mail_from_name' => config('mail.from.name'),
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
        
        return view('super-admin.settings.index', compact('settings'));
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
        
        $this->updateEnvFile([
            'APP_NAME' => $request->app_name,
            'APP_URL' => $request->app_url,
            'APP_TIMEZONE' => $request->timezone,
        ]);
        
        // Update config file for custom settings
        $this->updateConfigFile('app', [
            'currency' => $request->currency,
            'date_format' => $request->date_format,
            'time_format' => $request->time_format,
        ]);
        
        return back()->with('success', 'General settings updated successfully!');
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
        
        $envUpdates = [
            'MAIL_HOST' => $request->mail_host,
            'MAIL_PORT' => $request->mail_port,
            'MAIL_USERNAME' => $request->mail_username,
            'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            'MAIL_FROM_NAME' => $request->mail_from_name,
        ];
        
        if ($request->filled('mail_password')) {
            $envUpdates['MAIL_PASSWORD'] = $request->mail_password;
        }
        
        if ($request->filled('mail_encryption')) {
            $envUpdates['MAIL_ENCRYPTION'] = $request->mail_encryption;
        }
        
        $this->updateEnvFile($envUpdates);
        
        return back()->with('success', 'Mail settings updated successfully!');
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
        
        $this->updateConfigFile('billing', [
            'currency' => $request->currency,
            'tax_rate' => $request->tax_rate ?? 0,
            'invoice_prefix' => $request->invoice_prefix,
            'due_days' => $request->due_days,
            'late_fee' => $request->late_fee ?? 0,
        ]);
        
        return back()->with('success', 'Billing settings updated successfully!');
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
        
        $this->updateConfigFile('security', [
            'password' => [
                'min_length' => $request->password_min_length,
                'require_numbers' => $request->boolean('password_require_numbers'),
                'require_symbols' => $request->boolean('password_require_symbols'),
                'require_mixed_case' => $request->boolean('password_require_mixed_case'),
            ],
            'login' => [
                'attempts' => $request->login_attempts,
                'lockout_time' => $request->lockout_time,
            ],
            'session' => [
                'timeout' => $request->session_timeout,
            ],
        ]);
        
        return back()->with('success', 'Security settings updated successfully!');
    }
    
    private function updateEnvFile(array $updates)
    {
        $envPath = base_path('.env');
        
        if (!file_exists($envPath)) {
            return;
        }
        
        $envContent = file_get_contents($envPath);
        
        foreach ($updates as $key => $value) {
            // Escape value if needed
            $escapedValue = str_replace('"', '\"', $value);
            
            // Replace or add the key
            $pattern = "/^{$key}=.*/m";
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, "{$key}=\"{$escapedValue}\"", $envContent);
            } else {
                $envContent .= "\n{$key}=\"{$escapedValue}\"";
            }
        }
        
        file_put_contents($envPath, $envContent);
    }
    
    private function updateConfigFile($file, array $updates)
    {
        $configPath = config_path("{$file}.php");
        
        if (!file_exists($configPath)) {
            return;
        }
        
        $config = require $configPath;
        $config = array_merge($config, $updates);
        
        $content = "<?php\n\nreturn " . var_export($config, true) . ";\n";
        file_put_contents($configPath, $content);
    }
    
    public function backupDatabase()
{
    $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
    $backupPath = storage_path('app/backups/');
    
    // Create backup directory if not exists
    if (!file_exists($backupPath)) {
        mkdir($backupPath, 0755, true);
    }
    
    $path = $backupPath . $filename;
    
    // Get database credentials
    $db = config('database.connections.mysql');
    
    // Create backup command
    $command = sprintf(
        'mysqldump --user=%s --password=%s --host=%s %s > %s',
        escapeshellarg($db['username']),
        escapeshellarg($db['password']),
        escapeshellarg($db['host']),
        escapeshellarg($db['database']),
        escapeshellarg($path)
    );
    
    // Execute command
    $returnVar = NULL;
    $output  = NULL;
    exec($command, $output, $returnVar);
    
    // Check if backup was successful
    if ($returnVar !== 0) {
        // Alternative simpler backup method
        $content = "/* Database backup failed via mysqldump */\n";
        $content .= "/* You need to setup mysqldump on your server */\n";
        $content .= "/* Created: " . date('Y-m-d H:i:s') . " */\n";
        file_put_contents($path, $content);
    }
    
    // Check if file exists before downloading
    if (!file_exists($path)) {
        return back()->with('error', 'Backup file could not be created. Please check server permissions.');
    }
    
    return response()->download($path)->deleteFileAfterSend(true);
}
    public function clearCache()
    {
        \Artisan::call('config:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        \Artisan::call('route:clear');
        
        return back()->with('success', 'All cache cleared successfully!');
    }
}