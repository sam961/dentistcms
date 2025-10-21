@php
function getFeatureImages($featureName) {
    $images = [];
    $basePath = public_path('images/features/');

    // Check for single image
    if (file_exists($basePath . $featureName . '.png')) {
        $images[] = asset('images/features/' . $featureName . '.png');
    }

    // Check for numbered images (feature-name-1.png, feature-name-2.png, etc.)
    $index = 1;
    while (file_exists($basePath . $featureName . '-' . $index . '.png')) {
        $images[] = asset('images/features/' . $featureName . '-' . $index . '.png');
        $index++;
    }

    return $images;
}
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dental Hub - Modern Dental Practice Management Software</title>
    <meta name="description" content="Transform your dental practice with Dental Hub. All-in-one practice management software designed for modern dental clinics.">
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
<body class="antialiased bg-white" x-data="{ lightbox: false, lightboxImages: [], lightboxTitle: '', currentImageIndex: 0 }">

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
                    <a href="#contact" class="text-gray-700 hover:text-purple-600 font-medium transition-colors duration-200">Contact</a>
                    @guest
                        <a href="#demo" class="text-gray-700 hover:text-purple-600 font-medium transition-colors duration-200">Request Demo</a>
                    @endguest
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        @if(Auth::user()->isSuperAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                Dashboard
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-purple-600 font-medium transition-colors duration-200">
                            Sign In
                        </a>
                        <a href="{{ route('landing.contact') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fas fa-envelope mr-2"></i>
                            Get Started
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
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-gray-900 tracking-tight mb-6">
                    Modern Practice <br>
                    <span class="gradient-text">Management System</span>
                </h1>

                <p class="mt-6 max-w-3xl mx-auto text-xl sm:text-2xl text-gray-600 leading-relaxed">
                    A comprehensive platform designed to help dental clinics streamline operations, enhance patient care, and improve efficiency.
                </p>

                <div class="mt-12 flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('landing.contact') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white text-lg font-semibold rounded-2xl shadow-2xl hover:shadow-purple-500/50 transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-envelope mr-3"></i>
                        Contact Us
                        <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="#features" class="inline-flex items-center justify-center px-8 py-4 bg-white hover:bg-gray-50 text-gray-900 text-lg font-semibold rounded-2xl shadow-lg border-2 border-gray-200 hover:border-purple-300 transition-all duration-300">
                        <i class="fas fa-info-circle mr-3"></i>
                        Learn More
                    </a>
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
                    <span class="text-sm font-semibold text-gray-700">Secure</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-lock text-2xl text-purple-600"></i>
                    <span class="text-sm font-semibold text-gray-700">Encrypted</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-cloud text-2xl text-purple-600"></i>
                    <span class="text-sm font-semibold text-gray-700">Cloud-Based</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-mobile-alt text-2xl text-purple-600"></i>
                    <span class="text-sm font-semibold text-gray-700">Responsive</span>
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
                    Complete Practice Management
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Everything you need to run a modern dental practice efficiently.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- NEW ACCURATE FEATURES START -->
                                <!-- Feature Card 1: Patient Management -->
                <div class="group bg-white rounded-3xl border border-gray-200 hover:border-purple-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                    <div class="p-6 lg:p-8">
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-users text-white text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Patient Management</h3>
                        </div>
                        <p class="text-gray-600 leading-relaxed mb-6">Comprehensive patient profiles with demographics, contact information, medical history, and nationality tracking with international phone codes.</p>

                        @php
                            $patientImages = getFeatureImages('patients');
                        @endphp
                        @if(count($patientImages) > 0)
                            <button @click="lightbox = true; lightboxImages = {{ json_encode($patientImages) }}; lightboxTitle = 'Patient Management'; currentImageIndex = 0"
                                    class="w-full px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-xl transition-all duration-300 flex items-center justify-center group/btn shadow-lg hover:shadow-xl">
                                <i class="fas fa-eye mr-2 group-hover/btn:scale-110 transition-transform"></i>
                                Click to Preview {{ count($patientImages) > 1 ? count($patientImages) . ' Screenshots' : 'Screenshot' }}
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Feature Card 2: Smart Appointment Scheduling -->
                <div class="group bg-white rounded-3xl border border-gray-200 hover:border-purple-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                    <div class="p-6 lg:p-8">
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-calendar-check text-white text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Smart Appointment Scheduling</h3>
                        </div>
                        <p class="text-gray-600 leading-relaxed mb-6">Real-time availability checking, conflict prevention, duration-based slot management, and calendar view with easy rescheduling.</p>

                        @php
                            $appointmentsImages = getFeatureImages('appointments');
                        @endphp
                        @if(count($appointmentsImages) > 0)
                            <button @click="lightbox = true; lightboxImages = {{ json_encode($appointmentsImages) }}; lightboxTitle = 'Smart Appointment Scheduling'; currentImageIndex = 0"
                                    class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-xl transition-all duration-300 flex items-center justify-center group/btn shadow-lg hover:shadow-xl">
                                <i class="fas fa-eye mr-2 group-hover/btn:scale-110 transition-transform"></i>
                                Click to Preview {{ count($appointmentsImages) > 1 ? count($appointmentsImages) . ' Screenshots' : 'Screenshot' }}
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Feature Card 3: Dentist & Staff Management -->
                <div class="group bg-white rounded-3xl border border-gray-200 hover:border-purple-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                    <div class="p-6 lg:p-8">
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-user-md text-white text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Dentist & Staff Management</h3>
                        </div>
                        <p class="text-gray-600 leading-relaxed mb-6">Manage dentist profiles, specializations, licenses, schedules, and track individual performance across your practice.</p>

                        @php
                            $dentistsImages = getFeatureImages('dentists');
                        @endphp
                        @if(count($dentistsImages) > 0)
                            <button @click="lightbox = true; lightboxImages = {{ json_encode($dentistsImages) }}; lightboxTitle = 'Dentist & Staff Management'; currentImageIndex = 0"
                                    class="w-full px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-semibold rounded-xl transition-all duration-300 flex items-center justify-center group/btn shadow-lg hover:shadow-xl">
                                <i class="fas fa-eye mr-2 group-hover/btn:scale-110 transition-transform"></i>
                                Click to Preview {{ count($dentistsImages) > 1 ? count($dentistsImages) . ' Screenshots' : 'Screenshot' }}
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Feature Card 4: Treatment Plans & Procedures -->
                <div class="group bg-white rounded-3xl border border-gray-200 hover:border-purple-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                    <div class="p-6 lg:p-8">
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-procedures text-white text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Treatment Plans & Procedures</h3>
                        </div>
                        <p class="text-gray-600 leading-relaxed mb-6">Create detailed treatment plans with multiple procedures, pricing, duration tracking, and status management from planned to completed.</p>

                        @php
                            $treatmentPlansImages = getFeatureImages('treatment-plans');
                        @endphp
                        @if(count($treatmentPlansImages) > 0)
                            <button @click="lightbox = true; lightboxImages = {{ json_encode($treatmentPlansImages) }}; lightboxTitle = 'Treatment Plans & Procedures'; currentImageIndex = 0"
                                    class="w-full px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-xl transition-all duration-300 flex items-center justify-center group/btn shadow-lg hover:shadow-xl">
                                <i class="fas fa-eye mr-2 group-hover/btn:scale-110 transition-transform"></i>
                                Click to Preview {{ count($treatmentPlansImages) > 1 ? count($treatmentPlansImages) . ' Screenshots' : 'Screenshot' }}
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Feature Card 5: Digital Dental Charts -->
                <div class="group bg-white rounded-3xl border border-gray-200 hover:border-purple-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                    <div class="p-6 lg:p-8">
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-tooth text-white text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Digital Dental Charts</h3>
                        </div>
                        <p class="text-gray-600 leading-relaxed mb-6">Interactive dental charts with tooth-by-tooth tracking, periodontal charting, dental imaging storage, and comprehensive tooth records.</p>

                        @php
                            $dentalChartImages = getFeatureImages('dental-chart');
                        @endphp
                        @if(count($dentalChartImages) > 0)
                            <button @click="lightbox = true; lightboxImages = {{ json_encode($dentalChartImages) }}; lightboxTitle = 'Digital Dental Charts'; currentImageIndex = 0"
                                    class="w-full px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-semibold rounded-xl transition-all duration-300 flex items-center justify-center group/btn shadow-lg hover:shadow-xl">
                                <i class="fas fa-eye mr-2 group-hover/btn:scale-110 transition-transform"></i>
                                Click to Preview {{ count($dentalChartImages) > 1 ? count($dentalChartImages) . ' Screenshots' : 'Screenshot' }}
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Feature Card 6: Invoicing & Billing -->
                <div class="group bg-white rounded-3xl border border-gray-200 hover:border-purple-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                    <div class="p-6 lg:p-8">
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-file-invoice-dollar text-white text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Invoicing & Billing</h3>
                        </div>
                        <p class="text-gray-600 leading-relaxed mb-6">Professional invoicing with multi-item support, payment tracking, partial payments, status management, and financial reporting.</p>

                        @php
                            $invoicesImages = getFeatureImages('invoices');
                        @endphp
                        @if(count($invoicesImages) > 0)
                            <button @click="lightbox = true; lightboxImages = {{ json_encode($invoicesImages) }}; lightboxTitle = 'Invoicing & Billing'; currentImageIndex = 0"
                                    class="w-full px-6 py-3 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all duration-300 flex items-center justify-center group/btn shadow-lg hover:shadow-xl">
                                <i class="fas fa-eye mr-2 group-hover/btn:scale-110 transition-transform"></i>
                                Click to Preview {{ count($invoicesImages) > 1 ? count($invoicesImages) . ' Screenshots' : 'Screenshot' }}
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Feature Card 7: Medical Records & History -->
                <div class="group bg-white rounded-3xl border border-gray-200 hover:border-purple-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                    <div class="p-6 lg:p-8">
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-pink-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-notes-medical text-white text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Medical Records & History</h3>
                        </div>
                        <p class="text-gray-600 leading-relaxed mb-6">Complete medical history tracking with allergies, medications, conditions, and detailed notes for each patient visit.</p>

                        @php
                            $medicalRecordsImages = getFeatureImages('medical-records');
                        @endphp
                        @if(count($medicalRecordsImages) > 0)
                            <button @click="lightbox = true; lightboxImages = {{ json_encode($medicalRecordsImages) }}; lightboxTitle = 'Medical Records & History'; currentImageIndex = 0"
                                    class="w-full px-6 py-3 bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white font-semibold rounded-xl transition-all duration-300 flex items-center justify-center group/btn shadow-lg hover:shadow-xl">
                                <i class="fas fa-eye mr-2 group-hover/btn:scale-110 transition-transform"></i>
                                Click to Preview {{ count($medicalRecordsImages) > 1 ? count($medicalRecordsImages) . ' Screenshots' : 'Screenshot' }}
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Feature Card 8: Reports & Analytics Dashboard -->
                <div class="group bg-white rounded-3xl border border-gray-200 hover:border-purple-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                    <div class="p-6 lg:p-8">
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-chart-line text-white text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Reports & Analytics Dashboard</h3>
                        </div>
                        <p class="text-gray-600 leading-relaxed mb-6">Visual analytics with interactive charts, appointment trends, revenue tracking, patient demographics, and treatment statistics.</p>

                        @php
                            $dashboardImages = getFeatureImages('dashboard');
                        @endphp
                        @if(count($dashboardImages) > 0)
                            <button @click="lightbox = true; lightboxImages = {{ json_encode($dashboardImages) }}; lightboxTitle = 'Reports & Analytics Dashboard'; currentImageIndex = 0"
                                    class="w-full px-6 py-3 bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-semibold rounded-xl transition-all duration-300 flex items-center justify-center group/btn shadow-lg hover:shadow-xl">
                                <i class="fas fa-eye mr-2 group-hover/btn:scale-110 transition-transform"></i>
                                Click to Preview {{ count($dashboardImages) > 1 ? count($dashboardImages) . ' Screenshots' : 'Screenshot' }}
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Feature Card 9: Notifications & Reminders -->
                <div class="group bg-white rounded-3xl border border-gray-200 hover:border-purple-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                    <div class="p-6 lg:p-8">
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-bell text-white text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Notifications & Reminders</h3>
                        </div>
                        <p class="text-gray-600 leading-relaxed mb-6">Real-time notification system for appointments, updates, and important events with unread counter and notification center.</p>

                        @php
                            $notificationsImages = getFeatureImages('notifications');
                        @endphp
                        @if(count($notificationsImages) > 0)
                            <button @click="lightbox = true; lightboxImages = {{ json_encode($notificationsImages) }}; lightboxTitle = 'Notifications & Reminders'; currentImageIndex = 0"
                                    class="w-full px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold rounded-xl transition-all duration-300 flex items-center justify-center group/btn shadow-lg hover:shadow-xl">
                                <i class="fas fa-eye mr-2 group-hover/btn:scale-110 transition-transform"></i>
                                Click to Preview {{ count($notificationsImages) > 1 ? count($notificationsImages) . ' Screenshots' : 'Screenshot' }}
                            </button>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Final CTA Section -->
    <!-- Request Demo Section -->
    <div id="demo" class="py-24 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-lg mb-6">
                    <i class="fas fa-rocket text-white text-3xl"></i>
                </div>
                <h2 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-6">
                    Request a Demo Account
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Contact us to get your personalized demo account and explore all features
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-6 max-w-3xl mx-auto mb-12">
                <!-- WhatsApp Button -->
                <a href="https://wa.me/96171231845?text=Hello!%20I%20would%20like%20to%20request%20a%20demo%20account%20for%20the%20Dental%20CMS."
                   target="_blank"
                   class="group flex items-center justify-center px-8 py-6 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <i class="fab fa-whatsapp text-3xl mr-4 group-hover:scale-110 transition-transform"></i>
                    <div class="text-left">
                        <div class="text-lg font-bold">Contact via WhatsApp</div>
                        <div class="text-sm text-green-100">+961 71 231 845</div>
                    </div>
                </a>

                <!-- Phone Button -->
                <a href="tel:+96171231845"
                   class="group flex items-center justify-center px-8 py-6 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <i class="fas fa-phone-alt text-2xl mr-4 group-hover:rotate-12 transition-transform"></i>
                    <div class="text-left">
                        <div class="text-lg font-bold">Call Us</div>
                        <div class="text-sm text-purple-100">+961 71 231 845</div>
                    </div>
                </a>
            </div>

            <!-- Info Box -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 border-2 border-blue-200 rounded-2xl p-8 max-w-3xl mx-auto">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 text-2xl mr-4 mt-1"></i>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">What You'll Get:</h3>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span class="text-gray-700">Fully configured demo account</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span class="text-gray-700">Sample data to explore</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span class="text-gray-700">Personalized walkthrough</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span class="text-gray-700">No credit card required</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="contact" class="py-24 bg-gradient-to-br from-purple-600 via-blue-600 to-indigo-700 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS1vcGFjaXR5PSIwLjEiIHN0cm9rZS13aWR0aD0iMSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNncmlkKSIvPjwvc3ZnPg==')] opacity-20"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-6">
                Ready to Modernize Your Practice?
            </h2>
            <p class="text-xl sm:text-2xl text-purple-100 mb-10 max-w-3xl mx-auto">
                Get in touch to learn more about how Dental Hub can help streamline your dental practice operations.
            </p>
            <a href="{{ route('landing.contact') }}" class="inline-flex items-center px-10 py-5 bg-white hover:bg-gray-100 text-purple-600 text-xl font-bold rounded-2xl shadow-2xl hover:shadow-white/30 transform hover:-translate-y-1 transition-all duration-300">
                <i class="fas fa-envelope mr-3"></i>
                Contact Us
                <i class="fas fa-arrow-right ml-3"></i>
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-12 mb-12">
                <div>
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-tooth text-white text-lg"></i>
                        </div>
                        <span class="ml-3 text-xl font-bold text-white">Dental Hub</span>
                    </div>
                    <p class="text-sm leading-relaxed">Modern practice management platform for dental professionals.</p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Product</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#features" class="hover:text-white transition-colors duration-200">Features</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors duration-200">Sign In</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Contact</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('landing.contact') }}" class="hover:text-white transition-colors duration-200">Get in Touch</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} Dental Hub. All rights reserved.</p>
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

    <!-- Lightbox Modal -->
    <div x-show="lightbox"
         x-cloak
         @click.away="lightbox = false"
         @keydown.escape.window="lightbox = false"
         @keydown.arrow-left.window="currentImageIndex = Math.max(0, currentImageIndex - 1)"
         @keydown.arrow-right.window="currentImageIndex = Math.min(lightboxImages.length - 1, currentImageIndex + 1)"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <!-- Close Button -->
        <button @click="lightbox = false"
                class="absolute top-4 right-4 w-12 h-12 flex items-center justify-center bg-white/10 hover:bg-white/20 rounded-full text-white transition-all duration-200 backdrop-blur-sm z-10">
            <i class="fas fa-times text-2xl"></i>
        </button>

        <!-- Image Title & Counter -->
        <div class="absolute top-4 left-4 bg-white/10 backdrop-blur-sm px-6 py-3 rounded-full z-10">
            <h3 class="text-white font-bold text-lg">
                <span x-text="lightboxTitle"></span>
                <span x-show="lightboxImages.length > 1" class="ml-2 text-sm font-normal">
                    (<span x-text="currentImageIndex + 1"></span>/<span x-text="lightboxImages.length"></span>)
                </span>
            </h3>
        </div>

        <!-- Previous Button -->
        <button x-show="lightboxImages.length > 1 && currentImageIndex > 0"
                @click="currentImageIndex--"
                class="absolute left-4 top-1/2 transform -translate-y-1/2 w-12 h-12 flex items-center justify-center bg-white/10 hover:bg-white/20 rounded-full text-white transition-all duration-200 backdrop-blur-sm z-10">
            <i class="fas fa-chevron-left text-xl"></i>
        </button>

        <!-- Next Button -->
        <button x-show="lightboxImages.length > 1 && currentImageIndex < lightboxImages.length - 1"
                @click="currentImageIndex++"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 w-12 h-12 flex items-center justify-center bg-white/10 hover:bg-white/20 rounded-full text-white transition-all duration-200 backdrop-blur-sm z-10">
            <i class="fas fa-chevron-right text-xl"></i>
        </button>

        <!-- Image Container -->
        <div class="relative max-w-7xl max-h-[90vh] w-full"
             @click.stop
             x-transition:enter="transition ease-out duration-300 delay-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <img :src="lightboxImages[currentImageIndex]"
                 :alt="lightboxTitle"
                 class="w-full h-full object-contain rounded-2xl shadow-2xl"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100">
        </div>

        <!-- Hint Text -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-white/10 backdrop-blur-sm px-6 py-2 rounded-full">
            <p class="text-white text-sm">
                <kbd class="px-2 py-1 bg-white/20 rounded">ESC</kbd> to close
                <span x-show="lightboxImages.length > 1"> • <kbd class="px-2 py-1 bg-white/20 rounded">←</kbd> <kbd class="px-2 py-1 bg-white/20 rounded">→</kbd> to navigate</span>
            </p>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>
