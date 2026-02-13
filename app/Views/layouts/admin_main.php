<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - VPI Admin</title>
    <!-- Assuming you have Tailwind CSS setup, link your compiled CSS -->
    <link href="<?= base_url('css/styles.css') ?>" rel="stylesheet"> 
    <?= $this->renderSection('styles') ?>
</head>
<body class="bg-gray-100 font-sans">

    <div id="app" class="flex flex-col min-h-screen">
        <nav class="bg-blue-700 p-4 text-white shadow-md">
            <div class="container mx-auto flex justify-between items-center">
                <a href="<?= site_url('admin/dashboard') ?>" class="text-xl font-semibold hover:text-blue-200">VPI Admin Panel</a>
                <div>
                    <a href="<?= site_url('admin/usersRequest') ?>" class="px-4 hover:text-blue-200">Applications</a>
                    <!-- Add other admin links here -->
                    <?php if(session()->get('isAdminLoggedIn')): // Check if admin is logged in ?>
                        <a href="<?= site_url('admin/logout') ?>" class="px-4 hover:text-blue-200">Logout</a>
                    <?php else: ?>
                        <a href="<?= site_url('admin/login') ?>" class="px-4 hover:text-blue-200">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <main class="flex-grow container mx-auto py-8 px-4">
            <?= $this->renderSection('content') ?>
        </main>

        <footer class="bg-gray-800 text-white text-center p-4 mt-auto">
            &copy; <?= date('Y') ?> VPI Office Bearers. All rights reserved.
        </footer>
    </div>
    <?= $this->renderSection('scripts') ?>
</body>
</html>