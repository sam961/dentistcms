<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::withCount(['users', 'patients', 'dentists', 'appointments'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tenants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive,suspended',
            'admin_email' => 'required|email|max:255|unique:users,email',
            'admin_password' => 'required|string|min:8',
        ]);

        $tenant = Tenant::create($validated);

        // Create admin user for this tenant with provided credentials
        // Set email_verified_at to null - they need to verify
        $adminUser = User::create([
            'tenant_id' => $tenant->id,
            'name' => $tenant->name.' Admin',
            'email' => $validated['admin_email'],
            'password' => bcrypt($validated['admin_password']),
            'role' => 'admin',
            'email_verified_at' => null, // Require email verification
        ]);

        // Send email verification code
        $verificationCode = \App\Models\VerificationCode::createFor(
            $adminUser,
            \App\Models\VerificationCode::TYPE_EMAIL_VERIFICATION,
            60 // 1 hour expiration
        );

        \Illuminate\Support\Facades\Mail::to($adminUser->email)
            ->send(new \App\Mail\VerificationCodeMail($verificationCode));

        return redirect()
            ->route('admin.tenants.show', $tenant)
            ->with('success', 'Client created successfully! Verification email sent to '.$validated['admin_email'])
            ->with('admin_email', $validated['admin_email'])
            ->with('admin_password', $validated['admin_password'])
            ->with('info', 'The admin must verify their email before they can log in.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        $tenant->loadCount(['users', 'patients', 'dentists', 'appointments', 'invoices']);

        $recentUsers = $tenant->users()->latest()->limit(5)->get();
        $recentPatients = $tenant->patients()->latest()->limit(5)->get();
        $recentAppointments = $tenant->appointments()->with(['patient', 'dentist'])
            ->latest('appointment_date')
            ->limit(10)
            ->get();

        // Get the admin user for this tenant
        $adminUser = $tenant->users()->where('role', 'admin')->first();

        return view('admin.tenants.show', compact('tenant', 'recentUsers', 'recentPatients', 'recentAppointments', 'adminUser'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        // Get the admin user for this tenant
        $adminUser = $tenant->users()->where('role', 'admin')->first();

        return view('admin.tenants.edit', compact('tenant', 'adminUser'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        // Get the admin user to check if email is unique (except for this user)
        $adminUser = $tenant->users()->where('role', 'admin')->first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive,suspended',
            'admin_email' => 'required|email|max:255|unique:users,email,'.($adminUser ? $adminUser->id : 'NULL'),
            'admin_password' => 'nullable|string|min:8',
        ]);

        $tenant->update($validated);

        // Update admin user
        if ($adminUser) {
            $adminUser->email = $validated['admin_email'];

            // Only update password if provided
            if (! empty($validated['admin_password'])) {
                $adminUser->password = bcrypt($validated['admin_password']);
            }

            $adminUser->save();
        }

        $message = 'Client updated successfully!';
        if (! empty($validated['admin_password'])) {
            $message .= ' Admin password has been changed.';
        }

        return redirect()
            ->route('admin.tenants.show', $tenant)
            ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        $tenantName = $tenant->name;
        $tenant->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', "Client '{$tenantName}' and all associated data have been deleted successfully!");
    }
}
