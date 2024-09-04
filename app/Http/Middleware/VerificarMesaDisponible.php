<?php

namespace App\Http\Middleware;

use App\Models\Mesa;
use Closure;
use Illuminate\Http\Request;

class VerificarMesaDisponible
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $status = Mesa::find($request->mesa->id)->status ?? 1;

        if ($status) {
            return redirect()->route('admin');
        }

        return $next($request);
    }
}
