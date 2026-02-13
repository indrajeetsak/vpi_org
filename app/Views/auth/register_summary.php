    <?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Register - Step 5: Review Application<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2 text-sm">
                 <div>
                        <span class="font-medium text-gray-600 block">Address Line 1:</span>
                        <p><?= esc($data['address_line1'] ?? '') ?></p>
                    </div>
                    <?php if (!empty($data['address_line2'])): ?>
                    <div>
                        <span class="font-medium text-gray-600 block">Address Line 2:</span>
                        <p><?= esc($data['address_line2']) ?></p>
                    </div>
                    <?php endif; ?>
                    <div>
                        <span class="font-medium text-gray-600 block">Pin Code:</span>
                        <p><?= esc($data['pin_code'] ?? '') ?></p>
                    </div>
                </div>
                <!-- Consider adding State, District, MLA Area, Block here if they are part of the address and available in $data -->
            </div>
            <div class="h-2.5 bg-gray-200 rounded-full">
                <div class="h-2.5 bg-blue-600 rounded-full" style="width: 100%;"></div>
            </div>
        </div>
        <h1 class="text-2xl font-bold mb-6 text-center">Review Your Application Details</h1>

        <?php if(session()->has('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?= session('error') ?>
            </div>
        <?php endif; ?>

        <?php $data = $savedData ?? []; // Ensure $data is an array ?>

        <div class="space-y-6">
            <!-- Personal Information -->
            <div class="bg-gray-50 p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-3 text-blue-700 border-b pb-2">Personal Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="font-medium text-gray-600 block">Full Name:</span> 
                        <p><?= esc($data['first_name'] ?? '') ?> <?= esc($data['last_name'] ?? '') ?></p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600 block">Date of Birth:</span> 
                        <p><?= esc($data['date_of_birth'] ?? '') ?></p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600 block">Gender:</span> 
                        <p><?= ucfirst(esc($data['gender'] ?? '')) ?></p>
                    </div>
                    <?php if (!empty($data['photo'])): ?>
                    <div class="md:col-span-2">
                        <span class="font-medium text-gray-600 block">Photo:</span>
                        <img src="<?= base_url('uploads/photos/' . esc($data['photo'])) ?>" alt="Profile Photo" class="w-24 h-auto rounded mt-1">
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-gray-50 p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-3 text-blue-700 border-b pb-2">Contact Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="font-medium text-gray-600 block">WhatsApp Number:</span> 
                        <p><?= esc($data['mobile'] ?? '') ?></p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600 block">Alternate Number:</span> 
                        <p><?= esc($data['alternate_mobile'] ?? 'N/A') ?></p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600 block">Email:</span> 
                        <p><?= esc($data['email'] ?? '') ?></p>
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            <div class="bg-gray-50 p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-3 text-blue-700 border-b pb-2">Location Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><span class="font-medium text-gray-600 block">Lok Sabha Constituency:</span> <p><?= esc($data['ls_name'] ?? 'N/A') ?></p></div>
                    <div><span class="font-medium text-gray-600 block">Parivar Sabha Constituency:</span> <p><?= esc($data['2ls_name'] ?? 'N/A') ?></p></div>
                    <div><span class="font-medium text-gray-600 block">Gram Sabha Constituency:</span> <p><?= esc($data['3ls_name'] ?? 'N/A') ?></p></div>
                    <div><span class="font-medium text-gray-600 block">Jansabha Constituency:</span> <p><?= esc($data['4ls_name'] ?? 'N/A') ?></p></div>
                </div>
            </div>

            <!-- Family & Identification -->
            <div class="bg-gray-50 p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-3 text-blue-700 border-b pb-2">Family & Identification</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><span class="font-medium text-gray-600 block">Father's/Husband's Name:</span> <p><?= esc($data['father_name'] ?? '') ?></p></div>
                    <div><span class="font-medium text-gray-600 block">Mother's/Wife's Name:</span> <p><?= esc($data['mother_name'] ?? '') ?></p></div>
                    <div><span class="font-medium text-gray-600 block">Aadhaar Number:</span> <p><?= esc($data['aadhaar_number'] ?? '') ?></p></div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-gray-50 p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-3 text-blue-700 border-b pb-2">Address Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="font-medium text-gray-600 block">State:</span> 
                        <p><?= esc($data['state_name'] ?? '') ?></p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600 block">District:</span> 
                        <p><?= esc($data['district_name'] ?? '') ?></p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600 block">MLA Area:</span> 
                        <p><?= esc($data['mla_area_name'] ?? '') ?></p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600 block">Block:</span> 
                        <p><?= esc($data['block_name'] ?? '') ?></p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600 block">Address Line1:</span> 
                        <p><?= esc($data['address_line1'] ?? '') ?></p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600 block">Address Line2:</span> 
                        <p><?= esc($data['address_line2'] ?? '') ?></p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600 block">Pin Code:</span> 
                        <p><?= esc($data['pin_code'] ?? '') ?></p>
                    </div>
                </div>
<!-- Level and Post Information -->
            </div>
            <div class="bg-gray-50 p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-3 text-blue-700 border-b pb-2">Post Application Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="font-medium text-gray-600 block">Level:</span> 
                        <p><?= esc($data['level_name'] ?? 'Not Assigned') ?></p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600 block">Post:</span> 
                        <p><?= esc($data['post_name'] ?? 'Not Assigned') ?></p>
                    </div>
                </div>
            <!-- Login Information -->
            <div class="bg-gray-50 p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-3 text-blue-700 border-b pb-2">Login Details</h2>
                <div>
                    <span class="font-medium text-gray-600 block">User ID (Mobile Number):</span> 
                    <p><?= esc($data['mobile'] ?? '') ?></p>
                </div>
                <div class="mt-2">
                    <span class="font-medium text-gray-600 block">Password:</span> 
                    <p class="text-gray-500">[Hidden for security]</p>
                </div>
            </div>
        </div>
        

        <!-- Action Buttons -->
               <div class="flex justify-between mt-8">
            <button type="button" onclick="window.location.href='<?= site_url('auth/register?step=1') ?>'"
                    class="block bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-md focus:outline-none focus:shadow-outline visible opacity-100">
                &larr; Previous (Edit Post Application)
            </button>
             <form action="<?= site_url('auth/confirm-registration') ?>" method="POST" class="inline">
                <?= csrf_field() ?>
                <button type="submit"
                        class="block bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-md focus:outline-none focus:shadow-outline visible opacity-100">
                    Confirm & Proceed to Payment &rarr;
                </button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>