<?= $this->extend('layouts/admin_modern') ?>

<?= $this->section('title') ?>Manage Locations<?= $this->endSection() ?>

<?= $this->section('headerTitle') ?>Manage Locations<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Location Management Widget -->
<div class="bg-white rounded-xl shadow-md mt-8 overflow-hidden" id="district-data-editor-widget">
    <div class="bg-gray-50 px-6 py-4 border-b">
        <h4 class="text-lg font-bold text-gray-800">Manage Locations</h4>
        <p class="text-sm text-gray-600 mt-1">Add, edit, or delete districts, blocks, and MLA areas.</p>
    </div>
    <div class="p-6">
        <form id="editDistrictDataForm">
            <?= csrf_field() ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="stateSelect" class="block text-sm font-medium text-gray-700 mb-2">Select State</label>
                    <select id="stateSelect" name="state_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">-- Select State --</option>
                        <?php if (!empty($all_states)): ?>
                            <?php foreach ($all_states as $state): ?>
                                <option value="<?= esc($state['id']) ?>"><?= esc($state['name']) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No states found</option>
                        <?php endif; ?>
                    </select>
                    <!-- Add/Edit Districts Button - Below State Dropdown -->
                    <div id="stateActions" class="mt-3" style="display:none;">
                        <button type="button" id="addDistrictBtn" class="w-full px-4 py-2 bg-green-600 text-white font-medium rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition">
                            <i class="fas fa-plus mr-2"></i>Add/Edit Districts
                        </button>
                    </div>
                </div>
                <div>
                    <label for="districtSelect" class="block text-sm font-medium text-gray-700 mb-2">Select District</label>
                    <select id="districtSelect" name="district_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" disabled>
                        <option value="">-- Select State First --</option>
                    </select>
                    <!-- Edit Blocks and Edit MLA Areas Buttons - Below District Dropdown -->
                    <div id="editActions" class="mt-3 flex flex-col gap-2" style="display:none;">
                        <button type="button" id="editBlocksBtn" class="w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition">
                            <i class="fas fa-edit mr-2"></i>Edit Blocks
                        </button>
                        <button type="button" id="editMlaAreasBtn" class="w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition">
                            <i class="fas fa-edit mr-2"></i>Edit MLA Areas
                        </button>
                    </div>
                </div>
                <div>
                    <label for="blockSelect" class="block text-sm font-medium text-gray-700 mb-2">Select Block (Optional)</label>
                    <select id="blockSelect" name="block_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" disabled>
                        <option value="">-- Select District First --</option>
                    </select>
                    <!-- Add/Edit Sector Button - Below Block Dropdown -->
                    <div id="blockActions" class="mt-3" style="display:none;">
                        <button type="button" id="addEditSectorBtn" class="w-full px-4 py-2 bg-purple-600 text-white font-medium rounded-lg shadow hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-50 transition" style="background-color: #9333ea !important; color: white !important;">
                            <i class="fas fa-circle-notch mr-2"></i>Add/Edit Sector (Panchayat)
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sector Dropdown and Actions (New Row) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="sectorSelect" class="block text-sm font-medium text-gray-700 mb-2">Select Sector (Panchayat)</label>
                    <select id="sectorSelect" name="sector_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" disabled>
                        <option value="">-- Select Block First --</option>
                    </select>
                    <!-- Add/Edit Village Button - Below Sector Dropdown -->
                    <div id="sectorActions" class="mt-3" style="display:none;">
                        <button type="button" id="addEditVillageBtn" class="w-full px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 transition" style="background-color: #4f46e5 !important; color: white !important;">
                            <i class="fas fa-home mr-2"></i>Add/Edit Village
                        </button>
                    </div>
                </div>
            </div>

            <div id="dataEditAreaContainer" class="mb-6" style="display:none;">
                <label for="dataTextArea" class="block text-sm font-medium text-gray-700 mb-2">Editing: <span id="editingDataTypeLabel" class="font-bold"></span> (one item per line)</label>
                <textarea id="dataTextArea" name="data_items" class="w-full h-48 px-4 py-3 font-mono text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Data will be listed here. Add, edit, or delete items, ensuring each is on a new line."></textarea>
                <input type="hidden" id="currentDataType" name="data_type">
            </div>
            <button type="submit" id="updateDataBtn" class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition" style="display:none;">
                <i class="fas fa-save mr-2"></i>Update <span id="updateButtonLabel"></span>
            </button>
        </form>
        <div id="formFeedback" class="mt-4"></div>
    </div>
</div>

<!-- Add/Edit District Modal -->
<div id="addDistrictModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Add/Edit Districts</h3>
            <div class="mt-2 px-7 py-3">
                <form id="addDistrictForm">
                    <input type="hidden" name="state_id" id="addDistrictStateId">
                    <textarea id="districtNamesTextarea" name="district_names" class="w-full h-48 px-3 py-2 text-gray-700 border rounded-lg focus:outline-none" placeholder="Enter district names, separated by commas"></textarea>
                    <div class="items-center px-4 py-3">
                        <button type="submit" id="saveDistrictsBtn" class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">Save Districts</button>
                    </div>
                </form>
            </div>
            <div class="items-center px-4 py-3">
                <button id="closeAddDistrictModal" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">Cancel</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stateSelect = document.getElementById('stateSelect');
        const districtSelect = document.getElementById('districtSelect');
        const blockSelect = document.getElementById('blockSelect');
        const sectorSelect = document.getElementById('sectorSelect');
        const editActionsDiv = document.getElementById('editActions');
        const stateActionsDiv = document.getElementById('stateActions');
        const blockActionsDiv = document.getElementById('blockActions');
        const sectorActionsDiv = document.getElementById('sectorActions');
        const addDistrictBtn = document.getElementById('addDistrictBtn');
        const dataEditAreaContainer = document.getElementById('dataEditAreaContainer');
        const dataTextArea = document.getElementById('dataTextArea');
        const editingDataTypeLabel = document.getElementById('editingDataTypeLabel');
        const currentDataTypeInput = document.getElementById('currentDataType');
        const updateDataBtn = document.getElementById('updateDataBtn');
        const updateButtonLabel = document.getElementById('updateButtonLabel');
        const editDistrictDataForm = document.getElementById('editDistrictDataForm');
        const formFeedback = document.getElementById('formFeedback');

        // Modals
        const addDistrictModal = document.getElementById('addDistrictModal');
        const closeAddDistrictModal = document.getElementById('closeAddDistrictModal');
        const addDistrictForm = document.getElementById('addDistrictForm');
        const saveDistrictsBtn = document.getElementById('saveDistrictsBtn');
        const districtNamesTextarea = document.getElementById('districtNamesTextarea');

        // CSRF details
        const csrfTokenName = document.querySelector('input[name="<?= csrf_token() ?>"]').name;
        const csrfTokenValue = document.querySelector('input[name="<?= csrf_token() ?>"]').value;

        stateSelect.addEventListener('change', function() {
            const stateId = this.value;
            districtSelect.innerHTML = '<option value="">-- Loading Districts --</option>';
            districtSelect.disabled = true;
            editActionsDiv.style.display = 'none';
            dataEditAreaContainer.style.display = 'none';
            updateDataBtn.style.display = 'none';
            formFeedback.innerHTML = '';

            if (stateId) {
                stateActionsDiv.style.display = 'block';
                fetch(`<?= site_url('admin/locations/get_districts_by_state') ?>/${stateId}`.replace(/^http:\/\//i, 'https://'), {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.text())
                .then(text => {
                    try {
                        // Strip any trailing HTML/DebugBar garbage that CI4 might append
                        const jsonEnd = text.lastIndexOf('}') + 1;
                        const cleanJson = text.substring(0, jsonEnd);
                        return JSON.parse(cleanJson);
                    } catch (e) {
                        console.error('Failed to parse JSON:', text);
                        throw e;
                    }
                })
                .then(data => {
                    districtSelect.innerHTML = '<option value="">-- Select District --</option>';
                    if (data.success && data.districts.length > 0) {
                        data.districts.forEach(district => {
                            const option = document.createElement('option');
                            option.value = district.id;
                            option.textContent = district.name;
                            districtSelect.appendChild(option);
                        });
                        districtSelect.disabled = false;
                    } else {
                        districtSelect.innerHTML = '<option value="">-- No Districts Found --</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching districts:', error);
                    districtSelect.innerHTML = '<option value="">-- Error Loading Districts --</option>';
                });
            } else {
                stateActionsDiv.style.display = 'none';
                districtSelect.innerHTML = '<option value="">-- Select State First --</option>';
            }
        });

        districtSelect.addEventListener('change', function() {
            const districtId = this.value;
            
            
            // Reset block dropdown
            blockSelect.innerHTML = '<option value="">-- Loading Blocks --</option>';
            blockSelect.disabled = true;
            blockActionsDiv.style.display = 'none';

            // Reset sector dropdown
            sectorSelect.innerHTML = '<option value="">-- Select Block First --</option>';
            sectorSelect.disabled = true;
            sectorActionsDiv.style.display = 'none';

            
            if (districtId) {
                editActionsDiv.style.display = 'flex';
                
                // Load blocks for selected district
                fetch(`<?= site_url('admin/locations/get_blocks_by_district') ?>/${districtId}`.replace(/^http:\/\//i, 'https://'), {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.text())
                .then(text => {
                    try {
                        const jsonEnd = text.lastIndexOf('}') + 1;
                        return JSON.parse(text.substring(0, jsonEnd));
                    } catch (e) {
                        console.error('Failed to parse JSON:', text);
                        throw e;
                    }
                })
                .then(data => {
                    blockSelect.innerHTML = '<option value="">-- Select Block (Optional) --</option>';
                    if (data.success && data.blocks.length > 0) {
                        data.blocks.forEach(block => {
                            const option = document.createElement('option');
                            option.value = block.id;
                            option.textContent = block.name;
                            blockSelect.appendChild(option);
                        });
                        blockSelect.disabled = false;
                    } else {
                        blockSelect.innerHTML = '<option value="">-- No Blocks Found --</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching blocks:', error);
                    blockSelect.innerHTML = '<option value="">-- Error Loading Blocks --</option>';
                });
            } else {
                editActionsDiv.style.display = 'none';
                blockSelect.innerHTML = '<option value="">-- Select District First --</option>';
            }
            dataEditAreaContainer.style.display = 'none';
            updateDataBtn.style.display = 'none';
            formFeedback.innerHTML = '';
        });

        // Block Select Change Handler
        blockSelect.addEventListener('change', function() {
            const blockId = this.value;
            
            // Reset sector dropdown
            sectorSelect.innerHTML = '<option value="">-- Loading Sectors --</option>';
            sectorSelect.disabled = true;
            sectorActionsDiv.style.display = 'none';

            if (blockId) {
                blockActionsDiv.style.display = 'block';

                // Load sectors for selected block
                fetch(`<?= site_url('admin/locations/get_sectors_by_block') ?>/${blockId}`.replace(/^http:\/\//i, 'https://'), {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.text())
                .then(text => {
                    try {
                        const jsonEnd = text.lastIndexOf('}') + 1;
                        return JSON.parse(text.substring(0, jsonEnd));
                    } catch (e) {
                        console.error('Failed to parse JSON:', text);
                        throw e;
                    }
                })
                .then(data => {
                    sectorSelect.innerHTML = '<option value="">-- Select Sector --</option>';
                    if (data.success && data.sectors.length > 0) {
                        data.sectors.forEach(sector => {
                            const option = document.createElement('option');
                            option.value = sector.id;
                            option.textContent = sector.name;
                            sectorSelect.appendChild(option);
                        });
                        sectorSelect.disabled = false;
                    } else {
                        sectorSelect.innerHTML = '<option value="">-- No Sectors Found --</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching sectors:', error);
                    sectorSelect.innerHTML = '<option value="">-- Error Loading Sectors --</option>';
                });
            } else {
                blockActionsDiv.style.display = 'none';
                sectorSelect.innerHTML = '<option value="">-- Select Block First --</option>';
            }
            dataEditAreaContainer.style.display = 'none';
            updateDataBtn.style.display = 'none';
            formFeedback.innerHTML = '';
        });

        // Sector Select Change Handler
        sectorSelect.addEventListener('change', function() {
            if (this.value) {
                sectorActionsDiv.style.display = 'block';
            } else {
                sectorActionsDiv.style.display = 'none';
            }
            dataEditAreaContainer.style.display = 'none';
            updateDataBtn.style.display = 'none';
            formFeedback.innerHTML = '';
        });

        // Edit Blocks Button Handler
        const editBlocksBtn = document.getElementById('editBlocksBtn');
        editBlocksBtn.addEventListener('click', function() {
            const districtId = districtSelect.value;
            if (!districtId) {
                showFeedback('Please select a district first.', 'error');
                return;
            }

            // Fetch existing blocks for the selected district
            fetch(`<?= site_url('admin/locations/get_blocks_by_district') ?>/${districtId}`.replace(/^http:\/\//i, 'https://'), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.text())
            .then(text => {
                const jsonEnd = text.lastIndexOf('}') + 1;
                return JSON.parse(text.substring(0, jsonEnd));
            })
            .then(data => {
                if (data.success && data.blocks) {
                    const blockNames = data.blocks.map(b => b.name).join('\n');
                    dataTextArea.value = blockNames;
                } else {
                    dataTextArea.value = '';
                }
                
                // Show the edit area
                editingDataTypeLabel.textContent = 'Blocks';
                currentDataTypeInput.value = 'blocks';
                updateButtonLabel.textContent = 'Blocks';
                dataEditAreaContainer.style.display = 'block';
                updateDataBtn.style.display = 'inline-block';
                formFeedback.innerHTML = '';
            })
            .catch(error => {
                console.error('Error fetching blocks:', error);
                showFeedback('Failed to load blocks. Please try again.', 'error');
            });
        });

        // Edit MLA Areas Button Handler
        const editMlaAreasBtn = document.getElementById('editMlaAreasBtn');
        editMlaAreasBtn.addEventListener('click', function() {
            const districtId = districtSelect.value;
            if (!districtId) {
                showFeedback('Please select a district first.', 'error');
                return;
            }

            // Fetch existing MLA areas for the selected district
            fetch(`<?= site_url('admin/locations/get_mla_areas_by_district') ?>/${districtId}`.replace(/^http:\/\//i, 'https://'), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.text())
            .then(text => {
                const jsonEnd = text.lastIndexOf('}') + 1;
                return JSON.parse(text.substring(0, jsonEnd));
            })
            .then(data => {
                if (data.success && data.mla_areas) {
                    const mlaAreaNames = data.mla_areas.map(m => m.name).join('\n');
                    dataTextArea.value = mlaAreaNames;
                } else {
                    dataTextArea.value = '';
                }
                
                // Show the edit area
                editingDataTypeLabel.textContent = 'MLA Areas';
                currentDataTypeInput.value = 'mla_areas';
                updateButtonLabel.textContent = 'MLA Areas';
                dataEditAreaContainer.style.display = 'block';
                updateDataBtn.style.display = 'inline-block';
                formFeedback.innerHTML = '';
            })
            .catch(error => {
                console.error('Error fetching MLA areas:', error);
                showFeedback('Failed to load MLA areas. Please try again.', 'error');
            });
        });

        // Add/Edit Sector Button Handler
        const addEditSectorBtn = document.getElementById('addEditSectorBtn');
        addEditSectorBtn.addEventListener('click', function() {
            const blockId = blockSelect.value;
            if (!blockId) {
                showFeedback('Please select a block first.', 'error');
                return;
            }

            // Fetch existing sectors for the selected block
            fetch(`<?= site_url('admin/locations/get_sectors_by_block') ?>/${blockId}`.replace(/^http:\/\//i, 'https://'), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.text())
            .then(text => {
                const jsonEnd = text.lastIndexOf('}') + 1;
                return JSON.parse(text.substring(0, jsonEnd));
            })
            .then(data => {
                if (data.success && data.sectors) {
                    const sectorNames = data.sectors.map(c => c.name).join('\n');
                    dataTextArea.value = sectorNames;
                } else {
                    dataTextArea.value = '';
                }
                
                // Show the edit area
                editingDataTypeLabel.textContent = 'Sectors (Panchayat) (one item per line)';
                currentDataTypeInput.value = 'sectors';
                updateButtonLabel.textContent = 'Sectors (Panchayat)';
                dataEditAreaContainer.style.display = 'block';
                updateDataBtn.style.display = 'inline-block';
                formFeedback.innerHTML = '';
            })
            .catch(error => {
                console.error('Error fetching sectors:', error);
                showFeedback('Failed to load sectors. Please try again.', 'error');
            });
        });

        // Add/Edit Village Button Handler
        const addEditVillageBtn = document.getElementById('addEditVillageBtn');
        addEditVillageBtn.addEventListener('click', function() {
            const sectorId = sectorSelect.value;
            if (!sectorId) {
                showFeedback('Please select a sector first.', 'error');
                return;
            }

            // Fetch existing villages for the selected sector
            fetch(`<?= site_url('admin/locations/get_villages_by_sector') ?>/${sectorId}`.replace(/^http:\/\//i, 'https://'), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.text())
            .then(text => {
                const jsonEnd = text.lastIndexOf('}') + 1;
                return JSON.parse(text.substring(0, jsonEnd));
            })
            .then(data => {
                if (data.success && data.villages) {
                    const villageNames = data.villages.map(v => v.name).join('\n');
                    dataTextArea.value = villageNames;
                } else {
                    dataTextArea.value = '';
                }
                
                // Show the edit area
                editingDataTypeLabel.textContent = 'Villages (one item per line)';
                currentDataTypeInput.value = 'villages';
                updateButtonLabel.textContent = 'Villages';
                dataEditAreaContainer.style.display = 'block';
                updateDataBtn.style.display = 'inline-block';
                formFeedback.innerHTML = '';
            })
            .catch(error => {
                console.error('Error fetching villages:', error);
                showFeedback('Failed to load villages. Please try again.', 'error');
            });
        });

        // Modal Handling
        addDistrictBtn.addEventListener('click', () => {
            const stateId = stateSelect.value;
            if (!stateId) {
                showFeedback('Please select a state first.', 'error');
                return;
            }
            addDistrictModal.style.display = 'block';
            document.getElementById('addDistrictStateId').value = stateId;

            fetch(`<?= site_url('admin/locations/get_districts_by_state') ?>/${stateId}`.replace(/^http:\/\//i, 'https://'), { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(response => response.text())
                .then(text => {
                    const jsonEnd = text.lastIndexOf('}') + 1;
                    return JSON.parse(text.substring(0, jsonEnd));
                })
                .then(data => {
                    if (data.success && data.districts.length > 0) {
                        districtNamesTextarea.value = data.districts.map(d => d.name).join(', ');
                    } else {
                        districtNamesTextarea.value = '';
                    }
                });
        });

        closeAddDistrictModal.addEventListener('click', () => addDistrictModal.style.display = 'none');

        addDistrictForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const stateId = document.getElementById('addDistrictStateId').value;
            const districtNames = districtNamesTextarea.value.split(',').map(name => name.trim()).filter(name => name);

            fetch(`<?= site_url('admin/locations/add_districts') ?>`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfTokenValue
                },
                body: JSON.stringify({ state_id: stateId, names: districtNames })
            })
            .then(response => response.text())
            .then(text => {
                const jsonEnd = text.lastIndexOf('}') + 1;
                return JSON.parse(text.substring(0, jsonEnd));
            })
            .then(data => {
                if (data.success) {
                    showFeedback(data.message || 'Districts saved successfully.', 'success');
                    addDistrictModal.style.display = 'none';
                    stateSelect.dispatchEvent(new Event('change')); // Refresh district list
                } else {
                    showFeedback(data.message || 'Failed to save districts.', 'error');
                }
            });
        });

        // Enhanced Form Submit Handler for Blocks, MLA Areas, and Sectors
        editDistrictDataForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const dataType = currentDataTypeInput.value;
            const districtId = districtSelect.value;
            const blockId = blockSelect.value;
            const sectorId = sectorSelect.value;
            
            // Parse data items based on type
            let dataItems;
            // Parse line-separated values for all types (sectors, blocks, MLA areas)
            dataItems = dataTextArea.value.split('\n').map(item => item.trim()).filter(item => item);

            if (dataType === 'sectors' && !blockId) {
                showFeedback('Please select a block.', 'error');
                return;
            } else if (dataType === 'villages' && !sectorId) {
                showFeedback('Please select a sector.', 'error');
                return;
            } else if (!districtId) {
                showFeedback('Please select a district.', 'error');
                return;
            }

            if (dataItems.length === 0) {
                showFeedback('Please enter at least one item.', 'error');
                return;
            }

            let endpoint = '';
            let requestData = {};
            
            if (dataType === 'blocks') {
                endpoint = '<?= site_url('admin/locations/add_blocks') ?>';
                requestData = { district_id: districtId, names: dataItems };
            } else if (dataType === 'mla_areas') {
                endpoint = '<?= site_url('admin/locations/add_mla_areas') ?>';
                requestData = { district_id: districtId, names: dataItems };
            } else if (dataType === 'sectors') {
                endpoint = '<?= site_url('admin/locations/add_sectors') ?>';
                requestData = { block_id: blockId, names: dataItems };
            } else if (dataType === 'villages') {
                endpoint = '<?= site_url('admin/locations/add_villages') ?>';
                requestData = { sector_id: sectorId, names: dataItems };
            } else {
                showFeedback('Invalid data type.', 'error');
                return;
            }

            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfTokenValue
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.text())
            .then(text => {
                const jsonEnd = text.lastIndexOf('}') + 1;
                return JSON.parse(text.substring(0, jsonEnd));
            })
            .then(data => {
                console.log('Save response:', data);
                if (data.success) {
                    showFeedback(data.message || 'Data saved successfully.', 'success');
                    // Refresh the data by re-fetching
                    if (dataType === 'blocks') {
                        // Re-fetch blocks
                        fetch(`<?= site_url('admin/locations/get_blocks_by_district') ?>/${districtId}`.replace(/^http:\/\//i, 'https://'), {
                            method: 'GET',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                        })
                        .then(response => response.text())
                        .then(text => {
                            const jsonEnd = text.lastIndexOf('}') + 1;
                            const data = JSON.parse(text.substring(0, jsonEnd));
                            if (data.success && data.blocks) {
                                const blockNames = data.blocks.map(b => b.name).join('\n');
                                dataTextArea.value = blockNames;
                            }
                        });
                    } else if (dataType === 'mla_areas') {
                        // Re-fetch MLA areas
                        fetch(`<?= site_url('admin/locations/get_mla_areas_by_district') ?>/${districtId}`.replace(/^http:\/\//i, 'https://'), {
                            method: 'GET',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                        })
                        .then(response => response.text())
                        .then(text => {
                            const jsonEnd = text.lastIndexOf('}') + 1;
                            const data = JSON.parse(text.substring(0, jsonEnd));
                            if (data.success && data.mla_areas) {
                                const mlaAreaNames = data.mla_areas.map(m => m.name).join('\n');
                                dataTextArea.value = mlaAreaNames;
                            }
                        });
                    } else if (dataType === 'sectors') {
                        // Re-fetch sectors
                        fetch(`<?= site_url('admin/locations/get_sectors_by_block') ?>/${blockId}`.replace(/^http:\/\//i, 'https://'), {
                            method: 'GET',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                        })
                        .then(response => response.text())
                        .then(text => {
                            const jsonEnd = text.lastIndexOf('}') + 1;
                            const data = JSON.parse(text.substring(0, jsonEnd));
                            if (data.success && data.sectors) {
                                const sectorNames = data.sectors.map(c => c.name).join(', ');
                                dataTextArea.value = sectorNames;
                            }
                        });
                    } else if (dataType === 'villages') {
                        // Re-fetch villages
                        fetch(`<?= site_url('admin/locations/get_villages_by_sector') ?>/${sectorId}`.replace(/^http:\/\//i, 'https://'), {
                            method: 'GET',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                        })
                        .then(response => response.text())
                        .then(text => {
                            const jsonEnd = text.lastIndexOf('}') + 1;
                            const data = JSON.parse(text.substring(0, jsonEnd));
                            if (data.success && data.villages) {
                                const villageNames = data.villages.map(v => v.name).join('\n');
                                dataTextArea.value = villageNames;
                            }
                        });
                    }
                } else {
                    showFeedback(data.message || 'Failed to save data.', 'error');
                }
            })
            .catch(error => {
                console.error('Error saving data:', error);
                showFeedback('An error occurred. Please try again.', 'error');
            });
        });

        function showFeedback(message, type) {
            const typeClasses = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            formFeedback.innerHTML = `<div class="border-l-4 p-4 rounded-md ${typeClasses}" role="alert"><p>${message}</p></div>`;
        }
    });
</script>
<?= $this->endSection() ?>