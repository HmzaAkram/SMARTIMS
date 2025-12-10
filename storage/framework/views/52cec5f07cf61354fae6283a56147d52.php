<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'SmartIMS - Super Admin'); ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js"></script>
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #7c3aed;
            --accent-color: #06b6d4;
        }
        
        .sidebar {
            transition: all 0.3s ease;
        }
        
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .dark-bg {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Mobile menu button -->
    <button id="mobile-menu-button" class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-md bg-indigo-600 text-white">
        <i class="fas fa-bars"></i>
    </button>

    <div class="flex h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar hidden lg:flex flex-col w-64 bg-gradient-to-b from-gray-900 to-gray-800 text-white">
            <!-- Logo -->
            <div class="p-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-cube text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">SmartIMS</h1>
                        <p class="text-xs text-gray-400">Super Admin</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 space-y-2">
                <a href="<?php echo e(route('admin.dashboard')); ?>" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition-all <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-gray-800 border-l-4 border-indigo-500' : ''); ?>">
                    <i class="fas fa-chart-line w-5"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="#" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition-all">
                    <i class="fas fa-building w-5"></i>
                    <span>Companies</span>
                    <span class="ml-auto bg-indigo-500 text-xs px-2 py-1 rounded-full"><?php echo e($totalCompanies ?? 0); ?></span>
                </a>
                
                <a href="#" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition-all">
                    <i class="fas fa-users w-5"></i>
                    <span>Users</span>
                    <span class="ml-auto bg-green-500 text-xs px-2 py-1 rounded-full"><?php echo e($totalUsers ?? 0); ?></span>
                </a>
                
                <a href="#" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition-all">
                    <i class="fas fa-credit-card w-5"></i>
                    <span>Subscriptions</span>
                </a>
                
                <a href="#" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition-all">
                    <i class="fas fa-cog w-5"></i>
                    <span>Settings</span>
                </a>
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                        <span class="font-bold"><?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?></span>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-xs text-gray-400">Super Admin</p>
                    </div>
                    <a href="<?php echo e(route('logout')); ?>" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="p-2 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
                        <?php echo csrf_field(); ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900"><?php echo $__env->yieldContent('title'); ?></h1>
                            <p class="text-gray-600 mt-1"><?php echo $__env->yieldContent('subtitle', 'Manage your multi-tenant platform'); ?></p>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="p-2 rounded-lg hover:bg-gray-100">
                                    <i class="fas fa-bell text-gray-600"></i>
                                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                                </button>
                                
                                <!-- Notification Dropdown -->
                                <div x-show="open" @click.away="open = false" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                                    <div class="p-4 border-b">
                                        <h3 class="font-bold">Notifications</h3>
                                    </div>
                                    <div class="max-h-96 overflow-y-auto">
                                        <!-- Notification items -->
                                        <div class="p-4 border-b hover:bg-gray-50">
                                            <p class="font-medium">New company registered</p>
                                            <p class="text-sm text-gray-600">ABC Corp registered 2 hours ago</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Quick Stats -->
                            <div class="hidden md:flex items-center space-x-6">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-indigo-600"><?php echo e($totalCompanies ?? 0); ?></p>
                                    <p class="text-xs text-gray-600">Companies</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-green-600"><?php echo e($totalUsers ?? 0); ?></p>
                                    <p class="text-xs text-gray-600">Users</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <?php echo $__env->yieldContent('content'); ?>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4 px-6">
                <div class="flex items-center justify-between">
                    <p class="text-gray-600 text-sm">
                        &copy; <?php echo e(date('Y')); ?> SmartIMS. All rights reserved.
                    </p>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">
                            Last updated: <?php echo e(now()->format('M d, Y h:i A')); ?>

                        </span>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                            <i class="fas fa-circle text-green-500 mr-1"></i>
                            System Online
                        </span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('hidden');
            document.getElementById('sidebar').classList.toggle('flex');
            document.getElementById('sidebar').classList.toggle('fixed');
            document.getElementById('sidebar').classList.toggle('z-40');
            document.getElementById('sidebar').classList.toggle('inset-0');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuButton = document.getElementById('mobile-menu-button');
            
            if (window.innerWidth < 1024 && 
                !sidebar.contains(event.target) && 
                !menuButton.contains(event.target) &&
                !sidebar.classList.contains('hidden')) {
                sidebar.classList.add('hidden');
            }
        });

        // Auto-refresh dashboard every 5 minutes
        setTimeout(() => {
            window.location.reload();
        }, 300000); // 5 minutes
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\Users\HP\Documents\GitHub\SMARTIMS\resources\views/layouts/super-admin.blade.php ENDPATH**/ ?>