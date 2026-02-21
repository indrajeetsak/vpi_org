<?= $this->extend('layouts/admin_modern') ?>

<?= $this->section('title') ?>Manage Circles<?= $this->endSection() ?>

<?= $this->section('headerTitle') ?>Manage Circles<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-xl shadow-md mt-8 overflow-hidden">
    <div class="bg-gray-50 px-6 py-4 border-b">
        <h4 class="text-lg font-bold text-gray-800">Manage Circles</h4>
        <p class="text-sm text-gray-600 mt-1">Assign sectors to circles (East, West, North, South) for each block.</p>
    </div>
    <div class="p-6">
        <form id="manageCirclesForm">
            <?= csrf_field() ?>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- State Selection -->
                <div>
                    <label for="stateSelect" class="block text-sm font-medium text-gray-700 mb-2">Select State</label>
                    <select id="stateSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">-- Select State --</option>
                        <?php if (!empty($all_states)): ?>
                            <?php foreach ($all_states as $state): ?>
                                <option value="<?= esc($state['id']) ?>"><?= esc($state['name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- District Selection -->
                <div>
                    <label for="districtSelect" class="block text-sm font-medium text-gray-700 mb-2">Select District</label>
                    <select id="districtSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" disabled>
                        <option value="">-- Select State First --</option>
                    </select>
                </div>

                <!-- Block Selection -->
                <div>
                    <label for="blockSelect" class="block text-sm font-medium text-gray-700 mb-2">Select Block</label>
                    <select id="blockSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" disabled>
                        <option value="">-- Select District First --</option>
                    </select>
                </div>

                <!-- Circle Selection -->
                <div>
                    <label for="circleSelect" class="block text-sm font-medium text-gray-700 mb-2">Select Circle</label>
                    <select id="circleSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" disabled>
                        <option value="">-- Select Block First --</option>
                        <?php if (!empty($circles)): ?>
                            <?php foreach ($circles as $circle): ?>
                                <option value="<?= esc($circle['id']) ?>"><?= esc($circle['name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <!-- Sectors Area -->
            <div id="sectorsContainer" class="hidden">
                <div class="border-t pt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="text-md font-bold text-gray-700">Assign Sectors to <span id="selectedCircleName" class="text-blue-600"></span></h5>
                        <div class="text-sm text-gray-500">
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs mr-2">Note</span>
                            Sectors assigned to other circles are hidden.
                        </div>
                    </div>

                    <div id="sectorsList" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 max-h-96 overflow-y-auto p-2 border rounded-lg bg-gray-50">
                        <!-- Sectors checkboxes will be injected here -->
                        <div class="text-gray-500 text-center col-span-full py-4">Select a circle to load sectors.</div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" id="saveBtn" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition">
                        <i class="fas fa-save mr-2"></i>Save Assignment
                    </button>
                </div>
            </div>
        </form>
        <div id="formFeedback" class="mt-4"></div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const stateSelect = document.getElementById('stateSelect');
        const districtSelect = document.getElementById('districtSelect');
        const blockSelect = document.getElementById('blockSelect');
        const circleSelect = document.getElementById('circleSelect');
        const sectorsContainer = document.getElementById('sectorsContainer');
        const sectorsList = document.getElementById('sectorsList');
        const selectedCircleName = document.getElementById('selectedCircleName');
        const manageCirclesForm = document.getElementById('manageCirclesForm');
        const formFeedback = document.getElementById('formFeedback');

        // CSRF
        const csrfTokenName = '<?= csrf_token() ?>';
        let csrfTokenValue = '<?= csrf_hash() ?>';

        // State Change
        stateSelect.addEventListener('change', function() {
            const stateId = this.value;
            resetDropdown(districtSelect, '-- Loading Districts --');
            resetDropdown(blockSelect, '-- Select District First --');
            resetDropdown(circleSelect, '-- Select Block First --', true); // Keep default options hidden
            sectorsContainer.classList.add('hidden');

            if (stateId) {
                fetch(`<?= site_url('admin/locations/get_districts_by_state') ?>/${stateId}`)
                    .then(r => r.json())
                    .then(data => {
                        populateDropdown(districtSelect, data.districts, '-- Select District --');
                    });
            } else {
                resetDropdown(districtSelect, '-- Select State First --');
            }
        });

        // District Change
        districtSelect.addEventListener('change', function() {
            const districtId = this.value;
            resetDropdown(blockSelect, '-- Loading Blocks --');
            resetDropdown(circleSelect, '-- Select Block First --', true);
            sectorsContainer.classList.add('hidden');

            if (districtId) {
                fetch(`<?= site_url('admin/locations/get_blocks_by_district') ?>/${districtId}`)
                    .then(r => r.json())
                    .then(data => {
                        populateDropdown(blockSelect, data.blocks, '-- Select Block --');
                    });
            } else {
                resetDropdown(blockSelect, '-- Select District First --');
            }
        });

        // Block Change
        blockSelect.addEventListener('change', function() {
            const blockId = this.value;
            if (blockId) {
                circleSelect.value = "";
                circleSelect.disabled = false;
                // Re-enable circle options if they were disabled
                Array.from(circleSelect.options).forEach(opt => opt.disabled = false);
                circleSelect.options[0].text = "-- Select Circle --";
            } else {
                circleSelect.value = "";
                circleSelect.disabled = true;
                circleSelect.options[0].text = "-- Select Block First --";
            }
            sectorsContainer.classList.add('hidden');
        });

        // Circle Change
        circleSelect.addEventListener('change', function() {
            const blockId = blockSelect.value;
            const circle = this.value;

            if (blockId && circle) {
                const circleName = this.options[this.selectedIndex].text;
                selectedCircleName.textContent = circleName;
                sectorsContainer.classList.remove('hidden');
                loadSectors(blockId, circle);
            } else {
                sectorsContainer.classList.add('hidden');
            }
        });

        // Load Sectors
        function loadSectors(blockId, circle) {
            sectorsList.innerHTML = '<div class="text-gray-500 text-center col-span-full py-4"><i class="fas fa-spinner fa-spin mr-2"></i>Loading sectors...</div>';

            fetch(`<?= site_url('admin/circles/get_sectors') ?>?block_id=${blockId}&circle=${encodeURIComponent(circle)}`)
                .then(r => r.json())
                .then(data => {
                    sectorsList.innerHTML = '';
                    if (data.success && data.sectors.length > 0) {
                        data.sectors.forEach(sector => {
                            // Compare circle ID (sector.circle is now circle_id)
                            const isChecked = sector.circle == circle ? 'checked' : '';
                            const html = `
                                <div class="flex items-start p-2 hover:bg-white rounded transition border border-transparent hover:border-gray-200">
                                    <div class="flex items-center h-5">
                                        <input id="sector_${sector.id}" name="sector_ids[]" value="${sector.id}" type="checkbox" ${isChecked} class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded cursor-pointer">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="sector_${sector.id}" class="font-medium text-gray-700 cursor-pointer select-none">${sector.name}</label>
                                    </div>
                                </div>
                            `;
                            sectorsList.insertAdjacentHTML('beforeend', html);
                        });
                    } else {
                        sectorsList.innerHTML = '<div class="text-gray-500 text-center col-span-full py-4">No available sectors found for this block.</div>';
                    }
                })
                .catch(err => {
                    console.error(err);
                    sectorsList.innerHTML = '<div class="text-red-500 text-center col-span-full py-4">Error loading sectors.</div>';
                });
        }

        // Save Assignment
        manageCirclesForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const blockId = blockSelect.value;
            const circle = circleSelect.value;
            
            // Collect checked sectors
            const checkedSectors = Array.from(document.querySelectorAll('input[name="sector_ids[]"]:checked')).map(cb => cb.value);

            if (!blockId || !circle) {
                showFeedback('Please select a block and circle.', 'error');
                return;
            }

            const submitBtn = document.getElementById('saveBtn');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';

            fetch('<?= site_url('admin/circles/update_assignment') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="' + csrfTokenName + '"]').value
                },
                body: JSON.stringify({
                    block_id: blockId,
                    circle: circle,
                    sector_ids: checkedSectors
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showFeedback(data.message, 'success');
                    // Reload sectors to verify state
                    loadSectors(blockId, circle);
                } else {
                    showFeedback(data.message || 'Failed to save.', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showFeedback('An error occurred.', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        });

        // Helper: Reset Dropdown
        function resetDropdown(element, defaultText, isStatic = false) {
            element.disabled = true;
            element.value = "";
            if (!isStatic) {
                element.innerHTML = `<option value="">${defaultText}</option>`;
            } else {
                element.options[0].text = defaultText;
            }
        }

        // Helper: Populate Dropdown
        function populateDropdown(element, items, defaultText) {
            element.innerHTML = `<option value="">${defaultText}</option>`;
            items.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                element.appendChild(option);
            });
            element.disabled = false;
        }

        // Helper: Feedback
        function showFeedback(message, type) {
            const color = type === 'success' ? 'green' : 'red';
            formFeedback.innerHTML = `
                <div class="bg-${color}-100 border-l-4 border-${color}-500 text-${color}-700 p-4 rounded shadow-sm" role="alert">
                    <p>${message}</p>
                </div>
            `;
            setTimeout(() => {
                formFeedback.innerHTML = '';
            }, 5000);
        }
    });
</script>
<?= $this->endSection() ?>
