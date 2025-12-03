<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - SmartIMS</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-indigo-100 via-white to-purple-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-2xl">
            <!-- Logo -->
            <div class="flex justify-center">
                <svg class="h-12 w-12 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                Create your company account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                    Sign in
                </a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl">
            <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow-xl sm:rounded-lg sm:px-10">
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-4 rounded-md bg-red-50 dark:bg-red-900/20 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                    There were errors with your submission
                                </h3>
                                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                    <ul class="list-disc space-y-1 pl-5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form class="space-y-6" method="POST" action="{{ route('register-company.post') }}">
                    @csrf

                    <!-- Company Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Company Information</h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Company Name -->
                            <div class="sm:col-span-2">
                                <label for="company_name" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    Company Name *
                                </label>
                                <div class="mt-2">
                                    <input id="company_name" name="company_name" type="text" required value="{{ old('company_name') }}" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                </div>
                            </div>

                            <!-- Company Email -->
                            <div>
                                <label for="company_email" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    Company Email *
                                </label>
                                <div class="mt-2">
                                    <input id="company_email" name="company_email" type="email" required value="{{ old('company_email') }}" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                </div>
                            </div>

                            <!-- Company Phone -->
                            <div>
                                <label for="company_phone" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    Company Phone *
                                </label>
                                <div class="mt-2">
                                    <input id="company_phone" name="company_phone" type="tel" required value="{{ old('company_phone') }}" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                </div>
                            </div>

                            <!-- Subdomain -->
                            <div class="sm:col-span-2">
                                <label for="subdomain" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    Choose Your Subdomain *
                                </label>
                                <div class="mt-2">
                                    <div class="flex rounded-md shadow-sm">
                                        <input type="text" name="subdomain" id="subdomain" required value="{{ old('subdomain') }}" class="block w-full min-w-0 flex-1 rounded-l-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                        <span class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-3 text-gray-500 dark:text-gray-400 sm:text-sm">.smartims.com</span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Only lowercase letters, numbers, and hyphens allowed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Admin Account</h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    Full Name *
                                </label>
                                <div class="mt-2">
                                    <input id="name" name="name" type="text" required value="{{ old('name') }}" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                </div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    Email Address *
                                </label>
                                <div class="mt-2">
                                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                </div>
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    Password *
                                </label>
                                <div class="mt-2">
                                    <input id="password" name="password" type="password" required class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                </div>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Must be at least 8 characters</p>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    Confirm Password *
                                </label>
                                <div class="mt-2">
                                    <input id="password_confirmation" name="password_confirmation" type="password" required class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-white dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subscription Plan -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Choose Your Plan</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            @foreach(['free' => 'Free', 'standard' => 'Standard', 'premium' => 'Premium'] as $value => $label)
                            <div>
                                <input type="radio" name="plan" id="plan_{{ $value }}" value="{{ $value }}" {{ old('plan', 'free') == $value ? 'checked' : '' }} class="peer hidden">
                                <label for="plan_{{ $value }}" class="block cursor-pointer rounded-lg border-2 border-gray-300 dark:border-gray-600 p-4 hover:border-indigo-500 peer-checked:border-indigo-600 peer-checked:ring-2 peer-checked:ring-indigo-600">
                                    <div class="text-center">
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $label }}</p>
                                        <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">
                                            @if($value === 'free')
                                                Free
                                            @elseif($value === 'standard')
                                                $29/mo
                                            @else
                                                $79/mo
                                            @endif
                                        </p>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="flex items-start">
                        <div class="flex h-5 items-center">
                            <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="text-gray-700 dark:text-gray-300">
                                I agree to the <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Terms and Conditions</a> and <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Privacy Policy</a>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>