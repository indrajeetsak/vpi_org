<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - VPI Admin</title>
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?= $this->renderSection('styles') ?>
</head>
<body class="bg-gray-100 font-sans">
    <div class="min-h-screen relative overflow-x-hidden">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white w-64 space-y-6 py-7 px-2 fixed inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition duration-200 ease-in-out z-20 h-full">
            <a href="<?= site_url('admin/dashboard') ?>" class="text-white flex items-center space-x-2 px-4">
                <span class="text-2xl font-extrabold">VPI Admin</span>
            </a>

            <nav>
                <a href="<?= site_url('admin/dashboard') ?>" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 <?= (service('router')->controllerName() == 'App\Controllers\Admin' && service('router')->methodName() == 'index') ? 'bg-blue-600' : '' ?>">
                    <i class="fas fa-home mr-3"></i>Dashboard
                </a>
                <a href="<?= site_url('admin/usersRequest') ?>" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 <?= (service('router')->controllerName() == 'App\Controllers\Admin' && in_array(service('router')->methodName(), ['applications', 'usersRequest'])) ? 'bg-blue-600' : '' ?>">
                    <i class="fas fa-user-clock mr-3"></i>Applications
                </a>
                <a href="<?= site_url('admin/usersList') ?>" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 <?= (service('router')->controllerName() == 'App\Controllers\Admin' && in_array(service('router')->methodName(), ['users', 'usersList'])) ? 'bg-blue-600' : '' ?>">
                    <i class="fas fa-users mr-3"></i>Office Bearers
                </a>


                <a href="<?= site_url('admin/manage-location') ?>" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 <?= (service('router')->controllerName() == 'App\Controllers\Admin' && service('router')->methodName() == 'manageLocation') ? 'bg-blue-600' : '' ?>">
                    <i class="fas fa-map-marked-alt mr-3"></i>Manage Location
                </a>
                <a href="<?= site_url('admin/manage-constituencies') ?>" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 <?= (service('router')->controllerName() == 'App\Controllers\Admin' && service('router')->methodName() == 'manageConstituencies') ? 'bg-blue-600' : '' ?>">
                    <i class="fas fa-landmark mr-3"></i>Manage Constituencies
                </a>
                <?php if (session('admin_type') == 1): ?>
                    <a href="<?= site_url('admin/manage-admins') ?>" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 <?= (service('router')->controllerName() == 'App\Controllers\Admin' && in_array(service('router')->methodName(), ['manageAdmins', 'createAdmin', 'editAdmin'])) ? 'bg-blue-600' : '' ?>">
                        <i class="fas fa-user-shield mr-3"></i>Manage Admins
                    </a>
                <?php endif; ?>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col md:ml-64 min-h-screen max-w-full">
            <!-- Header -->
            <header class="bg-white shadow-sm sticky top-0 z-10">
                <div class="flex justify-between items-center py-4 px-6">
                    <div class="flex items-center">
                        <button id="sidebarToggle" class="md:hidden mr-4 text-gray-500 focus:outline-none">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-700"><?= $this->renderSection('headerTitle') ?></h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">Welcome, Admin!</span>
                        <a href="<?= site_url('admin/logout') ?>" class="text-gray-700 hover:text-gray-900">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-grow p-6 bg-gray-100">
                <?= $this->renderSection('content') ?>
            </main>

            <!-- Footer -->
            <footer class="bg-white py-4 px-6 border-t mt-auto">
                <div class="text-center text-gray-500 text-sm">
                    &copy; <?= date('Y') ?> VPI Office Bearers. All rights reserved.
                </div>
            </footer>
        </div>
    </div>

    <!-- Mobile sidebar overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-10 hidden md:hidden"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.bg-gray-800');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const sidebarToggle = document.getElementById('sidebarToggle');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('hidden');
            });

            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            });
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>