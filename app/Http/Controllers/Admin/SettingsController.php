<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\SettingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\AuditLog;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = [
            'site' => [
                'name' => SettingsManager::get('site_name', config('app.name')),
                'email' => SettingsManager::get('site_email', config('mail.from.address')),
                'support_email' => SettingsManager::get('support_email', env('MAIL_SUPPORT')),
                'url' => SettingsManager::get('app_url', config('app.url')),
                'timezone' => SettingsManager::get('timezone', config('app.timezone')),
                'currency' => SettingsManager::get('currency', 'NGN'),
            ],
            'branding' => [
                'logo_path' => SettingsManager::get('site_logo_path'),
                'logo_url' => $this->logoUrl(),
            ],
            'security' => [
                'max_login_attempts' => SettingsManager::get('security_max_login_attempts', config('banking.security.max_login_attempts', 5)),
                'lockout_time' => SettingsManager::get('security_lockout_time', config('banking.security.lockout_time', 30)),
                'session_timeout' => SettingsManager::get('security_session_timeout', config('banking.security.session_timeout', 30)),
                'two_factor_threshold' => SettingsManager::get('security_two_factor_threshold', config('banking.security.two_factor_threshold', 100000)),
                'admin_approval_threshold' => SettingsManager::get('security_admin_approval_threshold', config('banking.security.admin_approval_threshold', 1000000)),
            ],
        ];

        return inertia('Admin/Settings/Index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update application settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_email' => ['required', 'email', 'max:255'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'app_url' => ['required', 'url', 'max:255'],
            'timezone' => ['required', 'string', 'max:255'],
            'currency' => ['required', 'string', 'max:10'],
            'site_logo' => ['nullable', 'image', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
            'security_max_login_attempts' => ['required', 'integer', 'min:1', 'max:20'],
            'security_lockout_time' => ['required', 'integer', 'min:1', 'max:1440'],
            'security_session_timeout' => ['required', 'integer', 'min:1', 'max:1440'],
            'security_two_factor_threshold' => ['required', 'numeric', 'min:0'],
            'security_admin_approval_threshold' => ['required', 'numeric', 'min:0'],
        ]);

        $logoPath = SettingsManager::get('site_logo_path');

        DB::beginTransaction();

        try {
            $payload = [
                'site_name' => $validated['site_name'],
                'site_email' => $validated['site_email'],
                'support_email' => $validated['support_email'] ?? null,
                'app_url' => $validated['app_url'],
                'timezone' => $validated['timezone'],
                'currency' => $validated['currency'],
                'security_max_login_attempts' => (int) $validated['security_max_login_attempts'],
                'security_lockout_time' => (int) $validated['security_lockout_time'],
                'security_session_timeout' => (int) $validated['security_session_timeout'],
                'security_two_factor_threshold' => (float) $validated['security_two_factor_threshold'],
                'security_admin_approval_threshold' => (float) $validated['security_admin_approval_threshold'],
            ];

            if ($request->boolean('remove_logo')) {
                if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                    Storage::disk('public')->delete($logoPath);
                }

                $payload['site_logo_path'] = null;
                $logoPath = null;
            } elseif ($request->hasFile('site_logo')) {
                $uploaded = $request->file('site_logo')->store('branding', 'public');

                if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                    Storage::disk('public')->delete($logoPath);
                }

                $payload['site_logo_path'] = $uploaded;
                $logoPath = $uploaded;
            }

            SettingsManager::set($payload);

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            report($exception);

            return back()->with('error', 'Failed to update settings. Please try again.');
        }

        config([
            'app.name' => $payload['site_name'],
            'app.url' => $payload['app_url'],
            'app.timezone' => $payload['timezone'],
            'mail.from.address' => $payload['site_email'],
        ]);

        AuditLog::logEvent('system.settings_updated', [
            'admin' => auth()->user()->email ?? 'system',
            'changed' => array_keys($payload),
        ]);

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Clear application cache.
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');

            AuditLog::logEvent('system.cache_cleared', [
                'admin' => auth()->user()->name,
            ]);

            return back()->with('success', 'Application cache cleared successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    /**
     * Run database migrations.
     */
    public function runMigrations()
    {
        try {
            Artisan::call('migrate', ['--force' => true]);

            AuditLog::logEvent('system.migrations_run', [
                'admin' => auth()->user()->name,
            ]);

            return back()->with('success', 'Database migrations executed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to run migrations: ' . $e->getMessage());
        }
    }

    /**
     * Download database backup.
     */
    public function backupDatabase()
    {
        try {
            $dbPath = database_path('database.sqlite');
            
            if (!file_exists($dbPath)) {
                return back()->with('error', 'Database file not found.');
            }

            $filename = 'backup_' . now()->format('Y-m-d_His') . '.sqlite';

            AuditLog::logEvent('system.database_backup', [
                'admin' => auth()->user()->name,
                'filename' => $filename,
            ]);

            return response()->download($dbPath, $filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to backup database: ' . $e->getMessage());
        }
    }

    /**
     * Get system information.
     */
    public function systemInfo()
    {
        $info = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug'),
            'database_connection' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'queue_connection' => config('queue.default'),
            'disk_space' => $this->getDiskSpace(),
        ];

        return inertia('Admin/Settings/SystemInfo', [
            'info' => $info
        ]);
    }

    /**
     * Get available disk space.
     */
    private function getDiskSpace()
    {
        $free = disk_free_space('/');
        $total = disk_total_space('/');
        $used = $total - $free;

        return [
            'total' => $this->formatBytes($total),
            'used' => $this->formatBytes($used),
            'free' => $this->formatBytes($free),
            'percentage' => round(($used / $total) * 100, 2),
        ];
    }

    /**
     * Format bytes to human-readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Resolve the publicly accessible logo URL.
     */
    private function logoUrl(): ?string
    {
        $logoPath = SettingsManager::get('site_logo_path');

        if (! $logoPath) {
            return null;
        }

        if (! Storage::disk('public')->exists($logoPath)) {
            return null;
        }

        return Storage::disk('public')->url($logoPath);
    }
}
