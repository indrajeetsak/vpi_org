<?= $this->extend('layouts/admin_modern') ?>

<?= $this->section('title') ?>View Application<?= $this->endSection() ?>

<?= $this->section('headerTitle') ?>View Application<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Application Details</h1>
    <p class="text-gray-600">Review the details of this application</p>
</div>

<?php if ($application): ?>
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Application ID</label>
                            <div class="mt-1 text-sm text-gray-900"><?= esc($application['id']) ?></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Applicant Name</label>
                            <div class="mt-1 text-sm text-gray-900"><?= esc($application['first_name'] . ' ' . $application['last_name']) ?></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <div class="mt-1 text-sm text-gray-900"><?= esc($application['email']) ?></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Mobile</label>
                            <div class="mt-1 text-sm text-gray-900"><?= esc($application['mobile']) ?></div>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Application Details</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date of Birth</label>
                            <div class="mt-1 text-sm text-gray-900"><?= esc($application['date_of_birth']) ?></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <div class="mt-1">
                                <?php if ($application['status'] === 'pending'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                <?php elseif ($application['status'] === 'approved'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Approved
                                    </span>
                                <?php elseif ($application['status'] === 'rejected'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        <?= esc($application['status']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date Applied</label>
                            <div class="mt-1 text-sm text-gray-900"><?= esc(date('d M Y, h:i A', strtotime($application['created_at']))) ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 flex space-x-4">
                <a href="<?= site_url('admin/users/approveApplication/' . $application['id']) ?>" class="px-4 py-2 bg-green-600 text-white rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                    Approve
                </a>
                <a href="<?= site_url('admin/applications/reject/' . $application['id']) ?>" class="px-4 py-2 bg-red-600 text-white rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                    Reject
                </a>
                <a href="<?= site_url('admin/usersRequest') ?>" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                    Back to Applications
                </a>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="bg-white rounded-xl shadow-md p-10 text-center">
        <p class="text-gray-500">No application found with this ID.</p>
        <a href="<?= site_url('admin/usersRequest') ?>" class="mt-4 inline-block text-blue-600 hover:text-blue-800">Back to Applications</a>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>