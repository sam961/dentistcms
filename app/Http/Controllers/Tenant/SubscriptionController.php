<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        // Get the current tenant from context
        $tenant = app(\App\Services\TenantContext::class)->getTenant();

        if (! $tenant) {
            abort(403, 'No tenant context found.');
        }

        // Load subscription history for this tenant
        $tenant->load('subscriptionHistory.performedBy');

        return view('subscriptions.index', compact('tenant'));
    }
}
