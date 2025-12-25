<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileCompletion
{
    /**
     * Handle an incoming request to check if user profile is complete.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            // Check if user has role 'guru' or 'siswa' but no corresponding data record
            if ($user->role === 'guru' && !$user->dataGuru) {
                // If user is trying to access profile completion page, allow it
                if ($request->route()->getName() === 'profile.complete' || 
                    str_starts_with($request->path(), 'profile/complete')) {
                    return $next($request);
                }
                
                // Redirect to profile completion page
                return redirect()->route('profile.complete')->with('error', 'Please complete your teacher profile to continue.');
            }

            if ($user->role === 'siswa' && !$user->dataSiswa) {
                // If user is trying to access profile completion page, allow it
                if ($request->route()->getName() === 'profile.complete' || 
                    str_starts_with($request->path(), 'profile/complete')) {
                    return $next($request);
                }
                
                // Redirect to profile completion page
                return redirect()->route('profile.complete')->with('error', 'Please complete your student profile to continue.');
            }
        }

        return $next($request);
    }
}