<?= $this->extend('layouts/admin_modern') ?>

<?= $this->section('title') ?>Application Details<?= $this->endSection() ?>

<?= $this->section('headerTitle') ?>Application Details<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Application Details for <?= esc($user['first_name']) ?> <?= esc($user['last_name']) ?></h1>
    <p class="text-gray-600">Review the details of this application</p>
</div>

<div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
    <div class="bg-gray-50 px-6 py-4 border-b">
        <h2 class="text-lg font-bold text-gray-800">Personal Information</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8 text-gray-700">
            <div><strong>Full Name:</strong> <span class="text-gray-900"><?= esc($user['first_name']) ?> <?= esc($user['last_name']) ?></span></div>
            <div><strong>Email:</strong> <span class="text-gray-900"><?= esc($user['email']) ?></span></div>
            <div><strong>Mobile:</strong> <span class="text-gray-900"><?= esc($user['mobile']) ?></span></div>
            <div><strong>Alternate Mobile:</strong> <span class="text-gray-900"><?= esc($user['alternate_mobile'] ?? 'N/A') ?></span></div>
            <div><strong>Date of Birth:</strong> <span class="text-gray-900"><?= esc($user['date_of_birth'] ?? 'N/A') ?></span></div>
            <div><strong>Gender:</strong> <span class="text-gray-900"><?= esc($user['gender'] ?? 'N/A') ?></span></div>
            <div><strong>Father's Name:</strong> <span class="text-gray-900"><?= esc($user['father_name'] ?? 'N/A') ?></span></div>
            <div><strong>Mother's Name:</strong> <span class="text-gray-900"><?= esc($user['mother_name'] ?? 'N/A') ?></span></div>
            <div><strong>Aadhaar Number:</strong> <span class="text-gray-900"><?= esc($user['aadhaar_number'] ?? 'N/A') ?></span></div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
    <div class="bg-gray-50 px-6 py-4 border-b">
        <h2 class="text-lg font-bold text-gray-800">Address Information</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8 text-gray-700">
            <div><strong>Address Line 1:</strong> <span class="text-gray-900"><?= esc($user['address_line1'] ?? 'N/A') ?></span></div>
            <div><strong>Address Line 2:</strong> <span class="text-gray-900"><?= esc($user['address_line2'] ?? 'N/A') ?></span></div>
            <div><strong>State:</strong> <span class="text-gray-900"><?= esc($user['state_name'] ?? 'N/A') ?></span></div>
            <div><strong>District:</strong> <span class="text-gray-900"><?= esc($user['district_name'] ?? 'N/A') ?></span></div>
            <div><strong>MLA Area:</strong> <span class="text-gray-900"><?= esc($user['mla_area_name'] ?? 'N/A') ?></span></div>
            <div><strong>Block:</strong> <span class="text-gray-900"><?= esc($user['block_name'] ?? 'N/A') ?></span></div>
            <div><strong>Sector:</strong> <span class="text-gray-900"><?= esc($user['sector_name'] ?? 'N/A') ?></span></div>
            <div><strong>PIN Code:</strong> <span class="text-gray-900"><?= esc($user['pin_code'] ?? 'N/A') ?></span></div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
    <div class="bg-gray-50 px-6 py-4 border-b">
        <h2 class="text-lg font-bold text-gray-800">Application Details</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8 text-gray-700">
            <div><strong>Organ:</strong> <span class="text-gray-900"><?= esc($user['organ_name'] ?? 'N/A') ?></span></div>
            <div><strong>Level:</strong> <span class="text-gray-900"><?= esc($user['level_name'] ?? 'N/A') ?></span></div>
            <div><strong>Post:</strong> <span class="text-gray-900"><?= esc($user['post_name'] ?? 'N/A') ?></span></div>
            <div><strong>Application Status:</strong> 
                <span class="text-gray-900">
                    <?php if ($user['status'] === 'pending'): ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    <?php elseif ($user['status'] === 'approved'): ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Approved
                        </span>
                    <?php elseif ($user['status'] === 'rejected'): ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Rejected
                        </span>
                    <?php else: ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            <?= ucfirst(esc($user['status'] ?? 'N/A')) ?>
                        </span>
                    <?php endif; ?>
                </span>
            </div>
            <div><strong>Registered At:</strong> <span class="text-gray-900"><?= esc($user['created_at'] ?? 'N/A') ?></span></div>
            <div><strong>Last Updated:</strong> <span class="text-gray-900"><?= esc($user['updated_at'] ?? 'N/A') ?></span></div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
    <div class="bg-gray-50 px-6 py-4 border-b">
        <h2 class="text-lg font-bold text-gray-800">Appointment Committee</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8 text-gray-700">
            <div><strong>Committee State:</strong> <span class="text-gray-900"><?= esc($user['committee_state'] ?? 'N/A') ?></span></div>
            <div><strong>Committee District:</strong> <span class="text-gray-900"><?= esc($user['committee_district'] ?? 'N/A') ?></span></div>
            <div><strong>Committee Block:</strong> <span class="text-gray-900"><?= esc($user['committee_block'] ?? 'N/A') ?></span></div>
            <div><strong>Committee MLA Area:</strong> <span class="text-gray-900"><?= esc($user['committee_mla_area'] ?? 'N/A') ?></span></div>
            <div><strong>Committee Sector:</strong> <span class="text-gray-900"><?= esc($user['committee_sector'] ?? 'N/A') ?></span></div>
        </div>
    </div>
</div>

<div class="text-right mt-8">
    <a href="<?= site_url('admin/usersList') ?>" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
        Back to Users List
    </a>
</div>
<?= $this->endSection() ?>