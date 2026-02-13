<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Admin Dashboard<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
<div class="flex justify-between items-center mb-8">
    <h1 class="text-2xl font-bold">Admin Dashboard</h1>
    <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm">
        Admin
    </span>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-500 text-sm">Total Users</h3>
            <span class="text-blue-500 bg-blue-100 rounded-full p-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                </svg>
            </span>
        </div>
        <div class="flex items-baseline">
            <h1 class="text-2xl font-semibold text-gray-800"><?= esc($totalUsers ?? 0) ?></h1>
            <span class="ml-2 text-green-500 text-xs">Users</span>
        </div>
    </div>

    <!-- Pending Approvals -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-500 text-sm">Pending Approvals</h3>
            <span class="text-yellow-500 bg-yellow-100 rounded-full p-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                </svg>
            </span>
        </div>
        <div class="flex items-baseline">
            <h1 class="text-2xl font-semibold text-gray-800"><?= esc($pendingApprovals ?? 0) ?></h1>
            <span class="ml-2 text-yellow-500 text-xs">Pending</span>
        </div>
    </div>

    <!-- Active Posts -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-500 text-sm">Active Posts</h3>
            <span class="text-green-500 bg-green-100 rounded-full p-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                </svg>
            </span>
        </div>
        <div class="flex items-baseline">
            <h1 class="text-2xl font-semibold text-gray-800"><?= esc($activePosts ?? 0) ?></h1>
            <span class="ml-2 text-green-500 text-xs">Active</span>
        </div>
    </div>

    <!-- Total Organs -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-500 text-sm">Total Organs</h3>
            <span class="text-purple-500 bg-purple-100 rounded-full p-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                </svg>
            </span>
        </div>
        <div class="flex items-baseline">
            <h1 class="text-2xl font-semibold text-gray-800"><?= esc($totalOrgans ?? 0) ?></h1>
            <span class="ml-2 text-purple-500 text-xs">Organs</span>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
