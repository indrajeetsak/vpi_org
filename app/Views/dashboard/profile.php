<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Profile<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 min-h-screen p-4">
            <nav class="space-y-2">
                <a href="<?= base_url('dashboard') ?>" 
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">Dashboard</a>
                <a href="<?= base_url('dashboard/profile') ?>" 
                   class="block px-4 py-2 text-white bg-blue-600 rounded-md">My Profile</a>
                <a href="<?= base_url('dashboard/id-card') ?>" 
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">ID Card</a>
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
            <h1 class="text-2xl font-bold mb-8">My Profile</h1>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?= session()->getFlashdata('success') ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="<?= base_url('dashboard/profile') ?>" method="post" enctype="multipart/form-data" class="space-y-6">
                    <?= csrf_field() ?>
                    
                    <!-- Profile Photo -->
                    <div class="flex items-center space-x-6 mb-4">
                        <img src="<?= base_url('uploads/photos/' . esc($user['photo'])) ?>" 
                             alt="Current Profile Photo"
                             class="w-24 h-24 rounded-full object-cover">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Profile Photo
                            </label>
                            <input type="file" name="photo" accept="image/*" class="mt-1">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Personal Information -->
                        <div>
                            <h3 class="text-lg font-medium mb-4">Personal Information</h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                                    <input type="text" name="first_name" value="<?= esc($user['first_name']) ?>" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                    <input type="text" name="last_name" value="<?= esc($user['last_name']) ?>" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" value="<?= esc($user['email']) ?>" required readonly
                                       class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Mobile</label>
                                    <input type="tel" name="mobile" value="<?= esc($user['mobile']) ?>" required readonly
                                           class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Alternate Mobile</label>
                                    <input type="tel" name="alternate_mobile" value="<?= esc($user['alternate_mobile'] ?? '') ?>"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                <input type="date" name="date_of_birth" value="<?= esc($user['date_of_birth']) ?>" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Gender</label>
                                <select name="gender" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="male" <?= $user['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
                                    <option value="female" <?= $user['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                                    <option value="other" <?= $user['gender'] === 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Address and Other Information -->
                        <div>
                            <h3 class="text-lg font-medium mb-4">Address & Other Information</h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Father's Name</label>
                                    <input type="text" name="father_name" value="<?= esc($user['father_name']) ?>" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Mother's Name</label>
                                    <input type="text" name="mother_name" value="<?= esc($user['mother_name']) ?>" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Aadhaar Number</label>
                                <input type="text" name="aadhaar_number" value="<?= esc($user['aadhaar_number']) ?>" required readonly
                                       class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">State</label>
                                    <input type="text" name="state" value="<?= esc($user['state_id']) ?>" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">District</label>
                                    <input type="text" name="district" value="<?= esc($user['district_id']) ?>" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">MLA Area</label>
                                    <input type="text" name="mla_area" value="<?= esc($user['mla_area_id']) ?>" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Block</label>
                                    <input type="text" name="block" value="<?= esc($user['block_id']) ?>" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Address Line 1</label>
                                <input type="text" name="address_line1" value="<?= esc($user['address_line1']) ?>" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Address Line 2</label>
                                <input type="text" name="address_line2" value="<?= esc($user['address_line2'] ?? '') ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">PIN Code</label>
                                <input type="text" name="pin_code" value="<?= esc($user['pin_code']) ?>" required
                                       pattern="[0-9]{6}" title="Please enter a valid 6-digit PIN code"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Post Information -->
                    <div class="mt-6">
                        <h3 class="text-lg font-medium mb-4">Post Information</h3>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Organ</label>
                                <input type="text" value="<?= esc($organ_name ?? 'Not Assigned') ?>" readonly
                                       class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Level</label>
                                <input type="text" value="<?= esc($level_name ?? 'Not Assigned') ?>" readonly
                                       class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Post</label>
                                <input type="text" value="<?= esc($post_name ?? 'Not Assigned') ?>" readonly
                                       class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
