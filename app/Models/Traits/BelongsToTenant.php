<?php

namespace App\Models\Traits;

use App\Models\Scopes\TenantScope;
use App\Models\Tenant;
use App\Services\TenantContext;

trait BelongsToTenant
{
    /**
     * Boot the trait and add the global scope
     */
    protected static function bootBelongsToTenant(): void
    {
        // Add global scope to automatically filter by tenant_id
        static::addGlobalScope(new TenantScope);

        // Automatically set tenant_id when creating models
        static::creating(function ($model) {
            if (! isset($model->tenant_id)) {
                $tenantContext = app(TenantContext::class);
                if ($tenantContext->hasTenant()) {
                    $model->tenant_id = $tenantContext->getTenantId();
                }
            }
        });
    }

    /**
     * Get the tenant that owns the model
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
