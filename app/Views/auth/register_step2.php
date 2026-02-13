<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Register - Step 2: Location & Family<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2 text-sm">
                <span class="text-gray-500">Step 1: Personal</span>
                <span class="text-blue-600 font-semibold">Step 2: Location</span>
                <span class="text-gray-500">Step 3: Address & Password</span>
                <span class="text-gray-500">Step 4: Apply for Post</span>
                <span class="text-gray-500">Step 5: Review Application</span>
            </div>
            <div class="h-2.5 bg-gray-200 rounded-full">
                <div class="h-2.5 bg-blue-600 rounded-full" style="width: 40%;"></div>
            </div>
        </div>

        <h1 class="text-2xl font-bold mb-6 text-center">Step 2: Location & Family Details</h1>
        
        <?php if (session()->has('errors')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Please correct the following errors:</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form id="registrationFormStep2" action="<?= site_url('auth/process-step2') ?>" method="POST">
            <?= csrf_field() ?>
            
            <h2 class="text-xl font-semibold mb-4 border-b pb-2">Location Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                    <input type="text" id="country" value="India" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-500">
                </div>
                 <div>
                    <label for="state_id" class="block text-sm font-medium text-gray-700 mb-1">State <span class="text-red-500">*</span>
                        <span id="state_loader" class="ml-2 hidden">
                            <svg class="animate-spin h-4 w-4 text-blue-500 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </span>
                    </label>
                    <select name="state_id" id="state_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Loading States...</option>
                    </select>
                </div>
                <div>
                    <label for="district_id" class="block text-sm font-medium text-gray-700 mb-1">District <span class="text-red-500">*</span>
                        <span id="district_loader" class="ml-2 hidden">
                            <svg class="animate-spin h-4 w-4 text-blue-500 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </span>
                    </label>
                    <select name="district_id" id="district_id" required disabled
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-gray-100">
                        <option value="">-- Select State First --</option>
                    </select>
                </div>
                <div>
                    <label for="block_id" class="block text-sm font-medium text-gray-700 mb-1">Block <span class="text-red-500">*</span>
                        <span id="block_loader" class="ml-2 hidden">
                            <svg class="animate-spin h-4 w-4 text-blue-500 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </span>
                    </label>
                    <select name="block_id" id="block_id" required disabled
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-gray-100">
                        <option value="">-- Select District First --</option>
                    </select>
                </div> 
                <div>
                    <label for="mla_area_id" class="block text-sm font-medium text-gray-700 mb-1">MLA Area <span class="text-red-500">*</span>
                        <span id="mla_area_loader" class="ml-2 hidden">
                            <svg class="animate-spin h-4 w-4 text-blue-500 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </span>
                    </label>
                    <select name="mla_area_id" id="mla_area_id" required disabled
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-gray-100">
                        <option value="">-- Select District First --</option>
                    </select>
                </div>
                

                <!-- Auto-filled LS fields -->
                <div>
                    <label for="ls_text" class="block text-sm font-medium text-gray-700 mb-1">Your Loksabha Constituency<span class="text-red-500">*</span>
                        <span id="ls_text_loader" class="ml-2 hidden"><svg class="animate-spin h-3 w-3 text-blue-500 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></span>
                    </label>
                    <input type="text" id="ls" value="<?= esc($savedData['ls_name'] ?? '', 'attr') ?>" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-500">
                    <input type="hidden" name="ls_id" id="ls_id" value="<?= esc($savedData['ls_id'] ?? '', 'attr') ?>">
                </div>
                <div>
                    <label for="2ls_text" class="block text-sm font-medium text-gray-700 mb-1">Your Parivar Sabha Constituency <span class="text-red-500">*</span>
                        <span id="2ls_text_loader" class="ml-2 hidden"><svg class="animate-spin h-3 w-3 text-blue-500 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></span>
                    </label>
                    <input type="text" id="2ls" value="<?= esc($savedData['2ls_name'] ?? '', 'attr') ?>" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-500">
                    <input type="hidden" name="2ls_id" id="2ls_id" value="<?= esc($savedData['2ls_id'] ?? '', 'attr') ?>">
                </div>
                <div>
                    <label for="3ls_text" class="block text-sm font-medium text-gray-700 mb-1">Your Gram Sabha Constituency <span class="text-red-500">*</span>
                        <span id="3ls_text_loader" class="ml-2 hidden"><svg class="animate-spin h-3 w-3 text-blue-500 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></span>
                    </label>
                    <input type="text" id="3ls" value="<?= esc($savedData['3ls_name'] ?? '', 'attr') ?>" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-500">
                    <input type="hidden" name="3ls_id" id="3ls_id" value="<?= esc($savedData['3ls_id'] ?? '', 'attr') ?>">
                </div>
                <div>
                    <label for="4ls_text" class="block text-sm font-medium text-gray-700 mb-1">Your Jansabha Constituency <span class="text-red-500">*</span>
                        <span id="4ls_text_loader" class="ml-2 hidden"><svg class="animate-spin h-3 w-3 text-blue-500 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></span>
                    </label>
                    <input type="text" id="4ls" value="<?= esc($savedData['4ls_name'] ?? '', 'attr') ?>" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-500">
                    <input type="hidden" name="4ls_id" id="4ls_id" value="<?= esc($savedData['4ls_id'] ?? '', 'attr') ?>">
                </div>
            </div>

            <h2 class="text-xl font-semibold mb-4 mt-8 border-b pb-2">Family Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                <div>
                    <label for="father_name" class="block text-sm font-medium text-gray-700 mb-1">Father's/Husband's Name <span class="text-red-500">*</span></label>
                    <input type="text" name="father_name" id="father_name" value="<?= esc($savedData['father_name'] ?? old('father_name'), 'attr') ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="mother_name" class="block text-sm font-medium text-gray-700 mb-1">Mother's/Wife's Name <span class="text-red-500">*</span></label>
                    <input type="text" name="mother_name" id="mother_name" value="<?= esc($savedData['mother_name'] ?? old('mother_name'), 'attr') ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <h2 class="text-xl font-semibold mb-4 mt-8 border-b pb-2">Identification</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                <div>
                    <label for="aadhaar_number" class="block text-sm font-medium text-gray-700 mb-1">
                        Government-issued Aadhaar Card No. <span class="text-red-500">*</span>
                        <span class="text-gray-400" title="Enter your 12-digit Aadhaar number.">(?)</span>
                    </label>
                    <input type="text" name="aadhaar_number" id="aadhaar_number" value="<?= esc($savedData['aadhaar_number'] ?? old('aadhaar_number'), 'attr') ?>" required pattern="\d{12}" title="Enter 12 digit Aadhaar number"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex justify-between mt-8">
                <button type="button" onclick="window.location.href='<?= site_url('auth/register?step=1') ?>'" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-md">&larr; Previous</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md">Next: Address & Password &rarr;</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stateSelect = document.getElementById('state_id');
    const districtSelect = document.getElementById('district_id');
    const mlaAreaSelect = document.getElementById('mla_area_id');
    const blockSelect = document.getElementById('block_id');

    const stateLoader = document.getElementById('state_loader');
    const districtLoader = document.getElementById('district_loader');
    const mlaAreaLoader = document.getElementById('mla_area_loader');
    const blockLoader = document.getElementById('block_loader');

    const lsInput = document.getElementById('ls');
    const twoLsInput = document.getElementById('2ls');
    const threeLsInput = document.getElementById('3ls');
    const fourLsInput = document.getElementById('4ls');
    const lsTextLoader = document.getElementById('ls_text_loader');
    const twoLsTextLoader = document.getElementById('2ls_text_loader');
    const threeLsTextLoader = document.getElementById('3ls_text_loader');
    const fourLsTextLoader = document.getElementById('4ls_text_loader');

    // Hidden ID inputs for LS hierarchy
    const lsIdInput = document.getElementById('ls_id');
    const twoLsIdInput = document.getElementById('2ls_id');
    const threeLsIdInput = document.getElementById('3ls_id');
    const fourLsIdInput = document.getElementById('4ls_id');

    const savedStateId = "<?= esc($savedData['state_id'] ?? old('state_id'), 'js') ?>";
    const savedDistrictId = "<?= esc($savedData['district_id'] ?? old('district_id'), 'js') ?>";
    const savedMlaAreaId = "<?= esc($savedData['mla_area_id'] ?? old('mla_area_id'), 'js') ?>";
    const savedBlockId = "<?= esc($savedData['block_id'] ?? old('block_id'), 'js') ?>";

    // function showLoader(loader) { if (loader) loader.classList.remove('hidden'); } // Commented out
    // function hideLoader(loader) { if (loader) loader.classList.add('hidden'); } // Commented out
    
    function setLsFields(data = {}) {
        if (lsInput) lsInput.value = data.ls_name || '';
        if (twoLsInput) twoLsInput.value = data.two_ls_name || '';
        if (threeLsInput) threeLsInput.value = data.three_ls_name || '';
        if (fourLsInput) fourLsInput.value = data.four_ls_name || '';

        if (lsIdInput) lsIdInput.value = data.ls_id || '';
        if (twoLsIdInput) twoLsIdInput.value = data.two_ls_id || '';
        if (threeLsIdInput) threeLsIdInput.value = data.three_ls_id || '';
        if (fourLsIdInput) fourLsIdInput.value = data.four_ls_id || '';
    }

    function populateSelect(select, data, placeholder, selectedValue = null, isError = false) {
        if (!select) return;
        select.innerHTML = `<option value="">${placeholder}</option>`;
        if (!isError && data && data.length > 0) { // Only populate if not an error and data exists
            data.forEach(item => {
                if (item && typeof item['id'] !== 'undefined' && typeof item['name'] !== 'undefined') { // Changed to array access
                    const option = new Option(item['name'], item['id']); // Changed to array access
                    select.add(option);
                }
            });
            select.disabled = false;
            if (selectedValue) {
                const optionExists = Array.from(select.options).some(opt => opt.value === selectedValue);
                if (optionExists) {
                    select.value = selectedValue;
                }
            }
        } else if (!isError && (!data || data.length === 0)) { // No data, not an error
            select.innerHTML = `<option value="">No data available</option>`;
            select.disabled = true;
        } else { // Is an error, or other unhandled case, placeholder (which should be the error message) is already set
            select.disabled = true;
        }
    }

    async function fetchStates() {
        // showLoader(stateLoader); // Commented out
        stateSelect.disabled = true;
        districtSelect.disabled = true;
        mlaAreaSelect.disabled = true;
        blockSelect.disabled = true;
        setLsFields(); 
        try {
            const response = await fetch("<?= site_url('auth/get-states') ?>");
            if (!response.ok) {
                let serverErrorMsg = `HTTP error! Status: ${response.status}`;
                try { const errorData = await response.json(); if (errorData && errorData.error) serverErrorMsg = errorData.error; } catch (e) {}
                throw new Error(serverErrorMsg);
            }
            const responseData = await response.json();
            if (responseData && responseData.success && Array.isArray(responseData.data)) {
                populateSelect(stateSelect, responseData.data, '-- Select State --', savedStateId);
                if (savedStateId && stateSelect.value === savedStateId) {
                   fetchDistricts(savedStateId, true);
                }
            } else {
                console.error('Invalid data format received for states:', responseData);
                throw new Error('Received invalid format for states data.');
            }
        } catch (error) {
            console.error('Error fetching states:', error.message);
            populateSelect(stateSelect, [], 'Error loading states', null, true);
        } finally {
            // hideLoader(stateLoader); // Commented out
        }
    } 
    fetchStates();
     
    async function fetchDistricts(stateId, initialLoad = false) {
        if (!stateId) {
            populateSelect(districtSelect, [], '-- Select State First --');
            districtSelect.disabled = true;
            populateSelect(mlaAreaSelect, [], '-- Select District First --');
            mlaAreaSelect.disabled = true;
            populateSelect(blockSelect, [], '-- Select District First --');
            blockSelect.disabled = true;
            setLsFields();
            return;
        }
        // showLoader(districtLoader); // Commented out
        districtSelect.disabled = true;
        mlaAreaSelect.disabled = true;
        blockSelect.disabled = true;
        setLsFields();
        try {
            const response = await fetch(`<?= site_url('auth/get-districts') ?>/${stateId}`);
            if (!response.ok) {
                let serverErrorMsg = `HTTP error! Status: ${response.status}`;
                try { const errorData = await response.json(); if (errorData && errorData.error) serverErrorMsg = errorData.error; } catch (e) {}
                throw new Error(serverErrorMsg);
            }
            const responseData = await response.json();
            if (responseData && responseData.success && Array.isArray(responseData.data)) { // Check success and data key
                populateSelect(districtSelect, responseData.data, '-- Select District --', savedDistrictId);
                if (initialLoad && savedDistrictId && districtSelect.value === savedDistrictId) {
                    fetchMlaAreas(savedDistrictId, true); 
                    fetchBlocks(savedDistrictId, true); 
                }
            } else {
                console.error('Invalid data format received for districts:', responseData);
                throw new Error('Received invalid format for districts data.');
            }
        } catch (error) {
            console.error('Error fetching districts:', error.message);
            populateSelect(districtSelect, [], 'Error loading districts', null, true);
        } finally {
            // hideLoader(districtLoader); // Commented out
        }
    }

    stateSelect.addEventListener('change', function() {
        fetchDistricts(this.value);
        populateSelect(mlaAreaSelect, [], '-- Select District First --');
        mlaAreaSelect.disabled = true;
        populateSelect(blockSelect, [], '-- Select District First --');
        blockSelect.disabled = true;
        setLsFields();
    });

    async function fetchMlaAreas(districtId, initialLoad = false) {
        if (!districtId) {
            populateSelect(mlaAreaSelect, [], '-- Select District First --');
            mlaAreaSelect.disabled = true;
            setLsFields(); 
            return;
        }
        // showLoader(mlaAreaLoader); // Commented out
        mlaAreaSelect.disabled = true;
        setLsFields();
        try {
            const response = await fetch(`<?= site_url('auth/get-mla-areas') ?>/${districtId}`);
            if (!response.ok) {
                let serverErrorMsg = `HTTP error! Status: ${response.status}`;
                try { const errorData = await response.json(); if (errorData && errorData.error) serverErrorMsg = errorData.error; } catch (e) {}
                throw new Error(serverErrorMsg);
            }
            const responseData = await response.json(); 
            if (responseData && responseData.success && Array.isArray(responseData.data)) { // Check success and data key
                populateSelect(mlaAreaSelect, responseData.data, '-- Select MLA Area --', savedMlaAreaId);
                if (initialLoad && savedMlaAreaId && mlaAreaSelect.value === savedMlaAreaId) {
                    fetchLsHierarchyForMlaArea(savedMlaAreaId);
                }
            } else {
                console.error('Invalid data format received for MLA areas:', responseData);
                throw new Error('Received invalid format for MLA areas data.');
            }
        } catch (error) {
            console.error('Error fetching MLA areas:', error.message);
            populateSelect(mlaAreaSelect, [], 'Error loading MLA areas', null, true);
        } finally {
            // hideLoader(mlaAreaLoader); // Commented out
        }
    }

    districtSelect.addEventListener('change', function() {
        fetchMlaAreas(this.value);
        fetchBlocks(this.value);
        setLsFields();
    });

    async function fetchBlocks(districtId, initialLoad = false) {
        if (!districtId) {
            populateSelect(blockSelect, [], '-- Select District First --');
            blockSelect.disabled = true;
            return;
        }
        // showLoader(blockLoader); // Commented out
        blockSelect.disabled = true;
        try {
            const response = await fetch(`<?= site_url('auth/get-blocks') ?>/${districtId}`);
            if (!response.ok) {
                let serverErrorMsg = `HTTP error! Status: ${response.status}`;
                try { const errorData = await response.json(); if (errorData && errorData.error) serverErrorMsg = errorData.error; } catch (e) {}
                throw new Error(serverErrorMsg);
            }
            const responseData = await response.json(); 
            if (responseData && responseData.success && Array.isArray(responseData.data)) { // Check success and data key
                populateSelect(blockSelect, responseData.data, '-- Select Block --', savedBlockId);
                // Note: Block selection does not trigger LS data fetching in this version.
            } else {
                console.error('Invalid data format received for blocks:', responseData);
                throw new Error('Received invalid format for blocks data.');
            }
        } catch (error) {
            console.error('Error fetching blocks:', error.message);
            populateSelect(blockSelect, [], 'Error loading blocks', null, true);
        } finally {
            // hideLoader(blockLoader); // Commented out
        }
    }

    async function fetchLsHierarchyForMlaArea(mlaAreaId) {
        if (!mlaAreaId) {
            setLsFields();
            return;
        }
        // showLoader(lsTextLoader); // Commented out
        // showLoader(twoLsTextLoader); // Commented out
        // showLoader(threeLsTextLoader); // Commented out
        // showLoader(fourLsTextLoader); // Commented out
        setLsFields({ ls: 'Loading...', two_ls: 'Loading...', three_ls: 'Loading...', four_ls: 'Loading...' });

        try {
            const response = await fetch(`<?= site_url('auth/get-ls-hierarchy-by-mla-area') ?>/${mlaAreaId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (!response.ok) {
                let serverErrorMsg = `HTTP error! Status: ${response.status}`;
                try { const errorData = await response.json(); if (errorData && errorData.error) serverErrorMsg = errorData.error; } catch (e) {}
                throw new Error(serverErrorMsg);
            }
            const responseData = await response.json();
            if (responseData && responseData.success && typeof responseData.data === 'object' && responseData.data !== null) {
                setLsFields(responseData.data);
            } else {
                console.warn('No LS hierarchy data found or invalid format for MLA Area ID:', mlaAreaId, responseData);
                setLsFields();
            }
        } catch (error) {
            console.error('Error fetching LS hierarchy for MLA Area:', error.message);
            setLsFields();
        } finally {
            // hideLoader(lsTextLoader); // Commented out
            // hideLoader(twoLsTextLoader); // Commented out
            // hideLoader(threeLsTextLoader); // Commented out
            // hideLoader(fourLsTextLoader); // Commented out
        }
    }

    mlaAreaSelect.addEventListener('change', function() {
        // Only fetch if a valid MLA area is selected
        if (this.value) {
            fetchLsHierarchyForMlaArea(this.value);
        } else {
            setLsFields(); // Clear fields if "-- Select MLA Area --" is chosen
        }
    });

    // Block select does not trigger LS data in this version.
    // If it should clear LS data, you can add:
    // blockSelect.addEventListener('change', function() {
    //     setLsFields(); 
    // });
});
</script>
<?= $this->endSection() ?>
