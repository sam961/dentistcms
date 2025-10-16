<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dental Hub - Modern Dental Practice Management Software</title>
    <meta name="description" content="Transform your dental practice with Dental Hub. All-in-one practice management software designed for modern dental clinics. Start your free trial today!">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="antialiased bg-white">

    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-md border-b border-gray-100 fixed w-full top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center space-x-3">
                        <div class="w-12 h-12 gradient-bg rounded-2xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-tooth text-white text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">Dental Hub</span>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-purple-600 font-medium transition-colors duration-200">Features</a>
                    <a href="#pricing" class="text-gray-700 hover:text-purple-600 font-medium transition-colors duration-200">Pricing</a>
                    <a href="#testimonials" class="text-gray-700 hover:text-purple-600 font-medium transition-colors duration-200">Testimonials</a>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        @if(Auth::user()->isSuperAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                Dashboard
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-purple-600 font-medium transition-colors duration-200">
                            Sign In
                        </a>
                        <a href="{{ route('landing.contact') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fas fa-rocket mr-2"></i>
                            Start Free Trial
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-24 overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-purple-50 via-blue-50 to-pink-50">
            <div class="absolute top-20 left-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-float"></div>
            <div class="absolute top-40 right-10 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-float" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-20 left-1/2 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-float" style="animation-delay: 4s;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center animate-fadeInUp">
                <div class="inline-flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm rounded-full shadow-md mb-6">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                    <span class="text-sm font-medium text-gray-700">Join 1000+ dental practices worldwide</span>
                </div>

                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-gray-900 tracking-tight mb-6">
                    Transform Your <br>
                    <span class="gradient-text">Dental Practice</span>
                </h1>

                <p class="mt-6 max-w-3xl mx-auto text-xl sm:text-2xl text-gray-600 leading-relaxed">
                    The all-in-one practice management platform that helps dental clinics streamline operations, enhance patient care, and grow revenue.
                </p>

                <div class="mt-12 flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('landing.contact') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white text-lg font-semibold rounded-2xl shadow-2xl hover:shadow-purple-500/50 transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-rocket mr-3 group-hover:animate-bounce"></i>
                        Start Free 14-Day Trial
                        <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="#features" class="inline-flex items-center justify-center px-8 py-4 bg-white hover:bg-gray-50 text-gray-900 text-lg font-semibold rounded-2xl shadow-lg border-2 border-gray-200 hover:border-purple-300 transition-all duration-300">
                        <i class="fas fa-play-circle mr-3"></i>
                        Watch Demo
                    </a>
                </div>

                <div class="mt-12 flex items-center justify-center gap-8 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        No credit card required
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Setup in 5 minutes
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Cancel anytime
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trust Badges -->
    <div class="py-12 bg-white border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-center gap-x-12 gap-y-6 opacity-60">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-shield-alt text-2xl text-purple-600"></i>
                    <span class="text-sm font-semibold text-gray-700">HIPAA Compliant</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-lock text-2xl text-purple-600"></i>
                    <span class="text-sm font-semibold text-gray-700">256-bit Encryption</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-cloud text-2xl text-purple-600"></i>
                    <span class="text-sm font-semibold text-gray-700">Cloud-Based</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-headset text-2xl text-purple-600"></i>
                    <span class="text-sm font-semibold text-gray-700">24/7 Support</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-24 bg-gradient-to-b from-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <span class="inline-block px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold mb-4">FEATURES</span>
                <h2 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-6">
                    Everything Your Practice Needs
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Powerful features designed to make your dental practice more efficient, organized, and profitable.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature Card 1 -->
                <div class="group bg-white p-8 rounded-3xl border border-gray-200 hover:border-purple-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Patient Management</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">Complete patient profiles with medical history, treatment records, and automated appointment reminders.</p>
                    <a href="#" class="inline-flex items-center text-purple-600 font-semibold hover:text-purple-700">
                        Learn more <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>

                <!-- Feature Card 2 -->
                <div class="group bg-white p-8 rounded-3xl border border-gray-200 hover:border-purple-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <i class="fas fa-calendar-check text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Smart Scheduling</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">Real-time availability, conflict prevention, and automated reminders to reduce no-shows by 60%.</p>
                    <a href="#" class="inline-flex items-center text-purple-600 font-semibold hover:text-purple-700">
                        Learn more <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>

                <!-- Feature Card 3 -->
                <div class="group bg-white p-8 rounded-3xl border border-gray-200 hover:border-purple-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <i class="fas fa-file-invoice-dollar text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Billing & Invoicing</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">Professional invoicing, payment tracking, and automated reminders to improve cash flow.</p>
                    <a href="#" class="inline-flex items-center text-purple-600 font-semibold hover:text-purple-700">
                        Learn more <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>

                <!-- Feature Card 4 -->
                <div class="group bg-white p-8 rounded-3xl border border-gray-200 hover:border-purple-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <i class="fas fa-tooth text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Digital Dental Charts</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">Interactive dental charts with tooth-by-tooth tracking, images, and treatment history.</p>
                    <a href="#" class="inline-flex items-center text-purple-600 font-semibold hover:text-purple-700">
                        Learn more <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>

                <!-- Feature Card 5 -->
                <div class="group bg-white p-8 rounded-3xl border border-gray-200 hover:border-purple-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Analytics & Reports</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">Real-time insights on revenue, appointments, and patient trends to grow your practice.</p>
                    <a href="#" class="inline-flex items-center text-purple-600 font-semibold hover:text-purple-700">
                        Learn more <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>

                <!-- Feature Card 6 -->
                <div class="group bg-white p-8 rounded-3xl border border-gray-200 hover:border-purple-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <i class="fas fa-shield-virus text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Secure & Compliant</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">HIPAA compliant with bank-level encryption, daily backups, and 99.9% uptime guarantee.</p>
                    <a href="#" class="inline-flex items-center text-purple-600 font-semibold hover:text-purple-700">
                        Learn more <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="py-20 gradient-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="animate-fadeInUp">
                    <div class="text-5xl font-extrabold text-white mb-2">1000+</div>
                    <div class="text-purple-100 text-lg">Dental Practices</div>
                </div>
                <div class="animate-fadeInUp" style="animation-delay: 0.1s;">
                    <div class="text-5xl font-extrabold text-white mb-2">50K+</div>
                    <div class="text-purple-100 text-lg">Appointments Managed</div>
                </div>
                <div class="animate-fadeInUp" style="animation-delay: 0.2s;">
                    <div class="text-5xl font-extrabold text-white mb-2">99.9%</div>
                    <div class="text-purple-100 text-lg">Uptime Guaranteed</div>
                </div>
                <div class="animate-fadeInUp" style="animation-delay: 0.3s;">
                    <div class="text-5xl font-extrabold text-white mb-2">24/7</div>
                    <div class="text-purple-100 text-lg">Customer Support</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Section -->
    <div id="pricing" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <span class="inline-block px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold mb-4">PRICING</span>
                <h2 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-6">
                    Simple, Transparent Pricing
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Choose the perfect plan for your practice. All plans include 14-day free trial.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Starter Plan -->
                <div class="bg-white rounded-3xl p-8 border-2 border-gray-200 hover:border-purple-300 transition-all duration-300">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Starter</h3>
                    <p class="text-gray-600 mb-6">Perfect for small clinics</p>
                    <div class="mb-6">
                        <span class="text-5xl font-extrabold text-gray-900">$99</span>
                        <span class="text-gray-600">/month</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span class="text-gray-700">Up to 100 patients</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span class="text-gray-700">1 dentist account</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span class="text-gray-700">Basic reporting</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span class="text-gray-700">Email support</span>
                        </li>
                    </ul>
                    <a href="{{ route('landing.contact') }}" class="block text-center py-3 px-6 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-xl transition-colors duration-200">
                        Start Free Trial
                    </a>
                </div>

                <!-- Professional Plan (Featured) -->
                <div class="bg-gradient-to-br from-purple-600 to-blue-600 rounded-3xl p-8 transform scale-105 shadow-2xl relative">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 bg-yellow-400 text-yellow-900 text-sm font-bold rounded-full">
                        MOST POPULAR
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Professional</h3>
                    <p class="text-purple-100 mb-6">For growing practices</p>
                    <div class="mb-6">
                        <span class="text-5xl font-extrabold text-white">$199</span>
                        <span class="text-purple-100">/month</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-white mr-3"></i>
                            <span class="text-white">Unlimited patients</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-white mr-3"></i>
                            <span class="text-white">Up to 5 dentists</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-white mr-3"></i>
                            <span class="text-white">Advanced analytics</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-white mr-3"></i>
                            <span class="text-white">Priority support</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-white mr-3"></i>
                            <span class="text-white">Custom branding</span>
                        </li>
                    </ul>
                    <a href="{{ route('landing.contact') }}" class="block text-center py-3 px-6 bg-white hover:bg-gray-100 text-purple-600 font-semibold rounded-xl transition-colors duration-200">
                        Start Free Trial
                    </a>
                </div>

                <!-- Enterprise Plan -->
                <div class="bg-white rounded-3xl p-8 border-2 border-gray-200 hover:border-purple-300 transition-all duration-300">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Enterprise</h3>
                    <p class="text-gray-600 mb-6">For large clinics</p>
                    <div class="mb-6">
                        <span class="text-5xl font-extrabold text-gray-900">$399</span>
                        <span class="text-gray-600">/month</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span class="text-gray-700">Unlimited everything</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span class="text-gray-700">Unlimited dentists</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span class="text-gray-700">API access</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span class="text-gray-700">24/7 phone support</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span class="text-gray-700">Dedicated account manager</span>
                        </li>
                    </ul>
                    <a href="{{ route('landing.contact') }}" class="block text-center py-3 px-6 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-xl transition-colors duration-200">
                        Contact Sales
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div id="testimonials" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <span class="inline-block px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold mb-4">TESTIMONIALS</span>
                <h2 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-6">
                    Loved by Dental Professionals
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    See what dental practices are saying about Dental Hub.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-3xl border border-gray-200 shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-6 leading-relaxed italic">"Dental Hub transformed how we manage our practice. Patient scheduling is effortless, and our no-show rate dropped by 70%!"</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                            DS
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">Dr. Sarah Martinez</div>
                            <div class="text-sm text-gray-600">Bright Smile Dental</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-3xl border border-gray-200 shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-6 leading-relaxed italic">"The digital dental charts are amazing! Everything is organized and accessible in seconds. Our team loves it."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-500 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                            JC
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">Dr. James Chen</div>
                            <div class="text-sm text-gray-600">City Dental Care</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-3xl border border-gray-200 shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-6 leading-relaxed italic">"Best investment we've made! The analytics help us understand our practice better and make data-driven decisions."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-red-500 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                            EP
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">Dr. Emily Parker</div>
                            <div class="text-sm text-gray-600">Family Dental Group</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Final CTA Section -->
    <div class="py-24 bg-gradient-to-br from-purple-600 via-blue-600 to-indigo-700 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS1vcGFjaXR5PSIwLjEiIHN0cm9rZS13aWR0aD0iMSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNncmlkKSIvPjwvc3ZnPg==')] opacity-20"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-6">
                Ready to Transform Your Practice?
            </h2>
            <p class="text-xl sm:text-2xl text-purple-100 mb-10 max-w-3xl mx-auto">
                Join 1000+ dental practices using Dental Hub to deliver exceptional patient care and grow their business.
            </p>
            <a href="{{ route('landing.contact') }}" class="inline-flex items-center px-10 py-5 bg-white hover:bg-gray-100 text-purple-600 text-xl font-bold rounded-2xl shadow-2xl hover:shadow-white/30 transform hover:-translate-y-1 transition-all duration-300">
                <i class="fas fa-rocket mr-3"></i>
                Start Your Free 14-Day Trial
                <i class="fas fa-arrow-right ml-3"></i>
            </a>
            <p class="mt-6 text-purple-100 text-sm">No credit card required • Setup in 5 minutes • Cancel anytime</p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <div>
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-tooth text-white text-lg"></i>
                        </div>
                        <span class="ml-3 text-xl font-bold text-white">Dental Hub</span>
                    </div>
                    <p class="text-sm leading-relaxed">The modern practice management platform trusted by dental professionals worldwide.</p>
                    <div class="flex space-x-4 mt-6">
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-purple-600 rounded-lg flex items-center justify-center transition-colors duration-200">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-purple-600 rounded-lg flex items-center justify-center transition-colors duration-200">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-purple-600 rounded-lg flex items-center justify-center transition-colors duration-200">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-purple-600 rounded-lg flex items-center justify-center transition-colors duration-200">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Product</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#features" class="hover:text-white transition-colors duration-200">Features</a></li>
                        <li><a href="#pricing" class="hover:text-white transition-colors duration-200">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Security</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Integrations</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Company</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors duration-200">About Us</a></li>
                        <li><a href="{{ route('landing.contact') }}" class="hover:text-white transition-colors duration-200">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Careers</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Legal</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-200">HIPAA Compliance</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Security</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} Dental Hub. All rights reserved. Built with <i class="fas fa-heart text-red-500"></i> for dental professionals.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-fadeInUp').forEach((el) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.8s ease-out, transform 0.8s ease-out';
            observer.observe(el);
        });
    </script>
</body>
</html>
