<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class Controller
{
    protected function enforceBotProtection(Request $request, array $options = []): void
    {
        $honeypotField = $options['honeypot_field'] ?? 'internal_code';
        $timestampField = $options['timestamp_field'] ?? 'page_loaded_at';
        $interactionField = $options['interaction_field'] ?? 'interaction_token';
        $minSeconds = $options['min_seconds'] ?? 3;
        $maxSeconds = $options['max_seconds'] ?? 900;

        $honeypotValue = (string) $request->input($honeypotField, '');
        if ($honeypotValue !== '') {
            Log::warning('Honeypot field triggered on auth form.', [
                'ip' => $request->ip(),
                'path' => $request->path(),
                'user_agent' => $request->userAgent(),
            ]);

            throw ValidationException::withMessages([
                'form_security' => 'Security challenge failed. Please refresh the page and try again.',
            ]);
        }

        $interactionToken = $request->input($interactionField);
        if ($interactionToken !== 'human') {
            Log::notice('Missing human interaction token on auth form.', [
                'ip' => $request->ip(),
                'path' => $request->path(),
                'user_agent' => $request->userAgent(),
                'interaction_token' => $interactionToken,
            ]);

            throw ValidationException::withMessages([
                'form_security' => 'Please interact with the page before submitting the form.',
            ]);
        }

        $timestamp = $request->input($timestampField);
        if (empty($timestamp)) {
            Log::notice('Missing security timestamp on auth form.', [
                'ip' => $request->ip(),
                'path' => $request->path(),
                'user_agent' => $request->userAgent(),
            ]);

            throw ValidationException::withMessages([
                'form_security' => 'Security verification failed. Refresh the page and try again.',
            ]);
        }

        try {
            $loadedAt = Carbon::createFromTimestamp((int) $timestamp);
        } catch (\Exception $exception) {
            Log::warning('Invalid security timestamp on auth form.', [
                'ip' => $request->ip(),
                'path' => $request->path(),
                'user_agent' => $request->userAgent(),
            ]);

            throw ValidationException::withMessages([
                'form_security' => 'Security verification failed. Refresh the page and try again.',
            ]);
        }

        $now = now();

        if ($loadedAt->greaterThan($now->copy()->addSeconds(5))) {
            Log::warning('Future-dated security timestamp detected on auth form.', [
                'ip' => $request->ip(),
                'path' => $request->path(),
                'user_agent' => $request->userAgent(),
            ]);

            throw ValidationException::withMessages([
                'form_security' => 'Security verification failed. Refresh the page and try again.',
            ]);
        }

        $elapsedSeconds = $loadedAt->diffInSeconds($now);

        if ($elapsedSeconds < $minSeconds) {
            Log::notice('Form submitted faster than allowed threshold.', [
                'ip' => $request->ip(),
                'path' => $request->path(),
                'user_agent' => $request->userAgent(),
                'elapsed_seconds' => $elapsedSeconds,
            ]);

            throw ValidationException::withMessages([
                'form_security' => 'Please take a moment to review before submitting.',
            ]);
        }

        if ($elapsedSeconds > $maxSeconds) {
            Log::notice('Form submitted after exceeding maximum allowed duration.', [
                'ip' => $request->ip(),
                'path' => $request->path(),
                'user_agent' => $request->userAgent(),
                'elapsed_seconds' => $elapsedSeconds,
            ]);

            throw ValidationException::withMessages([
                'form_security' => 'Session expired. Refresh the page and try again.',
            ]);
        }
    }
}
