<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Visiting Card<?= $this->endSection() ?>

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
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">ID Card</a>
                <a href="<?= base_url('dashboard/visiting-card') ?>" 
                   class="block px-4 py-2 text-white bg-blue-600 rounded-md">Visiting Card</a>
                <a href="<?= base_url('dashboard/letter-head') ?>" 
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">Letter Head</a>
                <a href="<?= base_url('auth/logout') ?>"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <h1 class="text-2xl font-bold mb-8">Visiting Card</h1>

            <!-- Card Style Selection -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold mb-4">Select Card Style</h2>
                <div class="grid grid-cols-3 gap-6">
                    <!-- Style 1 -->
                    <div class="border p-4 rounded-lg cursor-pointer hover:border-blue-500 bg-white">
                        <div class="aspect-[1.75] border rounded bg-gradient-to-r from-blue-500 to-blue-700 p-4 text-white">
                            <h3 class="text-sm mb-2">Classic Style</h3>
                            <div class="text-xs opacity-75">Professional and Traditional</div>
                        </div>
                    </div>
                    <!-- Style 2 -->
                    <div class="border p-4 rounded-lg cursor-pointer hover:border-blue-500 bg-white">
                        <div class="aspect-[1.75] border rounded bg-gradient-to-r from-gray-800 to-gray-900 p-4 text-white">
                            <h3 class="text-sm mb-2">Modern Style</h3>
                            <div class="text-xs opacity-75">Clean and Contemporary</div>
                        </div>
                    </div>
                    <!-- Style 3 -->
                    <div class="border p-4 rounded-lg cursor-pointer hover:border-blue-500 bg-white">
                        <div class="aspect-[1.75] border rounded bg-gradient-to-r from-orange-500 to-red-600 p-4 text-white">
                            <h3 class="text-sm mb-2">Dynamic Style</h3>
                            <div class="text-xs opacity-75">Bold and Energetic</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Preview -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold mb-4">Preview</h2>
                <!-- Front Side -->
                <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden mb-4">
                    <div class="aspect-[1.75] p-6 bg-gradient-to-r from-blue-500 to-blue-700">
                        <div class="h-full flex flex-col justify-between text-white">
                            <div>
                                <h3 class="text-xl font-bold"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></h3>
                                <p class="text-blue-100 font-medium"><?= esc($user['post_name'] ?? 'Post Not Assigned') ?></p>
                                <p class="text-blue-100"><?= esc($user['organ_name'] ?? 'Organ Not Assigned') ?></p>
                                <p class="text-blue-100"><?= esc($user['level_name'] ?? 'Level Not Assigned') ?></p>
                            </div>
                            <div class="flex justify-between items-end mt-4">
                                <div>
                                    <p class="text-sm">Mobile: <?= esc($user['mobile']) ?></p>
                                    <p class="text-sm">Email: <?= esc($user['email']) ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm"><?= esc($user['block_name'] ?? 'N/A') ?>, <?= esc($user['district_name'] ?? 'N/A') ?>, <?= esc($user['state_name'] ?? 'N/A') ?></p>
                                    <p class="text-sm">PIN: <?= esc($user['pin_code']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back Side -->
                <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="aspect-[1.75] p-6">
                        <div class="h-full flex flex-col justify-center items-center text-center">
                            <img src="/images/logo.png" alt="Organization Logo" class="w-24 mb-4">
                            <h3 class="text-xl font-bold text-gray-800">VPI Office Bearer</h3>
                            <p class="text-gray-600 text-sm mt-2">United We Stand, Together We Grow</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Download/Print Options -->
            <div class="flex justify-center space-x-4">
                <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print Visiting Card
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
                    size: 89mm 51mm; /* Standard business card size */
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
