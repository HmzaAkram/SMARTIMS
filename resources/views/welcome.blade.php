<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartIMS - SaaS Inventory Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 min-h-screen flex items-center justify-center font-inter">
    <div class="max-w-4xl mx-auto text-center p-8">
        <h1 class="text-5xl md:text-6xl font-bold text-gray-900 dark:text-white mb-6">
            SmartIMS
        </h1>
        <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">
            Cloud-based Inventory Management System for modern businesses.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/register-company" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                Start Free Trial
            </a>
            <a href="/login" class="px-8 py-3 bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 border border-blue-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition font-semibold">
                Login
            </a>
        </div>
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
                <h3 class="font-bold text-lg mb-2">Real-time Tracking</h3>
                <p class="text-gray-600 dark:text-gray-400">Monitor stock levels across multiple warehouses instantly.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
                <h3 class="font-bold text-lg mb-2">AI Insights</h3>
                <p class="text-gray-600 dark:text-gray-400">Get predictive restock alerts and sales forecasts.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
                <h3 class="font-bold text-lg mb-2">Multi-Tenant SaaS</h3>
                <p class="text-gray-600 dark:text-gray-400">Each company gets isolated workspace with custom branding.</p>
            </div>
        </div>
    </div>
</body>
</html>