<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCentralDomain
{
    /**
     * Restrict SuperAdmin routes to central platform domains only.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $centralDomains = config('tenancy.central_domains', []);

        if (! in_array($host, $centralDomains, true)) {
            abort(404);
        }

        if (tenancy()->initialized) {
            tenancy()->end();
        }

        return $next($request);
    }
}
