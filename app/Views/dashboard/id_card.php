<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>ID Card<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 min-h-screen p-4">
            <nav class="space-y-2">
                <a href="<?= base_url('dashboard') ?>" 
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">Dashboard</a>
                <a href="<?= base_url('dashboard/profile') ?>" 
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">My Profile</a>
                <a href="<?= base_url('dashboard/id-card') ?>" 
                   class="block px-4 py-2 text-white bg-blue-600 rounded-md">ID Card</a>
                <a href="<?= base_url('dashboard/visiting-card') ?>" 
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">Visiting Card</a>
                <a href="<?= base_url('dashboard/letter-head') ?>" 
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">Letter Head</a>
                <a href="<?= base_url('auth/logout') ?>"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <h1 class="text-2xl font-bold mb-8">ID Card</h1>

            <!-- ID Card Preview -->
            <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- ID Card Front -->
                <div class="relative p-6 border-b">
                    <h2 class="text-lg font-semibold text-center mb-4">VPI Office Bearer ID Card</h2>
                    <div class="flex items-start space-x-4">
                        <img src="<?= base_url('uploads/photos/' . esc($user['photo'])) ?>" 
                             alt="Profile Photo"
                             class="w-32 h-32 rounded-lg object-cover border-2 border-blue-500">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></h3>
                            <p class="text-blue-600 font-semibold"><?= esc($user['post_name'] ?? 'Post Not Assigned') ?></p>
                            <p class="text-gray-600"><?= esc($user['organ_name'] ?? 'Organ Not Assigned') ?></p>
                            <p class="text-gray-600"><?= esc($user['level_name'] ?? 'Level Not Assigned') ?></p>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <p class="font-semibold">Mobile:</p>
                            <p><?= esc($user['mobile']) ?></p>
                        </div>
                        <div>
                            <p class="font-semibold">State:</p>
                            <p><?= esc($user['state_name']) ?></p>
                        </div>
                        <div>
                            <p class="font-semibold">District:</p>
                            <p><?= esc($user['district_name']) ?></p>
                        </div>
                        <div>
                            <p class="font-semibold">ID:</p>
                            <p>VPI-<?= str_pad($user['id'], 6, '0', STR_PAD_LEFT) ?></p>
                        </div>
                    </div>
                </div>

                <!-- ID Card Back -->
                <div class="p-6">
                    <div class="text-sm space-y-2">
                        <div>
                            <p class="font-semibold">Father's Name:</p>
                            <p><?= esc($user['father_name']) ?></p>
                        </div>
                        <div>
                            <p class="font-semibold">Address:</p>
                            <p><?= esc($user['address_line1']) ?>
                               <?= $user['address_line2'] ? ', ' . esc($user['address_line2']) : '' ?>
                               <br>
                                <?= esc($user['district_name']) ?>
                               <br>
                               <?= esc($user['state_name']) ?> - <?= esc($user['pin_code']) ?>
                            </p>
                        </div>
                        <div class="mt-4">
                            <p class="font-semibold">Blood Group:</p>
                            <p>Not Available</p>
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-center text-gray-500">
                        <p>If found, please return to:</p>
                        <p>VPI Office Bearer Administration</p>
                        <p>Contact: [Admin Contact Number]</p>
                    </div>
                </div>
            </div>

            <!-- Download/Print Buttons -->
            <div class="mt-8 flex justify-center space-x-4">
                <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print ID Card
                </button>
                <a href="#" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download PDF
                </a>
            </div>

            <!-- Print Stylesheet -->
            <style type="text/css" media="print">
                @page {
                    size: 85.6mm 53.98mm; /* Standard ID card size */
                    margin: 0;
                }
                body * {
                    visibility: hidden;
                }
                .max-w-md {
                    visibility: visible;
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                }
                .max-w-md * {
                    visibility: visible;
                }
            </style>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
