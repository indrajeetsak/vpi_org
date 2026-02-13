<?= $this->extend('layouts/admin_main') ?>

<?= $this->section('title') ?>Office Bearer Applications<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Pending Office Bearer Applications</h1>
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

    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Applicant Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Mobile</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Level</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Post</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">State</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">District</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Applied On</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($applications)): ?>
                        <?php foreach ($applications as $app): ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?= esc($app['first_name']) ?> <?= esc($app['last_name']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc($app['mobile']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc($app['email']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc($app['level_name'] ?? 'N/A') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc($app['post_name'] ?? 'N/A') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc($app['state_name'] ?? 'N/A') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc($app['district_name'] ?? 'N/A') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= esc(date('d M Y, h:i A', strtotime($app['application_date']))) ?>
                                </td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <?= ucfirst(esc($app['application_status'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="<?= site_url('admin/users/viewApplication/' . esc($app['application_id'], 'url')) ?>" class="text-indigo-600 hover:text-indigo-800 mr-3 transition-colors duration-150">View</a>
                                    <?php if ($app['application_status'] === 'submitted'): ?>
                                        <a href="<?= site_url('admin/users/approveApplication/' . esc($app['application_id'], 'url')) ?>" class="text-green-600 hover:text-green-800 mr-3 transition-colors duration-150" onclick="return confirm('Are you sure you want to approve this application?');">Approve</a>
                                        <a href="<?= site_url('admin/users/rejectApplication/' . esc($app['application_id'], 'url')) ?>" class="text-red-600 hover:text-red-800 transition-colors duration-150" onclick="return confirm('Are you sure you want to reject this application?');">Reject</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No pending applications found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>