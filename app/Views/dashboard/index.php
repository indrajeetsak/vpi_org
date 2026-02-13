<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 min-h-screen p-4">
            <nav class="space-y-2">
                <a href="<?= base_url('dashboard') ?>" 
                   class="block px-4 py-2 text-white bg-blue-600 rounded-md">Dashboard</a>
                <a href="<?= base_url('dashboard/profile') ?>" 
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">My Profile</a>
                <a href="<?= base_url('dashboard/id-card') ?>" 
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">ID Card</a>
                <a href="<?= base_url('dashboard/visiting-card') ?>" 
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">Visiting Card</a>
                <a href="<?= base_url('dashboard/letter-head') ?>" 
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">Letter Head</a>
            <a href="<?= base_url('auth/logout') ?>"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <div class="container px-4 p-8">
                <h1 class="text-2xl font-bold mb-8">Welcome, <?= esc($user['first_name'] . ' ' . $user['last_name']) ?>!</h1>
                
                <!-- Profile Summary Card -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <div class="flex items-start space-x-6">
                        <img src="<?= base_url('uploads/photos/' . esc($user['photo'])) ?>" 
                             alt="Profile Photo"
                             class="w-24 h-24 rounded-full object-cover">
                        <div>
                            <h2 class="text-xl font-semibold"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></h2>
                            <p class="text-gray-600"><?= esc($post_name ?? 'N/A') ?></p>
                            <p class="text-gray-600">
                                <?= esc($organ_name ?? 'N/A') ?>
                                <?php if (!empty($organ_name) && $organ_name !== 'Not Assigned' && !empty($level_name) && $level_name !== 'Not Assigned'): ?>
                                    , <!-- Add comma only if both organ and level are present and not default -->
                                <?php endif; ?>
                                <?php if (!empty($level_name) && $level_name !== 'Not Assigned'): // Display level name only if it's not the default 'Not Assigned'
                                    echo esc($level_name);
                                endif; ?>
                            </p>
                            <a href="<?= base_url('dashboard/profile') ?>" 
                               class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                View/Edit Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Grid -->
                <div class="grid grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4">Your ID Card</h3>
                        <div class="bg-gray-100 h-32 rounded-md mb-4"></div>
                        <a href="<?= base_url('dashboard/id-card') ?>" 
                           class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            View
                        </a>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4">Visiting Card</h3>
                        <div class="bg-gray-100 h-32 rounded-md mb-4"></div>
                        <a href="<?= base_url('dashboard/visiting-card') ?>" 
                           class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Create
                        </a>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4">Letter Head</h3>
                        <div class="bg-gray-100 h-32 rounded-md mb-4"></div>
                        <a href="<?= base_url('dashboard/letter-head') ?>" 
                           class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Create
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>