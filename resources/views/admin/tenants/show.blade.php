<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-clinic-medical text-blue-600 mr-3"></i>
                    {{ $tenant->name }}
                </h2>
                <p class="text-gray-600 mt-2">Client Details and Statistics</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.dashboard') }}" class="btn-modern btn-secondary inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Dashboard
                </a>
                <a href="{{ route('admin.tenants.subscription', $tenant) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition">
                    <i class="fas fa-credit-card mr-2"></i>
                    Manage Subscription
                </a>
                <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn-modern btn-primary inline-flex items-center">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Client
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('admin_email') && session('admin_password'))
                <div class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded-r-lg shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-key text-blue-400 text-2xl"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold text-blue-900 mb-2">
                                Admin Credentials Created
                            </h3>
                            <p class="text-sm text-blue-700 mb-4">
                                Save these credentials and share them with the clinic admin. They won't be shown again.
                            </p>
                            <div class="bg-white rounded-md p-4 border border-blue-200">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-semibold text-gray-600 uppercase">Admin Email</label>
                                        <div class="mt-1 flex items-center">
                                            <code class="text-sm font-mono bg-gray-100 px-3 py-2 rounded border border-gray-300 flex-1">{{ session('admin_email') }}</code>
                                            <button onclick="copyToClipboard('{{ session('admin_email') }}')" class="ml-2 text-blue-600 hover:text-blue-700">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-gray-600 uppercase">Admin Password</label>
                                        <div class="mt-1 flex items-center">
                                            <code class="text-sm font-mono bg-gray-100 px-3 py-2 rounded border border-gray-300 flex-1">{{ session('admin_password') }}</code>
                                            <button onclick="copyToClipboard('{{ session('admin_password') }}')" class="ml-2 text-blue-600 hover:text-blue-700">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @if($tenant->primary_domain)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <label class="text-xs font-semibold text-gray-600 uppercase">Login URL</label>
                                        <div class="mt-1">
                                            <a href="http://{{ $tenant->primary_domain }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-700 underline">
                                                http://{{ $tenant->primary_domain }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Client Info Card -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                <div class="px-8 py-6 bg-gradient-to-r from-blue-50 via-purple-50 to-pink-50 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl mr-4 shadow-lg">
                                <i class="fas fa-info-circle text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Client Information</h3>
                                <p class="text-sm text-gray-600">Basic details and contact information</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                            @if($tenant->status === 'active') bg-green-100 text-green-800
                            @elseif($tenant->status === 'inactive') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            @if($tenant->status === 'active')
                                <i class="fas fa-check-circle mr-1"></i>
                            @elseif($tenant->status === 'inactive')
                                <i class="fas fa-pause-circle mr-1"></i>
                            @else
                                <i class="fas fa-ban mr-1"></i>
                            @endif
                            {{ ucfirst($tenant->status) }}
                        </span>
                    </div>
                </div>
                <div class="p-8">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Clinic Name</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $tenant->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Subdomain</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">
                                @if($tenant->subdomain)
                                    <a href="{{ $tenant->getUrl() }}" target="_blank" class="text-blue-600 hover:text-blue-700 hover:underline">
                                        {{ $tenant->subdomain . '.' . config('app.domain') }}
                                        <i class="fas fa-external-link-alt text-xs ml-1"></i>
                                    </a>
                                @else
                                    Not set
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Custom Domain</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $tenant->domain ?? 'Not set' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $tenant->email ?? 'Not set' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $tenant->phone ?? 'Not set' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created At</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $tenant->created_at->format('M d, Y') }}</dd>
                        </div>
                        @if($tenant->address)
                            <div class="col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Address</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $tenant->address }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Admin User Credentials -->
            @if($adminUser)
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl shadow-xl overflow-hidden border-2 border-blue-200">
                    <div class="px-8 py-6 bg-gradient-to-r from-blue-600 to-indigo-600 border-b border-blue-700">
                        <div class="flex items-center">
                            <div class="p-3 bg-white/20 backdrop-blur rounded-2xl mr-4 shadow-lg">
                                <i class="fas fa-user-shield text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Admin User Credentials</h3>
                                <p class="text-sm text-blue-100">Login credentials for the clinic administrator</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white rounded-xl p-6 shadow-md border border-blue-100">
                                <label class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2 block">
                                    <i class="fas fa-envelope text-blue-600 mr-2"></i>Admin Email
                                </label>
                                <div class="flex items-center justify-between">
                                    <code id="admin-email" class="text-base font-mono bg-gray-50 px-4 py-3 rounded-lg border-2 border-gray-200 flex-1 text-gray-900">{{ $adminUser->email }}</code>
                                    <button onclick="copyToClipboard('{{ $adminUser->email }}', 'email')" class="ml-3 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl p-6 shadow-md border border-blue-100">
                                <label class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2 block">
                                    <i class="fas fa-user text-blue-600 mr-2"></i>Admin Name
                                </label>
                                <div class="text-base font-semibold bg-gray-50 px-4 py-3 rounded-lg border-2 border-gray-200 text-gray-900">
                                    {{ $adminUser->name }}
                                </div>
                            </div>

                            <div class="col-span-2 bg-white rounded-xl p-6 shadow-md border border-blue-100">
                                <label class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2 block">
                                    <i class="fas fa-link text-blue-600 mr-2"></i>Login URL
                                </label>
                                <div class="flex items-center justify-between">
                                    <a href="{{ $tenant->getUrl('/login') }}" target="_blank" id="login-url" class="text-base text-blue-600 hover:text-blue-700 underline font-medium bg-gray-50 px-4 py-3 rounded-lg border-2 border-gray-200 flex-1">
                                        {{ $tenant->getUrl('/login') }}
                                    </a>
                                    <button onclick="copyToClipboard('{{ $tenant->getUrl('/login') }}', 'url')" class="ml-3 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-span-2 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-6 border-2 border-amber-200">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-amber-600 text-2xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-bold text-amber-900 mb-2">
                                            <i class="fas fa-lock mr-1"></i>Password Security Notice
                                        </h4>
                                        <p class="text-sm text-amber-800">
                                            For security reasons, passwords are encrypted and cannot be displayed.
                                            The admin user should change their password after first login.
                                            If needed, you can reset the password from the edit page.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Actions Card -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-3xl shadow-xl overflow-hidden border-2 border-purple-200">
                <div class="p-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">
                                <i class="fas fa-credit-card text-purple-600 mr-3"></i>
                                Subscription Management
                            </h3>
                            <p class="text-gray-600 mb-4">Manage billing, plans, and subscription history</p>
                        </div>
                        <a href="{{ route('admin.tenants.subscription', $tenant) }}" class="inline-flex items-center px-6 py-3 bg-purple-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-wider hover:bg-purple-700 transition shadow-lg">
                            <i class="fas fa-arrow-right mr-2"></i>
                            Manage Subscription
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-100 rounded-xl">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">{{ $tenant->users_count }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Users</div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-green-100 rounded-xl">
                            <i class="fas fa-user-injured text-green-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">{{ $tenant->patients_count }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Patients</div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-purple-100 rounded-xl">
                            <i class="fas fa-user-md text-purple-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">{{ $tenant->dentists_count }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Dentists</div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-orange-100 rounded-xl">
                            <i class="fas fa-calendar text-orange-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">{{ $tenant->appointments_count }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Appointments</div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Users -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-users text-blue-600 mr-2"></i>
                            Recent Users
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($recentUsers->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentUsers as $user)
                                    <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                        </div>
                                        <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-4">No users yet</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Patients -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-user-injured text-green-600 mr-2"></i>
                            Recent Patients
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($recentPatients->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentPatients as $patient)
                                    <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $patient->full_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $patient->email }}</p>
                                        </div>
                                        <span class="text-xs text-gray-500">
                                            {{ $patient->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-4">No patients yet</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Appointments -->
            @if($recentAppointments->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-calendar text-orange-600 mr-2"></i>
                            Recent Appointments
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dentist</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentAppointments as $appointment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div>{{ $appointment->appointment_date->format('M d, Y') }}</div>
                                            <div class="text-gray-500">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $appointment->patient->full_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Dr. {{ $appointment->dentist->full_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($appointment->status === 'confirmed') bg-green-100 text-green-800
                                                @elseif($appointment->status === 'completed') bg-blue-100 text-blue-800
                                                @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
    function copyToClipboard(text, type) {
        navigator.clipboard.writeText(text).then(() => {
            // Create a temporary success message
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.classList.add('bg-green-600', 'hover:bg-green-700');
            btn.classList.remove('bg-blue-600', 'hover:bg-blue-700');

            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.classList.remove('bg-green-600', 'hover:bg-green-700');
                btn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            }, 2000);
        });
    }
    </script>
    @endpush
</x-app-layout>
