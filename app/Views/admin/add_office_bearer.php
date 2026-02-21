<?= $this->extend('layouts/admin_modern') ?>

<?= $this->section('title') ?>Add Office Bearer<?= $this->endSection() ?>

<?= $this->section('headerTitle') ?>Add Office Bearer<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    @media (min-width: 768px) {
        .md\:grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .md\:col-span-3 {
            grid-column: span 3 / span 3;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- <div class="bg-red-500 rounded-xl shadow-md mt-8 overflow-hidden w-full"> -->
    <div class="bg-gray-50 px-6 py-4 border-b">
        <h4 class="text-lg font-bold text-gray-800">Add New Office Bearer</h4>
        <p class="text-sm text-gray-600 mt-1">Enter details of appointed office bearer</p>
    </div>
    <div class="p-6">
        <form id="officeBearerForm" class="space-y-6">
            <!-- Personal Info Section -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                <h5 class="text-md font-semibold text-blue-900 mb-4">
                    <i class="fas fa-user mr-2"></i>Personal Information
                </h5>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="first_name" name="first_name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <span class="text-red-500 text-sm hidden" id="first-name-error"></span>
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Last Name <span class="text-gray-500 text-xs">(Optional)</span>
                        </label>
                        <input type="text" id="last_name" name="last_name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <span class="text-red-500 text-sm hidden" id="last-name-error"></span>
                    </div>

                    <div>
                        <label for="father_husband_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Father/Husband's Name <span class="text-gray-500 text-xs">(Optional)</span>
                        </label>
                        <input type="text" id="father_husband_name" name="father_husband_name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <span class="text-red-500 text-sm hidden" id="father_husband_name-error"></span>
                    </div>

                    <div>
                        <label for="mobile" class="block text-sm font-medium text-gray-700 mb-2">
                            Mobile/WhatsApp No. <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="mobile" name="mobile" required pattern="[0-9]{10}"
                            placeholder="10-digit mobile number"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <span class="text-red-500 text-sm hidden" id="mobile-error"></span>
                    </div>

                    <div>
                        <label for="state_id" class="block text-sm font-medium text-gray-700 mb-2">
                            State <span class="text-red-500">*</span>
                        </label>
                        <select id="state_id" name="state_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select State</option>
                            <?php foreach ($all_states as $state): ?>
                                <option value="<?= $state['id'] ?>"><?= esc($state['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="text-red-500 text-sm hidden" id="state_id-error"></span>
                    </div>

                    <div>
                        <label for="district_id" class="block text-sm font-medium text-gray-700 mb-2">
                            District <span class="text-red-500">*</span>
                        </label>
                        <select id="district_id" name="district_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select District</option>
                        </select>
                        <span class="text-red-500 text-sm hidden" id="district_id-error"></span>
                    </div>

                    <div class="md:col-span-3">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Address <span class="text-gray-500 text-xs">(Optional)</span>
                        </label>
                        <textarea id="address" name="address" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        <span class="text-red-500 text-sm hidden" id="address-error"></span>
                    </div>
                </div>
            </div>

            <!-- Appointment Details Section -->
            <div class="bg-green-50 border-l-4 border-green-500 p-4">
                <h5 class="text-md font-semibold text-green-900 mb-4">
                    <i class="fas fa-briefcase mr-2"></i>Appointment Details
                </h5>
                <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Organ Dropdown -->
                    <div>
                        <label for="organ_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Organ <span class="text-red-500">*</span>
                        </label>
                        <select id="organ_id" name="organ_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Select Organ</option>
                            <?php foreach ($organs as $organ): ?>
                                <option value="<?= $organ['id'] ?>"><?= esc($organ['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="text-red-500 text-sm hidden" id="organ_id-error"></span>
                    </div>

                    <!-- Front Dropdown (Hidden initially) -->
                    <div id="front_wrapper" class="hidden">
                        <label for="front_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Front <span class="text-red-500">*</span>
                        </label>
                        <select id="front_id" name="front_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Select Front</option>
                            <?php foreach ($fronts as $front): ?>
                                <option value="<?= $front['id'] ?>"><?= esc($front['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="text-red-500 text-sm hidden" id="front_id-error"></span>
                    </div>

                    <!-- Appointed Level (Hidden initially) -->
                    <div id="appointed_level_wrapper" class="hidden">
                        <label for="appointed_level" class="block text-sm font-medium text-gray-700 mb-2">
                            Appointed Level <span class="text-red-500">*</span>
                        </label>
                        <select id="appointed_level" name="appointed_level" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Select Level</option>
                            <option value="Village">Village/Ward</option>
                            <option value="Sector">Sector (Panchayat)</option>
                            <option value="Circle">Circle</option>
                            <option value="Block">Block</option>
                            <option value="District">District</option>
                            <option value="MLA Constituency">MLA Constituency</option>
                            <option value="MP Constituency">MP Constituency</option>
                            <option value="State">State</option>
                        </select>
                        <span class="text-red-500 text-sm hidden" id="appointed_level-error"></span>
                    </div>
                </div>

                <!-- Appointed Committee Cascading Dropdowns -->
                <div class="mt-4">
                    <h6 class="text-sm font-semibold text-gray-700 mb-3">Appointed Committee</h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Committee State (for all except MP Constituency) -->
                        <div id="committee_state_wrapper" class="hidden">
                            <label for="committee_state_id" class="block text-sm font-medium text-gray-700 mb-2">
                                State <span class="text-red-500">*</span>
                            </label>
                            <select id="committee_state_id" name="committee_state_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select State</option>
                                <?php foreach ($all_states as $state): ?>
                                    <option value="<?= $state['id'] ?>"><?= esc($state['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Committee District -->
                        <div id="committee_district_wrapper" class="hidden">
                            <label for="committee_district_id" class="block text-sm font-medium text-gray-700 mb-2">
                                District <span class="text-red-500">*</span>
                            </label>
                            <select id="committee_district_id" name="committee_district_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select District</option>
                            </select>
                        </div>

                        <!-- Block -->
                        <div id="block_wrapper" class="hidden">
                            <label for="block_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Block <span class="text-red-500">*</span>
                            </label>
                            <select id="block_id" name="block_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select Block</option>
                            </select>
                        </div>
                        
                        <!-- Circle (Hidden initially) -->
                        <div id="circle_wrapper" class="hidden">
                            <label for="circle_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Circle <span class="text-red-500">*</span>
                            </label>
                            <select id="circle_id" name="circle_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select Circle</option>
                            </select>
                        </div>

                        <!-- Sector -->
                        <div id="sector_wrapper" class="hidden">
                            <label for="sector_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Sector (Panchayat) <span class="text-red-500">*</span>
                            </label>
                            <select id="sector_id" name="sector_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select Sector</option>
                            </select>
                        </div>

                        <!-- Village (Hidden initially) -->
                        <div id="village_wrapper" class="hidden">
                            <label for="village_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Village/Ward <span class="text-red-500">*</span>
                            </label>
                            <select id="village_id" name="village_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select Village</option>
                            </select>
                        </div>

                        <!-- MLA Area -->
                        <div id="mla_area_wrapper" class="hidden">
                            <label for="mla_area_id" class="block text-sm font-medium text-gray-700 mb-2">
                                MLA Constituency <span class="text-red-500">*</span>
                            </label>
                            <select id="mla_area_id" name="mla_area_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select MLA Constituency</option>
                            </select>
                        </div>

                        <!-- 1LS (MP Constituency) -->
                        <div id="ls_wrapper" class="hidden">
                            <label for="ls_id" class="block text-sm font-medium text-gray-700 mb-2">
                                MP Constituency (1LS) <span class="text-red-500">*</span>
                            </label>
                            <select id="ls_id" name="ls_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select MP Constituency</option>
                            </select>
                        </div>
                    </div>
                </div>

                    <div id="posts-container" class="mt-8 hidden">
                        <h6 class="text-sm font-semibold text-gray-700 mb-3">Post Availability</h6>
                        <div class="overflow-x-auto border rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">Select</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Post Details</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                                    </tr>
                                </thead>
                                <tbody id="posts-table-body" class="bg-white divide-y divide-gray-200">
                                    <!-- Rows will be populated via JS -->
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" id="post_id" name="post_id" required>
                        <span class="text-red-500 text-sm hidden" id="post_id-error"></span>
                    </div>

                </div>
            </div>

            <!-- Alert Messages -->
            <div id="alert-container"></div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-4">
                <a href="<?= site_url('admin/usersList') ?>" 
                    class="px-6 py-3 bg-gray-500 text-white font-medium rounded-lg shadow hover:bg-gray-600 transition">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" id="submitBtn" disabled
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                    <i class="fas fa-save mr-2"></i>Save Office Bearer
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('officeBearerForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Organ & Front
    const organSelect = document.getElementById('organ_id');
    const frontSelect = document.getElementById('front_id');
    const frontWrapper = document.getElementById('front_wrapper');
    const appointedLevelWrapper = document.getElementById('appointed_level_wrapper');

    const appointedLevelSelect = document.getElementById('appointed_level');
    const postInput = document.getElementById('post_id');
    const postsContainer = document.getElementById('posts-container');
    const postsTableBody = document.getElementById('posts-table-body');
    
    // Personal info dropdowns
    const stateSelect = document.getElementById('state_id');
    const districtSelect = document.getElementById('district_id');
    
    // Committee dropdowns
    const committeeStateSelect = document.getElementById('committee_state_id');
    const committeeDistrictSelect = document.getElementById('committee_district_id');
    const blockSelect = document.getElementById('block_id');
    const circleSelect = document.getElementById('circle_id');
    const sectorSelect = document.getElementById('sector_id');
    const villageSelect = document.getElementById('village_id');
    const mlaAreaSelect = document.getElementById('mla_area_id');
    const lsSelect = document.getElementById('ls_id');
    
    // Wrappers
    const committeeStateWrapper = document.getElementById('committee_state_wrapper');
    const committeeDistrictWrapper = document.getElementById('committee_district_wrapper');
    const blockWrapper = document.getElementById('block_wrapper');
    const circleWrapper = document.getElementById('circle_wrapper');
    const sectorWrapper = document.getElementById('sector_wrapper');
    const villageWrapper = document.getElementById('village_wrapper');
    const mlaAreaWrapper = document.getElementById('mla_area_wrapper');
    const lsWrapper = document.getElementById('ls_wrapper');


    // Load districts when state changes (Personal Info)
    stateSelect.addEventListener('change', function() {
        const stateId = this.value;
        districtSelect.innerHTML = '<option value="">Select District</option>';
        
        if (stateId) {
            districtSelect.innerHTML = '<option value="">Loading...</option>';
            
            fetch(`<?= site_url('auth/get-districts/') ?>${stateId}`)
                .then(response => response.json())
                .then(data => {
                    districtSelect.innerHTML = '<option value="">Select District</option>';
                    if (data.success && data.data && data.data.length > 0) {
                        data.data.forEach(district => {
                            const option = document.createElement('option');
                            option.value = district.id;
                            option.textContent = district.name;
                            districtSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching districts:', error);
                    districtSelect.innerHTML = '<option value="">Error loading districts</option>';
                });
        }
    });

    // Handle Organ Change
    organSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const organName = selectedOption.text.trim().toLowerCase();

        // Reset sub-components
        frontSelect.value = '';
        frontWrapper.classList.add('hidden');
        appointedLevelWrapper.classList.add('hidden');
        appointedLevelSelect.value = '';
        
        hideAllCommitteeDropdowns();
        resetCommitteeDropdowns();
        resetPostsTable();

        if (organName === 'front') {
            frontWrapper.classList.remove('hidden');
        } else if (this.value) { 
            // Main Committee or others
            appointedLevelWrapper.classList.remove('hidden');
        }
    });

    // Handle Front Change
    frontSelect.addEventListener('change', function() {
        if (this.value) {
            appointedLevelWrapper.classList.remove('hidden');
        } else {
            appointedLevelWrapper.classList.add('hidden');
            appointedLevelSelect.value = '';
            hideAllCommitteeDropdowns();
            resetCommitteeDropdowns();
            resetPostsTable();
        }
    });

    // Appointed Level Change
    appointedLevelSelect.addEventListener('change', function() {
        const level = this.value;
        
        // Reset all location dropdowns
        resetLocationDropdowns();
        postsContainer.classList.add('hidden');
        postInput.value = '';
        checkSubmitButton();
        
        // Hide all wrappers first
        hideAllCommitteeDropdowns();

        if (level) {
            showCommitteeDropdownsForLevel(level);
            if (level === 'Circle') {
                loadCircles();
            }
        }
    });

    function loadCircles() {
        circleSelect.innerHTML = '<option value="">Loading...</option>';
        fetch('<?= site_url('admin/locations/get_all_circles') ?>')
            .then(response => response.json())
            .then(data => {
                circleSelect.innerHTML = '<option value="">Select Circle</option>';
                if (data.success && data.circles) {
                    data.circles.forEach(circle => {
                        const option = document.createElement('option');
                        option.value = circle.id;
                        option.textContent = circle.name;
                        circleSelect.appendChild(option);
                    });
                }
            });
    }

    function hideAllCommitteeDropdowns() {
        committeeStateWrapper.classList.add('hidden');
        committeeDistrictWrapper.classList.add('hidden');
        blockWrapper.classList.add('hidden');
        circleWrapper.classList.add('hidden');
        sectorWrapper.classList.add('hidden');
        villageWrapper.classList.add('hidden');
        mlaAreaWrapper.classList.add('hidden');
        lsWrapper.classList.add('hidden');
    }

    function resetCommitteeDropdowns() {
        committeeStateSelect.value = ''; 
        committeeDistrictSelect.innerHTML = '<option value="">Select District</option>';
        blockSelect.innerHTML = '<option value="">Select Block</option>';
        circleSelect.innerHTML = '<option value="">Select Circle</option>';
        sectorSelect.innerHTML = '<option value="">Select Sector</option>';
        villageSelect.innerHTML = '<option value="">Select Village</option>';
        mlaAreaSelect.innerHTML = '<option value="">Select MLA Constituency</option>';
        lsSelect.innerHTML = '<option value="">Select MP Constituency</option>';
    }
    
    function resetLocationDropdowns() {
        resetCommitteeDropdowns();
    }

    function showCommitteeDropdownsForLevel(level) {
        switch(level) {
            case 'Village':
                committeeStateWrapper.classList.remove('hidden');
                committeeDistrictWrapper.classList.remove('hidden');
                blockWrapper.classList.remove('hidden');
                sectorWrapper.classList.remove('hidden');
                villageWrapper.classList.remove('hidden');
                break;
            case 'Sector':
                committeeStateWrapper.classList.remove('hidden');
                committeeDistrictWrapper.classList.remove('hidden');
                blockWrapper.classList.remove('hidden');
                sectorWrapper.classList.remove('hidden');
                break;
            case 'Circle':
                committeeStateWrapper.classList.remove('hidden');
                committeeDistrictWrapper.classList.remove('hidden');
                blockWrapper.classList.remove('hidden');
                circleWrapper.classList.remove('hidden');
                break;
            case 'Block':
                committeeStateWrapper.classList.remove('hidden');
                committeeDistrictWrapper.classList.remove('hidden');
                blockWrapper.classList.remove('hidden');
                break;
            case 'District':
                committeeStateWrapper.classList.remove('hidden');
                committeeDistrictWrapper.classList.remove('hidden');
                break;
            case 'MLA Constituency':
                committeeStateWrapper.classList.remove('hidden');
                committeeDistrictWrapper.classList.remove('hidden');
                mlaAreaWrapper.classList.remove('hidden');
                break;
            case 'MP Constituency':
                committeeStateWrapper.classList.remove('hidden');
                lsWrapper.classList.remove('hidden');
                break;
            case 'State':
                committeeStateWrapper.classList.remove('hidden');
                break;
        }
    }

    function resetPostsTable() {
        postsContainer.classList.add('hidden');
        postsTableBody.innerHTML = '';
        postInput.value = '';
    }

    // Load Posts Table
    function loadPostsStructure() {
        const level = appointedLevelSelect.value;
        if (!level) return;

        // Gather location data
        const params = new URLSearchParams({
            level: level,
            state_id: committeeStateSelect.value,
            district_id: committeeDistrictSelect.value,
            block_id: blockSelect.value,
            sector_id: sectorSelect.value,
            village_id: villageSelect.value,
            circle_id: circleSelect.value,
            mla_area_id: mlaAreaSelect.value,
            ls_id: lsSelect.value,
            organ_id: organSelect.value,
            front_id: frontSelect.value
        });

        // Only fetch if required fields for the level are filled
        if (!isLevelDataComplete(level)) {
            resetPostsTable();
            return;
        }

        postsContainer.classList.remove('hidden');
        postsTableBody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Loading posts availability...</td></tr>';

        fetch(`<?= site_url('admin/posts/availability') ?>?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    renderPostsTable(data.data);
                } else {
                    postsTableBody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-red-500">Failed to load posts.</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                postsTableBody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-red-500">Error loading data.</td></tr>';
            });
    }

    function renderPostsTable(posts) {
        postsTableBody.innerHTML = '';
        if (posts.length === 0) {
             postsTableBody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No posts found for this level.</td></tr>';
             return;
        }

        posts.forEach(post => {
            const isOccupied = !!post.occupied_by;
            const rowClass = isOccupied ? 'bg-red-50' : 'hover:bg-gray-50';
            const statusHtml = isOccupied 
                ? `<div><p class="font-medium text-gray-900">${post.occupied_by.name}</p><p class="text-xs text-gray-500">${post.occupied_by.mobile}</p></div>`
                : '<span class="text-green-600 italic">No Appointment</span>';
            
            const photoHtml = isOccupied && post.occupied_by.photo 
                ? `<img src="${post.occupied_by.photo}" class="h-10 w-10 rounded-full object-cover">`
                : '<span class="text-xs text-gray-400">No Image</span>';

            const radioHtml = `<input type="radio" name="post_selection" value="${post.id}" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" onchange="document.getElementById('post_id').value = this.value; checkSubmitButton();">`;

            const row = `
                <tr class="${rowClass} transition">
                    <td class="px-6 py-4 whitespace-nowrap">${radioHtml}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${post.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">${statusHtml}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${photoHtml}</td>
                </tr>
            `;
            postsTableBody.insertAdjacentHTML('beforeend', row);
        });
    }

    function isLevelDataComplete(level) {
        switch(level) {
            case 'Village': return villageSelect.value !== '';
            case 'Sector': return sectorSelect.value !== '';
            case 'Circle': return circleSelect.value !== '';
            case 'Block': return blockSelect.value !== '';
            case 'District': return committeeDistrictSelect.value !== '';
            case 'MLA Constituency': return mlaAreaSelect.value !== '';
            case 'MP Constituency': return lsSelect.value !== '';
            case 'State': return committeeStateSelect.value !== '';
            default: return false;
        }
    }


    // Committee State change
    committeeStateSelect.addEventListener('change', function() {
        const stateId = this.value;
        const level = appointedLevelSelect.value;
    
        // Reset children
        committeeDistrictSelect.innerHTML = '<option value="">Select District</option>';
        blockSelect.innerHTML = '<option value="">Select Block</option>';
        
        // Don't reset Circles if they are fixed, but if State changes, maybe we should? 
        // Fixed circles are global, so we don't strictly need to reset, but resetting is cleaner for UX.
        // If we reset, we must re-load if Level is Circle.
        // But resetCommitteeDropdowns() is called here implicitly via manual innerHTML sets? No, explicitly below.
        
        if (level !== 'Circle') {
            circleSelect.innerHTML = '<option value="">Select Circle</option>';
        }
        
        sectorSelect.innerHTML = '<option value="">Select Sector</option>';
        villageSelect.innerHTML = '<option value="">Select Village</option>';
        mlaAreaSelect.innerHTML = '<option value="">Select MLA Constituency</option>';
        lsSelect.innerHTML = '<option value="">Select MP Constituency</option>';
        
        committeeDistrictWrapper.classList.add('hidden');
        lsWrapper.classList.add('hidden');
        mlaAreaWrapper.classList.add('hidden'); // Ensure MLA is hidden if state changes
        
        resetPostsTable();
        
        if (stateId) {
             if (level === 'State') {
                 loadPostsStructure();
             } else if (level === 'MP Constituency') {
                 lsWrapper.classList.remove('hidden');
                 // Fetch LS...
                 fetch(`<?= site_url('auth/get-ls-areas/') ?>${stateId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data) {
                            data.data.forEach(lsArea => {
                                const option = document.createElement('option');
                                option.value = lsArea.id;
                                option.textContent = lsArea.name;
                                lsSelect.appendChild(option);
                            });
                        }
                    });
             } else {
                 // For District, Block, Circle, Sector, Village, MLA -> Show District
                 committeeDistrictWrapper.classList.remove('hidden');
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
        }
        checkSubmitButton();
    });

    // Committee District change
    committeeDistrictSelect.addEventListener('change', function() {
        const districtId = this.value;
        const level = appointedLevelSelect.value;
        
        blockSelect.innerHTML = '<option value="">Select Block</option>';
        // Don't reset fixed circles
        
        sectorSelect.innerHTML = '<option value="">Select Sector</option>';
        villageSelect.innerHTML = '<option value="">Select Village</option>';
        mlaAreaSelect.innerHTML = '<option value="">Select MLA Constituency</option>';
        
        blockWrapper.classList.add('hidden');
        mlaAreaWrapper.classList.add('hidden');
        
        resetPostsTable();
        
        if (districtId) {
            if (level === 'District') {
                loadPostsStructure();
            } else if (level === 'MLA Constituency') {
                mlaAreaWrapper.classList.remove('hidden');
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
            } else {
                // For Block, Circle, Sector, Village -> Show Block
                blockWrapper.classList.remove('hidden');
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
            }
        }
        checkSubmitButton();
    });

    // Block change
    blockSelect.addEventListener('change', function() {
        const blockId = this.value;
        const level = appointedLevelSelect.value;
        
        // Remove circle reset and fetch logic
        
        sectorSelect.innerHTML = '<option value="">Select Sector</option>';
        villageSelect.innerHTML = '<option value="">Select Village</option>';
        
        // circleWrapper.classList.add('hidden'); // Don't hide circle wrapper on block change if level is circle
        sectorWrapper.classList.add('hidden');
        
        resetPostsTable();

        if (blockId) {
            if (level === 'Block') {
                loadPostsStructure();
            } else if (level === 'Circle') {
                 // Do nothing for circle loading, it's already loaded or loaded by loadCircles().
                 // Just check if we can submit?
                 // But we need to ensure Circles are loaded?
                 if (circleSelect.options.length <= 1) {
                     loadCircles();
                 }
            } else if (level === 'Sector' || level === 'Village') {
                 sectorWrapper.classList.remove('hidden');
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
        }
        checkSubmitButton();
    });

    // Circle Change
    circleSelect.addEventListener('change', function() {
        resetPostsTable();
        loadPostsStructure();
        checkSubmitButton();
    });

    // Sector Change
    sectorSelect.addEventListener('change', function() {
         const sectorId = this.value;
         const level = appointedLevelSelect.value;
         
         villageSelect.innerHTML = '<option value="">Select Village</option>';
         villageWrapper.classList.add('hidden');
         
         resetPostsTable();
         
         if (sectorId) {
             if (level === 'Sector') {
                 loadPostsStructure();
             } else if (level === 'Village') {
                 villageWrapper.classList.remove('hidden');
                 fetch(`<?= site_url('admin/locations/get_villages_by_sector/') ?>${sectorId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.villages) {
                            data.villages.forEach(village => {
                                const option = document.createElement('option');
                                option.value = village.id;
                                option.textContent = village.name;
                                villageSelect.appendChild(option);
                            });
                        }
                    });
             }
         }
         checkSubmitButton();
    });
    
    // Village Change
    villageSelect.addEventListener('change', () => { resetPostsTable(); loadPostsStructure(); checkSubmitButton(); });
    mlaAreaSelect.addEventListener('change', () => { resetPostsTable(); loadPostsStructure(); checkSubmitButton(); });
    lsSelect.addEventListener('change', () => { resetPostsTable(); loadPostsStructure(); checkSubmitButton(); });

    // Expose checkSubmitButton globally since it's called by radio buttons
    window.checkSubmitButton = function() {
        submitBtn.disabled = !postInput.value;
    }


    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        
        fetch('<?= site_url('admin/save-office-bearer') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '<?= site_url('admin/usersList') ?>';
                });
            } else {
                // Clear previous errors
                document.querySelectorAll('.text-red-500.text-sm').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('input, select, textarea').forEach(el => el.classList.remove('border-red-500'));

                let isDuplicate = false;
                if (data.errors) {
                    for (const [field, error] of Object.entries(data.errors)) {
                        const input = document.getElementById(field);
                        const errorSpan = document.getElementById(field + '-error');
                        
                        if (input) input.classList.add('border-red-500');

                        // Check for duplicate condition
                        if (field === 'mobile' && error.includes('already appointed')) {
                            isDuplicate = error; // Store the message for popup
                            continue; // Skip showing inline text for duplicate, as popup handles it
                        }

                        if (errorSpan) {
                            errorSpan.textContent = error;
                            errorSpan.classList.remove('hidden');
                        }
                    }
                }

                if (isDuplicate) {
                    // Show specialized Alert for duplicate
                    Swal.fire({
                        icon: 'warning',
                        title: 'Duplicate Appointment',
                        text: isDuplicate,
                        confirmButtonText: 'OK, I Check',
                        confirmButtonColor: '#2563eb' // Ensure button is visible (Blue-600)
                    });
                } else {
                    // Use generic global message for other errors
                    let errorMessage = data.message || 'Failed to save office bearer';
                    if (data.errors && Object.keys(data.errors).length > 0) {
                        errorMessage = 'Please check the highlighted fields for errors.';
                    }
                    showAlert('error', errorMessage);
                }

                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Save Office Bearer';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to save office bearer. Please try again.',
                confirmButtonColor: '#2563eb'
            });
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Save Office Bearer';
        });
    });

    function showAlert(type, message) {
        // ... (Keep existing showAlert as fallback for non-duplicate errors)
        const alertContainer = document.getElementById('alert-container');
        const alertClass = type === 'success' ? 'bg-green-50 border-green-500 text-green-700' : 'bg-red-50 border-red-500 text-red-700';
        const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        alertContainer.innerHTML = `
            <div class="${alertClass} border-l-4 p-4 rounded">
                <div class="flex items-center">
                    <i class="fas ${iconClass} mr-3"></i>
                    <p>${message}</p>
                </div>
            </div>
        `;
        
        setTimeout(() => {
            alertContainer.innerHTML = '';
        }, 5000);
    }
});
</script>
<?= $this->endSection() ?>
