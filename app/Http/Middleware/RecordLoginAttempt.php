<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginHistory;
use Jenssegers\Agent\Agent;

class RecordLoginAttempt
{
    use \App\Traits\SendsAdminAlerts;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $response = $next($request);
        
        // Only process for login attempts
        if ($this->isLoginAttempt($request)) {
            $credentials = $this->credentials($request);
            $user = $this->getUser($credentials);
            
            if ($this->isSuccessfulLogin($request, $user)) {
                $this->recordSuccess($user, $request);
            } else {
                $this->recordFailure($user, $credentials['email'] ?? 'unknown', $request);
                
                // Alert admins about failed login attempt
                $this->alertFailedLogin(
                    $credentials['email'] ?? 'unknown',
                    $request->ip()
                );
                
                // Check for suspicious activity (multiple failed attempts)
                $this->checkSuspiciousActivity($credentials['email'] ?? null, $request);
            }
        }
        
        return $response;
    }
    
    /**
     * Check for suspicious login activity.
     *
     * @param  string|null  $email
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function checkSuspiciousActivity($email, Request $request)
    {
        if (!$email) {
            return;
        }
        
        // Count failed attempts in the last 15 minutes
        $recentFailures = LoginHistory::where('email', $email)
            ->where('login_successful', false)
            ->where('created_at', '>=', now()->subMinutes(15))
            ->count();
            
        if ($recentFailures >= 3) {
            $user = \App\Models\User::where('email', $email)->first();
            
            $this->alertSuspiciousActivity(
                "Multiple failed login attempts ({$recentFailures} in 15 minutes)",
                $user ?? $email,
                [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'time' => now()->toDateTimeString(),
                ]
            );
        }
    }
    
    /**
     * Check if the request is a login attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isLoginAttempt(Request $request)
    {
        return $request->routeIs('login') && $request->isMethod('post');
    }
    
    /**
     * Get the login credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only('email', 'password');
    }
    
    /**
     * Get the user for the given credentials.
     *
     * @param  array  $credentials
     * @return \App\Models\User|null
     */
    protected function getUser(array $credentials)
    {
        if (empty($credentials['email'])) {
            return null;
        }
        
        return \App\Models\User::where('email', $credentials['email'])->first();
    }
    
    /**
     * Determine if the login was successful.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User|null  $user
     * @return bool
     */
    protected function isSuccessfulLogin(Request $request, $user)
    {
        if (!$user) {
            return false;
        }
        
        return Auth::attempt(
            $request->only('email', 'password'),
            $request->filled('remember')
        );
    }
    
    /**
     * Record a successful login attempt.
     *
     * @param  \App\Models\User  $user
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function recordSuccess($user, Request $request)
    {
        try {
            LoginHistory::recordSuccess($user, $request);
            
            // Update last login timestamp
            $user->last_login_at = now();
            $user->save();
        } catch (\Exception $e) {
            \Log::error('Failed to record successful login: ' . $e->getMessage());
        }
    }
    
    /**
     * Record a failed login attempt.
     *
     * @param  \App\Models\User|null  $user
     * @param  string  $email
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function recordFailure($user, $email, Request $request)
    {
        try {
            $reason = $user 
                ? 'Invalid password' 
                : 'User with this email does not exist';
                
            LoginHistory::recordFailure($user, $reason, $request);
        } catch (\Exception $e) {
            \Log::error('Failed to record failed login: ' . $e->getMessage());
        }
    }
}
