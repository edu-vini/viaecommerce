<?php

declare(strict_types=1);

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stancl\Tenancy\Database\Models\Domain;

class CaddyController extends Controller
{
    /**
     * Validate whether a domain is authorized for Caddy On-Demand TLS certificate issuance.
     */
    public function ask(Request $request): Response
    {
        $domain = strtolower(trim((string) $request->query('domain', '')));

        if ($domain === '' || filter_var($domain, FILTER_VALIDATE_IP) !== false || in_array($domain, ['localhost'], true)) {
            return response('Invalid domain', 404);
        }

        $centralDomains = array_map('strtolower', config('tenancy.central_domains', []));

        if (in_array($domain, $centralDomains, true)) {
            return response('OK', 200);
        }

        if (Domain::query()->where('domain', $domain)->exists()) {
            return response('OK', 200);
        }

        $baseDomain = strtolower((string) config('tenancy.base_domain', 'viasolutions.com.br'));
        $suffix = '.'.$baseDomain;

        if ($baseDomain !== '' && str_ends_with($domain, $suffix)) {
            $subdomain = substr($domain, 0, -strlen($suffix));

            if ($subdomain !== '' && ! str_contains($subdomain, '.') && Tenant::query()->where('slug', $subdomain)->exists()) {
                return response('OK', 200);
            }
        }

        return response('Domain not registered', 404);
    }
}
