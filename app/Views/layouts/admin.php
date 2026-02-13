<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen">
    <div class="flex">
        <!-- Admin Sidebar -->
        <div class="w-64 bg-gray-900 min-h-screen p-4">
            <nav class="space-y-2">
                <div class="px-4 py-3 text-white">
                    <h2 class="text-lg font-semibold">Admin Panel</h2>
                    <p class="text-sm text-gray-400">Manage VPI Office Bearers</p>
                </div>
                <a href="<?= base_url('admin') ?>" 
                   class="block px-4 py-2 text-white rounded-md <?= service('request')->uri->getSegment(2) === null ? 'bg-blue-600' : 'hover:bg-gray-800' ?>">Dashboard</a>
                <a href="<?= base_url('admin/users') ?>" 
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-800 rounded-md <?= service('request')->uri->getSegment(2) === 'users' ? 'bg-blue-600' : '' ?>">Users</a>
                <a href="<?= base_url('admin/posts') ?>" 
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-800 rounded-md <?= service('request')->uri->getSegment(2) === 'posts' ? 'bg-blue-600' : '' ?>">Posts</a>
                <a href="<?= base_url('admin/organs') ?>" 
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-800 rounded-md <?= service('request')->uri->getSegment(2) === 'organs' ? 'bg-blue-600' : '' ?>">Organs</a>
                <a href="<?= base_url('admin/levels') ?>" 
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-800 rounded-md <?= service('request')->uri->getSegment(2) === 'levels' ? 'bg-blue-600' : '' ?>">Levels</a>
                <a href="<?= base_url('admin/usersList') ?>"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-800 rounded-md <?= service('request')->uri->getSegment(2) === 'usersList' ? 'bg-blue-600' : '' ?>">Manage Listing</a>
                <?php if (session('admin_type') == 1): ?>
                    <a href="<?= base_url('admin/manage-admins') ?>"
                       class="block px-4 py-2 text-gray-300 hover:bg-gray-800 rounded-md <?= service('request')->uri->getSegment(2) === 'manage-admins' ? 'bg-blue-600' : '' ?>">Manage Admins</a>
                <?php endif; ?>
                <a href="<?= base_url('auth/logout') ?>"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-800 rounded-md">Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <?= $this->renderSection('page_content') ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
