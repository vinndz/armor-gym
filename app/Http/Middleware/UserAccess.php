<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
 
class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
 


    public function handle(Request $request, Closure $next, $role)
    {

        if(auth()->user()->role == $role)
            return $next($request);
        // return response()->json(['You do not have permission to access for this page.']);

        return redirect()->route('login')->with('error', 'Anda tidak memiliki akses yang cukup untuk halaman ini.');
    }

    
}