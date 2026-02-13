<?= $this->extend('layouts/admin_modern') ?>

<?= $this->section('title') ?>Edit Office Bearer<?= $this->endSection() ?>

<?= $this->section('headerTitle') ?>Edit Office Bearer<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if (!empty($user)): ?>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Office Bearer: <?= esc($user['first_name'] . ' ' . $user['last_name']) ?></h1>
        <p class="text-gray-600">Update office bearer information</p>
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
        <div class="p-6">
            <form id="editUserForm" action="<?= site_url('admin/users/update/' . $user['id']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <!-- Personal Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" id="first_name" value="<?= esc($user['first_name'] ?? '', 'attr') ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-gray-500 text-xs">(Optional)</span></label>
                            <input type="text" name="last_name" id="last_name" value="<?= esc($user['last_name'] ?? '', 'attr') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" value="<?= esc($user['email'] ?? '', 'attr') ?>" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100">
                        </div>
                        <div>
                            <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
                            <input type="tel" name="mobile" id="mobile" value="<?= esc($user['mobile'] ?? '', 'attr') ?>" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100">
                        </div>
                        <div>
                            <label for="father_name" class="block text-sm font-medium text-gray-700 mb-1">Father/Husband's Name</label>
                            <input type="text" name="father_name" id="father_name" value="<?= esc($user['father_name'] ?? '', 'attr') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" value="<?= esc($user['date_of_birth'] ?? '', 'attr') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <select name="gender" id="gender" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Gender</option>
                                <option value="male" <?= ($user['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?= ($user['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
                                <option value="other" <?= ($user['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                            <input type="file" name="photo" id="photo" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Upload a new photo (JPG, PNG, max 2MB)</p>
                            <?php if (!empty($user['photo'])): ?>
                                <div class="mt-2">
                                    <img src="<?= base_url('uploads/photos/' . $user['photo']) ?>" alt="Current Photo" class="w-24 h-24 object-cover rounded-md border" id="currentPhoto">
                                    <p class="text-xs text-gray-500 mt-1">Current photo</p>
                                </div>
                            <?php endif; ?>
                            <div class="mt-2 hidden" id="photoPreview">
                                <img src="" alt="Photo Preview" class="w-24 h-24 object-cover rounded-md border" id="previewImage">
                                <p class="text-xs text-gray-500 mt-1">New photo preview</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Address Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="address_line1" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea id="address_line1" name="address_line1" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"><?= esc($user['address_line1'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label for="state_id" class="block text-sm font-medium text-gray-700 mb-1">State <span class="text-red-500">*</span></label>
                            <select id="state_id" name="state_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select State</option>
                                <?php foreach ($all_states as $state): ?>
                                    <option value="<?= $state['id'] ?>" <?= ($user['state_id'] ?? '') == $state['id'] ? 'selected' : '' ?>><?= esc($state['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="district_id" class="block text-sm font-medium text-gray-700 mb-1">District <span class="text-red-500">*</span></label>
                            <select id="district_id" name="district_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select District</option>
                                <?php if (!empty($districts)): ?>
                                    <?php foreach ($districts as $district): ?>
                                        <option value="<?= $district['id'] ?>" <?= ($user['district_id'] ?? '') == $district['id'] ? 'selected' : '' ?>><?= esc($district['name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Appointment Details -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Appointment Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Organ, Level, Post -->
                        <div>
                            <label for="organ_id" class="block text-sm font-medium text-gray-700 mb-1">Organ</label>
                            <select id="organ_id" name="organ_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Organ</option>
                                <?php foreach ($all_organs as $organ): ?>
                                    <option value="<?= $organ['id'] ?>" <?= ($user['organ_id'] ?? '') == $organ['id'] ? 'selected' : '' ?>><?= esc($organ['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="level_id" class="block text-sm font-medium text-gray-700 mb-1">Level <span class="text-red-500">*</span></label>
                            <select id="level_id" name="level_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Level</option>
                                <?php foreach ($all_levels as $level): ?>
                                    <option value="<?= $level['id'] ?>" <?= ($user['level_id'] ?? '') == $level['id'] ? 'selected' : '' ?>><?= esc($level['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="post_id" class="block text-sm font-medium text-gray-700 mb-1">Post <span class="text-red-500">*</span></label>
                            <select id="post_id" name="post_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Post</option>
                                <?php if (!empty($current_posts)): ?>
                                    <?php foreach ($current_posts as $post): ?>
                                        <option value="<?= $post['id'] ?>" <?= ($user['post_id'] ?? '') == $post['id'] ? 'selected' : '' ?>><?= esc($post['name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Location Fields (Appointment) -->
                        <div>
                            <label for="committee_state_id" class="block text-sm font-medium text-gray-700 mb-1">Appointment State <span class="text-red-500">*</span></label>
                            <select id="committee_state_id" name="committee_state_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select State</option>
                                <?php foreach ($all_states as $state): ?>
                                    <option value="<?= $state['id'] ?>" <?= ($user['committee_state_id'] ?? '') == $state['id'] ? 'selected' : '' ?>><?= esc($state['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="committee_district_id" class="block text-sm font-medium text-gray-700 mb-1">Appointment District</label>
                            <select id="committee_district_id" name="committee_district_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select District</option>
                                <?php if (!empty($committee_districts)): ?>
                                    <?php foreach ($committee_districts as $district): ?>
                                        <option value="<?= $district['id'] ?>" <?= ($user['committee_district_id'] ?? '') == $district['id'] ? 'selected' : '' ?>><?= esc($district['name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <label for="block_id" class="block text-sm font-medium text-gray-700 mb-1">Block</label>
                            <select id="block_id" name="block_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Block</option>
                                <?php if (!empty($current_blocks)): ?>
                                    <?php foreach ($current_blocks as $block): ?>
                                        <option value="<?= $block['id'] ?>" <?= ($user['block_id'] ?? '') == $block['id'] ? 'selected' : '' ?>><?= esc($block['name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <label for="sector_id" class="block text-sm font-medium text-gray-700 mb-1">Sector</label>
                            <select id="sector_id" name="sector_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Sector</option>
                                <?php if (!empty($current_sectors)): ?>
                                    <?php foreach ($current_sectors as $sector): ?>
                                        <option value="<?= $sector['id'] ?>" <?= ($user['sector_id'] ?? '') == $sector['id'] ? 'selected' : '' ?>><?= esc($sector['name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <label for="mla_area_id" class="block text-sm font-medium text-gray-700 mb-1">MLA Constituency</label>
                            <select id="mla_area_id" name="mla_area_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select MLA Constituency</option>
                                <?php if (!empty($current_mla_areas)): ?>
                                    <?php foreach ($current_mla_areas as $mla_area): ?>
                                        <option value="<?= $mla_area['id'] ?>" <?= ($user['mla_area_id'] ?? '') == $mla_area['id'] ? 'selected' : '' ?>><?= esc($mla_area['name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                         <div>
                            <label for="ls_id" class="block text-sm font-medium text-gray-700 mb-1">MP Constituency (1LS)</label>
                            <select id="ls_id" name="ls_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select MP Constituency</option>
                                <!-- TODO: Populate 4LS list if needed, currently not passed in data but we can add -->
                                <!-- For now assume it might be populated if we add logic or leave empty for dynamic load -->
                            </select> 
                        </div>
                        
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select name="status" id="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="pending" <?= ($user['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= ($user['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="rejected" <?= ($user['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                <option value="suspended" <?= ($user['status'] ?? '') === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="<?= site_url('admin/usersList') ?>" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="bg-white rounded-xl shadow-md p-10 text-center">
        <p class="text-gray-500">User not found.</p>
        <a href="<?= site_url('admin/usersList') ?>" class="mt-4 inline-block text-blue-600 hover:text-blue-800">Back to List</a>
    </div>
<?php endif; ?>

<script>
    // --- Personal Address JS ---
    const stateSelect = document.getElementById('state_id');
    const districtSelect = document.getElementById('district_id');

    stateSelect.addEventListener('change', function() {
        const stateId = this.value;
        districtSelect.innerHTML = '<option value="">Select District</option>';
        if (stateId) {
            fetch(`<?= site_url('auth/get-districts/') ?>${stateId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data) {
                        data.data.forEach(district => {
                            const option = document.createElement('option');
                            option.value = district.id;
                            option.textContent = district.name;
                            districtSelect.appendChild(option);
                        });
                    }
                });
        }
    });

    // --- Appointment Location JS ---
    const committeeStateSelect = document.getElementById('committee_state_id');
    const committeeDistrictSelect = document.getElementById('committee_district_id');
    const blockSelect = document.getElementById('block_id');
    const sectorSelect = document.getElementById('sector_id');
    const mlaAreaSelect = document.getElementById('mla_area_id');

    // Appointment State change
    committeeStateSelect.addEventListener('change', function() {
        const stateId = this.value;
        committeeDistrictSelect.innerHTML = '<option value="">Select District</option>';
        blockSelect.innerHTML = '<option value="">Select Block</option>';
        sectorSelect.innerHTML = '<option value="">Select Sector</option>';
        mlaAreaSelect.innerHTML = '<option value="">Select MLA Constituency</option>';
        
        if (stateId) {
            fetch(`<?= site_url('auth/get-districts/') ?>${stateId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data) {
                        data.data.forEach(district => {
                            const option = document.createElement('option');
                            option.value = district.id;
                            option.textContent = district.name;
                            committeeDistrictSelect.appendChild(option);
                        });
                    }
                });
        }
    });

    // Appointment District change
    committeeDistrictSelect.addEventListener('change', function() {
        const districtId = this.value;
        blockSelect.innerHTML = '<option value="">Select Block</option>';
        sectorSelect.innerHTML = '<option value="">Select Sector</option>';
        mlaAreaSelect.innerHTML = '<option value="">Select MLA Constituency</option>';
        
        if (districtId) {
            // Load blocks
            fetch(`<?= site_url('auth/get-blocks/') ?>${districtId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data) {
                        data.data.forEach(block => {
                            const option = document.createElement('option');
                            option.value = block.id;
                            option.textContent = block.name;
                            blockSelect.appendChild(option);
                        });
                    }
                });
            
            // Load MLA areas
            fetch(`<?= site_url('auth/get-mla-areas/') ?>${districtId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data) {
                        data.data.forEach(mlaArea => {
                            const option = document.createElement('option');
                            option.value = mlaArea.id;
                            option.textContent = mlaArea.name;
                            mlaAreaSelect.appendChild(option);
                        });
                    }
                });
        }
    });

    // Block change - load sectors
    blockSelect.addEventListener('change', function() {
        const blockId = this.value;
        sectorSelect.innerHTML = '<option value="">Select Sector</option>';
        
        if (blockId) {
            fetch(`<?= site_url('admin/locations/get_sectors_by_block/') ?>${blockId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.sectors) {
                        data.sectors.forEach(sector => {
                            const option = document.createElement('option');
                            option.value = sector.id;
                            option.textContent = sector.name;
                            sectorSelect.appendChild(option);
                        });
                    }
                });
        }
    });

    // Level change - load posts
    const levelSelect = document.getElementById('level_id');
    const postSelect = document.getElementById('post_id');

    levelSelect.addEventListener('change', function() {
        const levelId = this.value;
        postSelect.innerHTML = '<option value="">Select Post</option>';
        
        if (levelId) {
            // Find the level name from the selected option
            const selectedOption = this.options[this.selectedIndex];
            const levelName = selectedOption.textContent;
            
            fetch(`<?= site_url('admin/posts/by-level/') ?>${encodeURIComponent(levelName)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.posts && data.posts.length > 0) {
                        data.posts.forEach(post => {
                            const option = document.createElement('option');
                            option.value = post.id;
                            option.textContent = post.name;
                            postSelect.appendChild(option);
                        });
                    }
                });
        }
    });

    // Photo preview
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photoPreview');
    const previewImage = document.getElementById('previewImage');
    const currentPhoto = document.getElementById('currentPhoto');

    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    photoPreview.classList.remove('hidden');
                    if (currentPhoto) {
                        currentPhoto.parentElement.classList.add('opacity-50');
                    }
                };
                reader.readAsDataURL(file);
            } else {
                photoPreview.classList.add('hidden');
                if (currentPhoto) {
                    currentPhoto.parentElement.classList.remove('opacity-50');
                }
            }
        });
    }
</script>
<?= $this->endSection() ?>