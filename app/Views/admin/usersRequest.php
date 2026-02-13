<?= $this->extend('layouts/admin_modern') ?>

<?= $this->section('title') ?>Application Requests<?= $this->endSection() ?>

<?= $this->section('headerTitle') ?>Application Requests<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Pending Application Requests</h1>
    <p class="text-gray-600">Review and manage pending applications</p>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-md rounded-md" role="alert">
        <p class="font-bold">Success</p>
        <p><?= session()->getFlashdata('success') ?></p>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-md rounded-md" role="alert">
        <p class="font-bold">Error</p>
        <p><?= session()->getFlashdata('error') ?></p>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <?php if (!empty($applications) && is_array($applications)): ?>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Applicant Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Contact</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Applied For</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Location</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Applied On</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($applications as $app): ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= esc($app['first_name']) ?> <?= esc($app['last_name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div><?= esc($app['mobile']) ?></div>
                                <div><?= esc($app['email']) ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="font-semibold"><?= esc($app['level_name'] ?? 'N/A') ?></div>
                                <div><?= esc($app['post_name'] ?? 'N/A') ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="font-semibold"><?= esc($app['state_name'] ?? 'N/A') ?></div>
                                <div><?= esc($app['district_name'] ?? 'N/A') ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc(date('d M Y, h:i A', strtotime($app['applied_at']))) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <?= ucfirst(esc($app['appointment_status'] ?? 'Pending')) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="<?= site_url('admin/users/view/' . esc($app['id'], 'url')) ?>" class="text-indigo-600 hover:text-indigo-800 transition-colors duration-150">View</a>
                                <?php if (($app['appointment_status'] ?? 'pending') === 'pending'): ?>
                                    <a href="<?= site_url('admin/users/approveApplication/' . esc($app['id'], 'url')) ?>" class="text-green-600 hover:text-green-800 transition-colors duration-150" onclick="return confirm('Are you sure you want to approve this application?');">Approve</a>
                                    <a href="<?= site_url('admin/applications/reject/' . esc($app['id'], 'url')) ?>" class="text-red-600 hover:text-red-800 transition-colors duration-150" onclick="return confirm('Are you sure you want to reject this application?');">Reject</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="p-4">
                <?= $pager->links() ?? '' ?>
            </div>
        <?php else: ?>
            <p class="text-center p-10 text-gray-500">No pending applications found.</p>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
