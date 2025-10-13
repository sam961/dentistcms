<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        $currentSubscription = Subscription::where('user_id', Auth::id())
            ->where('status', 'active')
            ->latest()
            ->first();

        $paymentHistory = Subscription::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('subscriptions.index', compact('currentSubscription', 'paymentHistory'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('subscriptions.create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'plan_name' => 'required|string|max:255',
            'plan_description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'payment_method' => 'required|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
        ]);

        $startDate = now();
        $endDate = match ($validated['billing_cycle']) {
            'monthly' => $startDate->copy()->addMonth(),
            'quarterly' => $startDate->copy()->addMonths(3),
            'yearly' => $startDate->copy()->addYear(),
        };

        Subscription::create([
            'user_id' => Auth::id(),
            'plan_name' => $validated['plan_name'],
            'plan_description' => $validated['plan_description'] ?? null,
            'amount' => $validated['amount'],
            'currency' => 'USD',
            'billing_cycle' => $validated['billing_cycle'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'renewal_date' => $endDate,
            'status' => 'active',
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $validated['transaction_id'] ?? null,
            'auto_renewal' => true,
        ]);

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription created successfully!');
    }

    public function show(Subscription $subscription): \Illuminate\Contracts\View\View
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        return view('subscriptions.show', compact('subscription'));
    }

    public function edit(Subscription $subscription): \Illuminate\Contracts\View\View
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        return view('subscriptions.edit', compact('subscription'));
    }

    public function update(Request $request, Subscription $subscription): \Illuminate\Http\RedirectResponse
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'auto_renewal' => 'required|boolean',
            'payment_method' => 'nullable|string|max:255',
        ]);

        $subscription->update([
            'auto_renewal' => $validated['auto_renewal'],
            'payment_method' => $validated['payment_method'] ?? $subscription->payment_method,
        ]);

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription updated successfully!');
    }

    public function cancel(Subscription $subscription): \Illuminate\Http\RedirectResponse
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        $subscription->update([
            'status' => 'cancelled',
            'auto_renewal' => false,
        ]);

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription cancelled successfully!');
    }

    public function renew(Subscription $subscription): \Illuminate\Http\RedirectResponse
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        $newStartDate = now();
        $newEndDate = match ($subscription->billing_cycle) {
            'monthly' => $newStartDate->copy()->addMonth(),
            'quarterly' => $newStartDate->copy()->addMonths(3),
            'yearly' => $newStartDate->copy()->addYear(),
        };

        Subscription::create([
            'user_id' => Auth::id(),
            'plan_name' => $subscription->plan_name,
            'plan_description' => $subscription->plan_description,
            'amount' => $subscription->amount,
            'currency' => $subscription->currency,
            'billing_cycle' => $subscription->billing_cycle,
            'start_date' => $newStartDate,
            'end_date' => $newEndDate,
            'renewal_date' => $newEndDate,
            'status' => 'active',
            'payment_method' => $subscription->payment_method,
            'auto_renewal' => true,
        ]);

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription renewed successfully!');
    }

    public function destroy(Subscription $subscription): \Illuminate\Http\RedirectResponse
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        $subscription->delete();

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription deleted successfully!');
    }
}
