<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user()) {
            App::setLocale(Auth::user()->locale);
        } else {
            // $acceptHeader is something like this: "en-US,en;q=0.8,uk;q=0.6,pl;q=0.4,ru;q=0.2"
            $acceptHeader = $request->server->get('HTTP_ACCEPT_LANGUAGE');
            // we need only first two letters from $acceptHeader
            $acceptLocale = substr($acceptHeader, 0, 2);
            $availableLocales = config('app.locales');
            // if accept locale is not in available, then set fallback_locale (default en)
            $localeToSet = in_array($acceptLocale, array_keys($availableLocales)) ? $acceptLocale : config('app.fallback_locale');
            App::setLocale($localeToSet);
        }

        return $next($request);
    }
}
