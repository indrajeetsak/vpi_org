<?= $this->extend('layouts/admin_modern') ?>

<?= $this->section('title') ?>Manage Polling Booths<?= $this->endSection() ?>

<?= $this->section('headerTitle') ?>Manage Polling Booths<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-xl shadow-md mt-8 overflow-hidden" id="polling-booth-editor-widget">
    <div class="bg-gray-50 px-6 py-4 border-b">
        <h4 class="text-lg font-bold text-gray-800">Manage Polling Booths</h4>
        <p class="text-sm text-gray-600 mt-1">Add, edit, or delete Polling Booths (Stations) for an MLA Area.</p>
    </div>
    <div class="p-6">
        <form id="editPollingBoothForm">
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
                </div>
                <div>
                    <label for="districtSelect" class="block text-sm font-medium text-gray-700 mb-2">Select District</label>
                    <select id="districtSelect" name="district_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" disabled>
                        <option value="">-- Select State First --</option>
                    </select>
                </div>
                <div>
                    <label for="mlaAreaSelect" class="block text-sm font-medium text-gray-700 mb-2">Select MLA Area</label>
                    <select id="mlaAreaSelect" name="mla_area_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" disabled>
                        <option value="">-- Select District First --</option>
                    </select>
                    <!-- Add/Edit Polling Booths Button -->
                    <div id="mlaActions" class="mt-3" style="display:none;">
                        <button type="button" id="editPollingBoothsBtn" class="w-full px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 transition">
                            <i class="fas fa-edit mr-2"></i>Edit Polling Booths
                        </button>
                    </div>
                </div>
            </div>

            <div id="dataEditAreaContainer" class="mb-6" style="display:none;">
                <label for="dataTextArea" class="block text-sm font-medium text-gray-700 mb-2">Editing: <span class="font-bold">Polling Booths</span> (one item per line)</label>
                <textarea id="dataTextArea" name="data_items" class="w-full h-48 px-4 py-3 font-mono text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Paste polling booths here. Each booth should be on a new line."></textarea>
            </div>
            <button type="submit" id="updateDataBtn" class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition" style="display:none;">
                <i class="fas fa-save mr-2"></i>Update Polling Booths
            </button>
        </form>
        <div id="formFeedback" class="mt-4"></div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stateSelect = document.getElementById('stateSelect');
        const districtSelect = document.getElementById('districtSelect');
        const mlaAreaSelect = document.getElementById('mlaAreaSelect');
        const mlaActionsDiv = document.getElementById('mlaActions');
        const editPollingBoothsBtn = document.getElementById('editPollingBoothsBtn');
        const dataEditAreaContainer = document.getElementById('dataEditAreaContainer');
        const dataTextArea = document.getElementById('dataTextArea');
        const updateDataBtn = document.getElementById('updateDataBtn');
        const editPollingBoothForm = document.getElementById('editPollingBoothForm');
        const formFeedback = document.getElementById('formFeedback');

        const csrfTokenValue = document.querySelector('input[name="<?= csrf_token() ?>"]').value;

        // Reset Data Editor
        function resetEditor() {
            dataEditAreaContainer.style.display = 'none';
            updateDataBtn.style.display = 'none';
            formFeedback.innerHTML = '';
        }

        function showFeedback(message, type) {
            const typeClasses = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            formFeedback.innerHTML = `<div class="border-l-4 p-4 rounded-md ${typeClasses}" role="alert"><p>${message}</p></div>`;
        }

        // State Change
        stateSelect.addEventListener('change', function() {
            const stateId = this.value;
            districtSelect.innerHTML = '<option value="">-- Loading Districts --</option>';
            districtSelect.disabled = true;
            mlaAreaSelect.innerHTML = '<option value="">-- Select District First --</option>';
            mlaAreaSelect.disabled = true;
            mlaActionsDiv.style.display = 'none';
            resetEditor();

            if (stateId) {
                fetch(`<?= site_url('admin/locations/get_districts_by_state') ?>/${stateId}`, { headers: {'X-Requested-With': 'XMLHttpRequest'} })
                .then(r => r.json())
                .then(data => {
                    districtSelect.innerHTML = '<option value="">-- Select District --</option>';
                    if (data.success && data.districts.length > 0) {
                        data.districts.forEach(d => {
                            let opt = document.createElement('option');
                            opt.value = d.id; opt.textContent = d.name;
                            districtSelect.appendChild(opt);
                        });
                        districtSelect.disabled = false;
                    } else {
                        districtSelect.innerHTML = '<option value="">-- No Districts Found --</option>';
                    }
                }).catch(e => console.error(e));
            } else {
                districtSelect.innerHTML = '<option value="">-- Select State First --</option>';
            }
        });

        // District Change
        districtSelect.addEventListener('change', function() {
            const districtId = this.value;
            mlaAreaSelect.innerHTML = '<option value="">-- Loading MLA Areas --</option>';
            mlaAreaSelect.disabled = true;
            mlaActionsDiv.style.display = 'none';
            resetEditor();

            if (districtId) {
                fetch(`<?= site_url('admin/locations/get_mla_areas_by_district') ?>/${districtId}`, { headers: {'X-Requested-With': 'XMLHttpRequest'} })
                .then(r => r.json())
                .then(data => {
                    mlaAreaSelect.innerHTML = '<option value="">-- Select MLA Area --</option>';
                    if (data.success && data.mla_areas.length > 0) {
                        data.mla_areas.forEach(m => {
                            let opt = document.createElement('option');
                            opt.value = m.id; opt.textContent = m.name;
                            mlaAreaSelect.appendChild(opt);
                        });
                        mlaAreaSelect.disabled = false;
                    } else {
                        mlaAreaSelect.innerHTML = '<option value="">-- No MLA Areas Found --</option>';
                    }
                }).catch(e => console.error(e));
            } else {
                mlaAreaSelect.innerHTML = '<option value="">-- Select District First --</option>';
            }
        });

        // MLA Area Change
        mlaAreaSelect.addEventListener('change', function() {
            if (this.value) {
                mlaActionsDiv.style.display = 'block';
            } else {
                mlaActionsDiv.style.display = 'none';
            }
            resetEditor();
        });

        // Edit Polling Booths Button
        editPollingBoothsBtn.addEventListener('click', function() {
            const mlaAreaId = mlaAreaSelect.value;
            if (!mlaAreaId) { showFeedback('Please select an MLA Area first.', 'error'); return; }

            fetch(`<?= site_url('admin/locations/get_polling_booths_by_mla_area') ?>/${mlaAreaId}`, { headers: {'X-Requested-With': 'XMLHttpRequest'} })
            .then(r => r.json())
            .then(data => {
                if (data.success && data.polling_booths) {
                    dataTextArea.value = data.polling_booths.map(b => b.name).join('\n');
                } else {
                    dataTextArea.value = '';
                }
                dataEditAreaContainer.style.display = 'block';
                updateDataBtn.style.display = 'inline-block';
                formFeedback.innerHTML = '';
            }).catch(e => {
                console.error(e);
                showFeedback('Failed to load Polling Booths.', 'error');
            });
        });

        // Submit Updated Boohs
        editPollingBoothForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const mlaAreaId = mlaAreaSelect.value;
            if (!mlaAreaId) { showFeedback('Please select an MLA Area.', 'error'); return; }

            const dataItems = dataTextArea.value.split('\n').map(item => item.trim()).filter(item => item);
            
            if (dataItems.length === 0) {
                if (!confirm("You are about to delete all polling booths for this MLA Area. Continue?")) return;
            }

            fetch(`<?= site_url('admin/locations/add_polling_booths') ?>`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfTokenValue
                },
                body: JSON.stringify({ mla_area_id: mlaAreaId, names: dataItems })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // Update text area with sanitized input
                    dataTextArea.value = dataItems.join('\n');
                    showFeedback(data.message || 'Polling booths updated successfully.', 'success');
                } else {
                    showFeedback(data.message || 'Failed to sync polling booths.', 'error');
                }
            }).catch(e => {
                console.error(e);
                showFeedback('An error occurred during save.', 'error');
            });
        });
    });
</script>
<?= $this->endSection() ?>
