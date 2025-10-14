<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        // Get the current tenant from context
        $tenant = app(\App\Services\TenantContext::class)->getTenant();

        if (!$tenant) {
            abort(403, 'No tenant context found.');
        }

        // Load subscription history for this tenant
        $tenant->load('subscriptionHistory.performedBy');

        return view('subscriptions.index', compact('tenant'));
    }
}
