<?= $this->extend('layouts/admin_modern') ?>

<?= $this->section('title') ?>Committees — <?= esc($levelLabel) ?><?= $this->endSection() ?>

<?= $this->section('headerTitle') ?>Constituted Committees<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight"><?= esc($levelLabel) ?> Committees</h2>
            <p class="text-gray-500 mt-1">Showing all constituted committees at the <?= esc($levelLabel) ?> level</p>
        </div>
        <a href="<?= site_url('admin/dashboard') ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700 transition text-sm">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>

    <?php if (!empty($committees)): ?>
    <!-- Summary -->
    <div class="bg-indigo-800 rounded-xl p-5 shadow border border-gray-700 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-300 uppercase tracking-wide font-semibold">Total Constituted Committees</p>
            <p class="text-3xl font-extrabold text-white mt-1"><?= count($committees) ?></p>
        </div>
        <div class="bg-gray-700 p-4 rounded-2xl text-indigo-400">
            <i class="fas fa-building text-2xl"></i>
        </div>
    </div>

    <!-- Committee Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Members</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php foreach ($committees as $i => $committee): ?>
                <tr class="hover:bg-indigo-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium"><?= $i + 1 ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-9 w-9 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-indigo-600"></i>
                            </div>
                            <div class="ml-3">
                                <?php
                                    $parts = array_filter([
                                        $committee['loc1'] ?? null,
                                        $committee['loc2'] ?? null,
                                        $committee['loc3'] ?? null,
                                        $committee['loc4'] ?? null,
                                    ]);
                                    $primary = array_shift($parts); // first = primary location name
                                ?>
                                <p class="text-sm font-semibold text-gray-900"><?= esc($primary) ?></p>
                                <?php if (!empty($parts)): ?>
                                <p class="text-xs text-gray-500 mt-0.5"><?= esc(implode(', ', $parts)) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-emerald-100 text-emerald-800">
                            <?= esc($committee['member_count']) ?> members
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <a href="<?= site_url('admin/usersList?search_term=' . urlencode($committee['loc1'] ?? '')) ?>"
                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-medium hover:bg-blue-700 transition shadow-sm">
                            <i class="fas fa-eye mr-1"></i> View Members
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php else: ?>
    <div class="bg-white rounded-xl shadow-md p-12 text-center border border-gray-200">
        <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
        <p class="text-lg font-medium text-gray-600">No constituted committees found at the <?= esc($levelLabel) ?> level.</p>
        <a href="<?= site_url('admin/dashboard') ?>" class="mt-4 inline-block text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
        </a>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
