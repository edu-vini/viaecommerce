<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedById;
use Stancl\Tenancy\Resolvers\DomainTenantResolver;
use Stancl\Tenancy\Tenancy;
use Symfony\Component\HttpFoundation\Response;

class InitializeStoreTenancy
{
    public function __construct(
        protected Tenancy $tenancy,
        protected DomainTenantResolver $resolver,
    ) {}

    /**
     * Resolve the current store tenant by custom domain, subdomain, or local fallback.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        if ($host === 'ecommerce.viasolutions.com.br') {
            if ($this->tenancy->initialized) {
                $this->tenancy->end();
            }

            return $next($request);
        }

        $centralDomains = config('tenancy.central_domains', []);

        if (in_array($host, $centralDomains, true)) {
            $tenant = $this->resolveCentralFallback();
        } else {
            $tenant = $this->resolveFromHost($host);
        }

        if ($tenant) {
            if (! $tenant->isActive()) {
                return response()->view('errors.tenant-paused', [
                    'tenant' => $tenant,
                ], 503);
            }

            if ($this->tenancy->initialized && tenant('id') !== $tenant->id) {
                $this->tenancy->end();
            }

            if (! $this->tenancy->initialized) {
                $this->tenancy->initialize($tenant);
            }
        }

        return $next($request);
    }

    protected function resolveFromHost(string $host): ?Tenant
    {
        try {
            /** @var Tenant $tenant */
            $tenant = $this->resolver->resolve($host);

            return $tenant;
        } catch (\Throwable) {
            // Fall through to subdomain / slug lookup
        }

        $subdomain = explode('.', $host)[0] ?? '';

        if ($subdomain !== '') {
            try {
                /** @var Tenant $tenant */
                $tenant = $this->resolver->resolve($subdomain);

                return $tenant;
            } catch (\Throwable) {
                // Fall through to slug lookup
            }

            $tenant = Tenant::where('slug', $subdomain)->first();

            if ($tenant) {
                return $tenant;
            }
        }

        abort(404, 'Loja não encontrada para este domínio.');
    }

    protected function resolveCentralFallback(): ?Tenant
    {
        $fallbackId = config('tenancy.fallback_tenant_id');

        if (! $fallbackId && ! app()->environment(['local', 'testing'])) {
            return null;
        }

        try {
            return $fallbackId
                ? Tenant::find($fallbackId)
                : Tenant::where('is_active', true)->first();
        } catch (TenantCouldNotBeIdentifiedById|\Throwable) {
            return null;
        }
    }
}
