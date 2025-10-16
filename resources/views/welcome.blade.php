<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Modern Dental Practice Management</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gradient-to-br from-blue-50 via-white to-indigo-50">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <i class="fas fa-tooth text-blue-600 text-2xl mr-3"></i>
                    <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                        {{ config('app.name') }}
                    </span>
                </div>

                @if (Route::has('login'))
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-2 text-gray-700 hover:text-blue-600 transition-colors">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center">
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-gray-900 mb-6">
                    Modern Dental Practice
                    <span class="block bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                        Management System
                    </span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 mb-10 max-w-3xl mx-auto">
                    Streamline your dental practice with our comprehensive, cloud-based solution.
                    Featuring treatment planning, digital imaging, and smart scheduling.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-lg font-semibold rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <i class="fas fa-rocket mr-2"></i>
                            Start Free Trial
                        </a>
                    @endif
                    <a href="#features" class="px-8 py-4 bg-white text-blue-600 text-lg font-semibold rounded-xl border-2 border-blue-600 hover:bg-blue-50 transition-all duration-300">
                        <i class="fas fa-play-circle mr-2"></i>
                        See Features
                    </a>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-16 max-w-4xl mx-auto">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-blue-600 mb-2">80%</div>
                        <div class="text-gray-600">Feature Parity</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-indigo-600 mb-2">100%</div>
                        <div class="text-gray-600">Cloud-Based</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-purple-600 mb-2">24/7</div>
                        <div class="text-gray-600">Access</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-pink-600 mb-2">$79</div>
                        <div class="text-gray-600">Starting Price</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Powerful Features for Modern Dentistry
                </h2>
                <p class="text-xl text-gray-600">
                    Everything you need to run a successful dental practice
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Treatment Planning -->
                <div class="group bg-gradient-to-br from-blue-50 to-white p-8 rounded-2xl border-2 border-blue-100 hover:border-blue-600 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-clipboard-list text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Treatment Planning</h3>
                    <p class="text-gray-600 mb-4">
                        Create comprehensive treatment plans with phases, cost estimation, and insurance coverage. Track progress and email plans directly to patients.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Phase organization</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Cost breakdown</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Progress tracking</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Email functionality</li>
                    </ul>
                </div>

                <!-- Digital Imaging -->
                <div class="group bg-gradient-to-br from-purple-50 to-white p-8 rounded-2xl border-2 border-purple-100 hover:border-purple-600 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-pink-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-images text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Digital Imaging</h3>
                    <p class="text-gray-600 mb-4">
                        Professional image management with PhotoSwipe viewer, X-rays, intraoral photos, CBCT scans. Advanced gallery with zoom and filtering.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Multiple image types</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>PhotoSwipe viewer</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Advanced filtering</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Cloud storage</li>
                    </ul>
                </div>

                <!-- Smart Scheduling -->
                <div class="group bg-gradient-to-br from-green-50 to-white p-8 rounded-2xl border-2 border-green-100 hover:border-green-600 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-600 to-emerald-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-calendar-check text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Smart Scheduling</h3>
                    <p class="text-gray-600 mb-4">
                        Real-time availability checking with conflict prevention. Multi-step booking process with duration-based scheduling and status tracking.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Real-time availability</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Conflict prevention</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Multi-step booking</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Status management</li>
                    </ul>
                </div>

                <!-- Interactive Dental Chart -->
                <div class="group bg-gradient-to-br from-orange-50 to-white p-8 rounded-2xl border-2 border-orange-100 hover:border-orange-600 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-600 to-red-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-tooth text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Interactive Dental Chart</h3>
                    <p class="text-gray-600 mb-4">
                        Beautiful, intuitive dental charting with click-to-select. Track conditions, treatments, and history for every tooth with modern gradient UI.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Adult & primary teeth</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Condition tracking</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Treatment history</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Modern UI</li>
                    </ul>
                </div>

                <!-- Patient Management -->
                <div class="group bg-gradient-to-br from-indigo-50 to-white p-8 rounded-2xl border-2 border-indigo-100 hover:border-indigo-600 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-600 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Patient Management</h3>
                    <p class="text-gray-600 mb-4">
                        Comprehensive patient records with medical history, contact information, family relationships, and document storage.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Full patient profiles</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Medical history</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Family grouping</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Document storage</li>
                    </ul>
                </div>

                <!-- Multi-Tenant SaaS -->
                <div class="group bg-gradient-to-br from-pink-50 to-white p-8 rounded-2xl border-2 border-pink-100 hover:border-pink-600 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-pink-600 to-rose-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-building text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Multi-Tenant Architecture</h3>
                    <p class="text-gray-600 mb-4">
                        Full data isolation per practice. Perfect for managing multiple locations or running a SaaS dental platform.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Data isolation</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Multi-location support</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Subscription management</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Secure & scalable</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Technology Stack -->
    <section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Built with Modern Technology
                </h2>
                <p class="text-xl text-gray-600">
                    Leveraging the latest tools for performance and reliability
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="bg-white p-6 rounded-xl text-center shadow-lg hover:shadow-2xl transition-all">
                    <div class="text-4xl mb-3">🚀</div>
                    <h4 class="font-bold text-gray-900 mb-2">Laravel 12</h4>
                    <p class="text-sm text-gray-600">Latest PHP framework</p>
                </div>
                <div class="bg-white p-6 rounded-xl text-center shadow-lg hover:shadow-2xl transition-all">
                    <div class="text-4xl mb-3">🎨</div>
                    <h4 class="font-bold text-gray-900 mb-2">Tailwind CSS</h4>
                    <p class="text-sm text-gray-600">Modern utility-first CSS</p>
                </div>
                <div class="bg-white p-6 rounded-xl text-center shadow-lg hover:shadow-2xl transition-all">
                    <div class="text-4xl mb-3">⚡</div>
                    <h4 class="font-bold text-gray-900 mb-2">Alpine.js</h4>
                    <p class="text-sm text-gray-600">Reactive UI framework</p>
                </div>
                <div class="bg-white p-6 rounded-xl text-center shadow-lg hover:shadow-2xl transition-all">
                    <div class="text-4xl mb-3">☁️</div>
                    <h4 class="font-bold text-gray-900 mb-2">Cloud-Native</h4>
                    <p class="text-sm text-gray-600">100% cloud-based</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Simple, Transparent Pricing
                </h2>
                <p class="text-xl text-gray-600">
                    No hidden fees. No long-term contracts. Cancel anytime.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Starter Plan -->
                <div class="bg-white p-8 rounded-2xl border-2 border-gray-200 hover:border-blue-600 hover:shadow-2xl transition-all duration-300">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Starter</h3>
                    <div class="mb-6">
                        <span class="text-5xl font-bold text-gray-900">$79</span>
                        <span class="text-gray-600">/month</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-600 mr-3 mt-1"></i>
                            <span class="text-gray-600">1 Dentist</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-600 mr-3 mt-1"></i>
                            <span class="text-gray-600">Unlimited patients</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-600 mr-3 mt-1"></i>
                            <span class="text-gray-600">Appointments & charting</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-600 mr-3 mt-1"></i>
                            <span class="text-gray-600">Basic reporting</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-600 mr-3 mt-1"></i>
                            <span class="text-gray-600">Email support</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block w-full text-center px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                        Get Started
                    </a>
                </div>

                <!-- Professional Plan -->
                <div class="bg-gradient-to-br from-blue-600 to-indigo-600 p-8 rounded-2xl shadow-2xl transform scale-105 relative">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-yellow-400 text-gray-900 px-4 py-1 rounded-full text-sm font-bold">
                        POPULAR
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Professional</h3>
                    <div class="mb-6">
                        <span class="text-5xl font-bold text-white">$129</span>
                        <span class="text-blue-100">/month</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start">
                            <i class="fas fa-check text-yellow-400 mr-3 mt-1"></i>
                            <span class="text-white">Up to 3 dentists</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-yellow-400 mr-3 mt-1"></i>
                            <span class="text-white">All Starter features</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-yellow-400 mr-3 mt-1"></i>
                            <span class="text-white">Treatment planning</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-yellow-400 mr-3 mt-1"></i>
                            <span class="text-white">Digital imaging</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-yellow-400 mr-3 mt-1"></i>
                            <span class="text-white">Priority support</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block w-full text-center px-6 py-3 bg-white text-blue-600 rounded-lg hover:bg-gray-100 transition-colors font-semibold">
                        Get Started
                    </a>
                </div>

                <!-- Enterprise Plan -->
                <div class="bg-white p-8 rounded-2xl border-2 border-gray-200 hover:border-blue-600 hover:shadow-2xl transition-all duration-300">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Enterprise</h3>
                    <div class="mb-6">
                        <span class="text-5xl font-bold text-gray-900">$199</span>
                        <span class="text-gray-600">/month</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-600 mr-3 mt-1"></i>
                            <span class="text-gray-600">Up to 10 dentists</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-600 mr-3 mt-1"></i>
                            <span class="text-gray-600">All Professional features</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-600 mr-3 mt-1"></i>
                            <span class="text-gray-600">Advanced reporting</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-600 mr-3 mt-1"></i>
                            <span class="text-gray-600">Multiple locations</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-600 mr-3 mt-1"></i>
                            <span class="text-gray-600">Dedicated support</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block w-full text-center px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-indigo-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Ready to Transform Your Practice?
            </h2>
            <p class="text-xl text-blue-100 mb-10">
                Join modern dental practices using our platform. Start your free trial today.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-blue-600 text-lg font-semibold rounded-xl hover:bg-gray-100 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-rocket mr-2"></i>
                    Start Free Trial
                </a>
                <a href="{{ route('login') }}" class="px-8 py-4 bg-transparent text-white text-lg font-semibold rounded-xl border-2 border-white hover:bg-white hover:text-blue-600 transition-all duration-300">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Sign In
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <i class="fas fa-tooth text-blue-500 text-2xl mr-3"></i>
                        <span class="text-xl font-bold text-white">{{ config('app.name') }}</span>
                    </div>
                    <p class="text-sm">
                        Modern dental practice management for the digital age.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Product</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Documentation</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Company</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">About</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Privacy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Security</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved. Built with Laravel 12 & Tailwind CSS.</p>
            </div>
        </div>
    </footer>
</body>
</html>
