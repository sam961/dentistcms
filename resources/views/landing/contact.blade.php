<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Us - Dental Hub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="antialiased bg-gray-50">

    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-md border-b border-gray-100 fixed w-full top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="{{ route('landing.index') }}" class="flex-shrink-0 flex items-center space-x-3">
                        <div class="w-12 h-12 gradient-bg rounded-2xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-tooth text-white text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">Dental Hub</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('landing.index') }}" class="text-gray-700 hover:text-purple-600 font-medium transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="pt-32 pb-24 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-16">
                <h1 class="text-5xl font-extrabold text-gray-900 mb-4">Get in Touch</h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Have questions about Dental Hub? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
                </p>
            </div>

            <div class="max-w-4xl mx-auto">
                <!-- Contact Form -->
                <div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 lg:p-12">
                        @if(session('success'))
                            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-green-400 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('landing.contact.submit') }}" method="POST" class="space-y-6">
                            @csrf

                            <div class="grid md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Your Name <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        value="{{ old('name') }}"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror"
                                        placeholder="John Doe"
                                    >
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        value="{{ old('email') }}"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                                        placeholder="john@example.com"
                                    >
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Phone (Optional) -->
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Phone Number <span class="text-gray-400 text-xs">(Optional)</span>
                                </label>
                                <input
                                    type="tel"
                                    name="phone"
                                    id="phone"
                                    value="{{ old('phone') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 @error('phone') border-red-500 @enderror"
                                    placeholder="+1 (555) 000-0000"
                                >
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Subject -->
                            <div>
                                <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Subject <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="subject"
                                    id="subject"
                                    value="{{ old('subject') }}"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 @error('subject') border-red-500 @enderror"
                                    placeholder="How can we help you?"
                                >
                                @error('subject')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Message -->
                            <div>
                                <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Message <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    name="message"
                                    id="message"
                                    rows="6"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 resize-none @error('message') border-red-500 @enderror"
                                    placeholder="Tell us more about your inquiry..."
                                >{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-between pt-4">
                                <p class="text-sm text-gray-500">
                                    <span class="text-red-500">*</span> Required fields
                                </p>
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                                >
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm">&copy; {{ date('Y') }} Dental Hub. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
