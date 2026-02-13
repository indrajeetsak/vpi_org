<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Letter Head<?= $this->endSection() ?>

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
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">Visiting Card</a>
                <a href="<?= base_url('dashboard/letter-head') ?>" 
                   class="block px-4 py-2 text-white bg-blue-600 rounded-md">Letter Head</a>
                <a href="<?= base_url('auth/logout') ?>"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <h1 class="text-2xl font-bold mb-8">Letter Head</h1>

            <!-- Style Selection -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold mb-4">Select Letter Head Style</h2>
                <div class="grid grid-cols-3 gap-6">
                    <!-- Style 1 -->
                    <div class="border p-4 rounded-lg cursor-pointer hover:border-blue-500 bg-white">
                        <div class="aspect-[0.7] border rounded bg-white p-4">
                            <div class="h-8 bg-blue-600 mb-2"></div>
                            <div class="h-4 bg-gray-200 w-1/2 mb-4"></div>
                            <div class="space-y-1">
                                <div class="h-2 bg-gray-100 w-full"></div>
                                <div class="h-2 bg-gray-100 w-full"></div>
                            </div>
                        </div>
                        <p class="text-center mt-2 text-sm font-medium">Professional Style</p>
                    </div>
                    <!-- Style 2 -->
                    <div class="border p-4 rounded-lg cursor-pointer hover:border-blue-500 bg-white">
                        <div class="aspect-[0.7] border rounded bg-white p-4">
                            <div class="h-8 bg-gradient-to-r from-blue-500 to-blue-700 mb-2"></div>
                            <div class="h-4 bg-gray-200 w-1/2 mb-4"></div>
                            <div class="space-y-1">
                                <div class="h-2 bg-gray-100 w-full"></div>
                                <div class="h-2 bg-gray-100 w-full"></div>
                            </div>
                        </div>
                        <p class="text-center mt-2 text-sm font-medium">Modern Style</p>
                    </div>
                    <!-- Style 3 -->
                    <div class="border p-4 rounded-lg cursor-pointer hover:border-blue-500 bg-white">
                        <div class="aspect-[0.7] border rounded bg-white p-4">
                            <div class="border-2 border-blue-600 h-full p-4">
                                <div class="h-8 bg-blue-600 mb-2"></div>
                                <div class="h-4 bg-gray-200 w-1/2 mb-4"></div>
                                <div class="space-y-1">
                                    <div class="h-2 bg-gray-100 w-full"></div>
                                    <div class="h-2 bg-gray-100 w-full"></div>
                                </div>
                            </div>
                        </div>
                        <p class="text-center mt-2 text-sm font-medium">Bordered Style</p>
                    </div>
                </div>
            </div>

            <!-- Letter Head Preview -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold mb-4">Preview</h2>
                <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                    <!-- Header Section -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white p-8">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <img src="/images/logo.png" alt="Organization Logo" class="w-16 h-16">
                                <div>
                                    <h3 class="text-2xl font-bold">VPI Office Bearer</h3>
                                    <p class="text-blue-100">United We Stand, Together We Grow</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></p>
                                <p class="text-blue-100"><?= esc($user['post_name'] ?? 'Post Not Assigned') ?></p>
                                <p class="text-blue-100"><?= esc($user['organ_name'] ?? 'Organ Not Assigned') ?></p>
                                <p class="text-blue-100"><?= esc($user['level_name'] ?? 'Level Not Assigned') ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="border-t border-b border-gray-200 px-8 py-4 bg-gray-50">
                        <div class="flex justify-between text-sm text-gray-600">
                            <div>
                                <p>Mobile: <?= esc($user['mobile']) ?></p>
                                <p>Email: <?= esc($user['email']) ?></p>
                            </div>
                            <div class="text-right">
                                <p><?= esc($user['address_line1']) ?></p>
                                <p><?= esc($user['district_name']) ?>, <?= esc($user['state_name']) ?> - <?= esc($user['pin_code']) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Sample Content Area -->
                    <div class="p-8 min-h-[600px]">
                        <div class="text-right mb-8">
                            <p>Date: <?= date('d/m/Y') ?></p>
                            <p>Ref: VPI/<?= date('Y') ?>/<?= str_pad($user['id'], 4, '0', STR_PAD_LEFT) ?></p>
                        </div>
                        <div class="space-y-4 text-gray-400 select-none">
                            <p>To,</p>
                            <p>____________________</p>
                            <p>____________________</p>
                            <p>____________________</p>
                            <br>
                            <p>Subject: ____________________</p>
                            <br>
                            <p>Dear Sir/Madam,</p>
                            <p>____________________</p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="border-t border-gray-200 p-4 text-center text-sm text-gray-600">
                        <p>Official Communication - VPI Office Bearer</p>
                    </div>
                </div>
            </div>

            <!-- Download/Print Options -->
            <div class="flex justify-center space-x-4">
                <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print Letter Head
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
                    size: A4;
                    margin: 0;
                }
                body * {
                    visibility: hidden;
                }
                .max-w-4xl {
                    visibility: visible;
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 21cm;
                    height: 29.7cm;
                }
                .max-w-4xl * {
                    visibility: visible;
                }
            </style>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
