<!DOCTYPE html>
<html lang="en">
<head>    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - VPI Office Bearer</title>
    <script defer src="https://unpkg.com/alpinejs@3.12.3/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
    <?= $this->renderSection('scripts') ?>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">VPI Office Bearer</h1>
                <?php if (session()->get('isLoggedIn')): ?>
                    <nav>
                        <a href="<?= base_url('dashboard') ?>" class="text-white hover:text-blue-200 px-3 py-2">Dashboard</a>
                        <a href="<?= base_url('auth/logout') ?>" class="text-white hover:text-blue-200 px-3 py-2">Logout</a>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-auto">
        <div class="container mx-auto px-4 py-6">
            <p class="text-center">&copy; <?= date('Y') ?> VPI Office Bearer. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
