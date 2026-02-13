<?= $this->extend('layouts/admin_modern') ?>

<?= $this->section('title') ?>Manage Constituencies<?= $this->endSection() ?>

<?= $this->section('headerTitle') ?>Manage Constituencies<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-xl shadow-md mt-8 overflow-hidden">
    <div class="bg-gray-50 px-6 py-4 border-b">
        <h4 class="text-lg font-bold text-gray-800">Manage Loksabha Constituencies</h4>
    </div>
    <div class="p-6">
        <form id="editConstituencyForm">
            <?= csrf_field() ?>
            <input type="hidden" id="currentDataType" name="data_type" value="">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Column 1: 4 Loksabha -->
                <div>
                    <label for="loksabhaSelect" class="block text-sm font-medium text-gray-700 mb-2">4 Loksabha</label>
                    <select id="loksabhaSelect" name="loksabha_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">-- Select 4 Loksabha --</option>
                        <?php if (!empty($loksabha_list)): ?>
                            <?php foreach ($loksabha_list as $loksabha): ?>
                                <option value="<?= esc($loksabha['id']) ?>"><?= esc($loksabha['Name']) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No Loksabha found</option>
                        <?php endif; ?>
                    </select>
                    
                    <!-- Add/Edit 3 Loksabha Button -->
                    <div id="fourLsActions" class="mt-3" style="display:none;">
                        <button type="button" id="addEdit3LsBtn" class="w-full px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition">
                            <i class="fas fa-edit mr-1"></i>Edit 3 LS
                        </button>
                    </div>
                </div>
                
                <!-- Column 2: 3 Loksabha -->
                <div>
                    <label for="threeLsSelect" class="block text-sm font-medium text-gray-700 mb-2">3 Loksabha</label>
                    <select id="threeLsSelect" name="3ls_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" disabled>
                        <option value="">-- Select 4 LS First --</option>
                    </select>
                    
                    <!-- Add/Edit 2 Loksabha Button -->
                    <div id="threeLsActions" class="mt-3" style="display:none;">
                        <button type="button" id="addEdit2LsBtn" class="w-full px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <i class="fas fa-edit mr-1"></i>Edit 2 LS
                        </button>
                    </div>
                </div>
                
                <!-- Column 3: 2 Loksabha -->
                <div>
                    <label for="twoLsSelect" class="block text-sm font-medium text-gray-700 mb-2">2 Loksabha</label>
                    <select id="twoLsSelect" name="2ls_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" disabled>
                        <option value="">-- Select 3 LS First --</option>
                    </select>
                    
                    <!-- Add/Edit 1 Loksabha Button -->
                    <div id="twoLsActions" class="mt-3" style="display:none;">
                        <button type="button" id="addEdit1LsBtn" class="w-full px-3 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg shadow hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 transition">
                            <i class="fas fa-edit mr-1"></i>Edit 1 LS
                        </button>
                    </div>
                </div>
                
                <!-- Column 4: 1 Loksabha -->
                <div>
                    <label for="oneLsSelect" class="block text-sm font-medium text-gray-700 mb-2">1 Loksabha</label>
                    <select id="oneLsSelect" name="1ls_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" disabled>
                        <option value="">-- Select 2 LS First --</option>
                    </select>
                </div>
            </div>

            <!-- Data Edit Area (for 3 Loksabha) -->
            <div id="dataEditAreaContainer" style="display:none;" class="mt-6">
                <h5 class="text-md font-semibold text-gray-700 mb-3">
                    Editing: <span id="editingDataTypeLabel"></span> (line-separated) <span class="text-sm text-gray-500">(one item per line)</span>
                </h5>
                <textarea id="dataTextArea" name="data" rows="10" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-y" placeholder="Data will be listed here. Add, edit, or delete items, ensuring each is on a new line."></textarea>
                
                <div class="mt-4 flex gap-3">
                    <button type="submit" id="updateDataBtn" class="px-6 py-2 bg-green-600 text-white font-medium rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition">
                        <i class="fas fa-save mr-2"></i>Update <span id="updateButtonLabel"></span>
                    </button>
                    <button type="button" id="cancelEditBtn" class="px-6 py-2 bg-gray-500 text-white font-medium rounded-lg shadow hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50 transition">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                </div>
                
                <div id="formFeedback" class="mt-4"></div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loksabhaSelect = document.getElementById('loksabhaSelect');
        const threeLsSelect = document.getElementById('threeLsSelect');
        const twoLsSelect = document.getElementById('twoLsSelect');
        const oneLsSelect = document.getElementById('oneLsSelect');
        const fourLsActionsDiv = document.getElementById('fourLsActions');
        const addEdit3LsBtn = document.getElementById('addEdit3LsBtn');
        const dataEditAreaContainer = document.getElementById('dataEditAreaContainer');
        const dataTextArea = document.getElementById('dataTextArea');
        const editingDataTypeLabel = document.getElementById('editingDataTypeLabel');
        const currentDataTypeInput = document.getElementById('currentDataType');
        const updateDataBtn = document.getElementById('updateDataBtn');
        const updateButtonLabel = document.getElementById('updateButtonLabel');
        const editConstituencyForm = document.getElementById('editConstituencyForm');
        const formFeedback = document.getElementById('formFeedback');
        const cancelEditBtn = document.getElementById('cancelEditBtn');
        const threeLsActionsDiv = document.getElementById('threeLsActions');
        const twoLsActionsDiv = document.getElementById('twoLsActions');
        const addEdit2LsBtn = document.getElementById('addEdit2LsBtn');
        const addEdit1LsBtn = document.getElementById('addEdit1LsBtn');

        // CSRF details
        const csrfTokenName = document.querySelector('input[name="<?= csrf_token() ?>"]').name;
        const csrfTokenValue = document.querySelector('input[name="<?= csrf_token() ?>"]').value;

        // 4 Loksabha Select Change Handler
        loksabhaSelect.addEventListener('change', function() {
            const fourLsId = this.value;
            
            if (fourLsId) {
                fourLsActionsDiv.style.display = 'block';
                
                // Fetch 3 Loksabha for the selected 4 Loksabha and populate dropdown
                threeLsSelect.innerHTML = '<option value="">-- Loading 3 Loksabha --</option>';
                threeLsSelect.disabled = true;
                
                // Reset dependent dropdowns
                twoLsSelect.innerHTML = '<option value="">-- Select 3 Loksabha First --</option>';
                twoLsSelect.disabled = true;
                oneLsSelect.innerHTML = '<option value="">-- Select 2 Loksabha First --</option>';
                oneLsSelect.disabled = true;
                
                fetch(`<?= site_url('admin/constituencies/get_3ls_by_4ls') ?>/${fourLsId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    threeLsSelect.innerHTML = '<option value="">-- Select 3 Loksabha --</option>';
                    if (data.success && data['3ls'] && data['3ls'].length > 0) {
                        data['3ls'].forEach(ls => {
                            const option = document.createElement('option');
                            option.value = ls.id;
                            option.textContent = ls.name;
                            threeLsSelect.appendChild(option);
                        });
                        threeLsSelect.disabled = false;
                    } else {
                        threeLsSelect.innerHTML = '<option value="">-- No 3 Loksabha Found --</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching 3 Loksabha:', error);
                    threeLsSelect.innerHTML = '<option value="">-- Error Loading 3 Loksabha --</option>';
                });
            } else {
                fourLsActionsDiv.style.display = 'none';
                threeLsSelect.innerHTML = '<option value="">-- Select 4 Loksabha First --</option>';
                threeLsSelect.disabled = true;
                twoLsSelect.innerHTML = '<option value="">-- Select 3 Loksabha First --</option>';
                twoLsSelect.disabled = true;
                oneLsSelect.innerHTML = '<option value="">-- Select 2 Loksabha First --</option>';
                oneLsSelect.disabled = true;
            }
            dataEditAreaContainer.style.display = 'none';
            formFeedback.innerHTML = '';
        });

        // 3 Loksabha Select Change Handler
        threeLsSelect.addEventListener('change', function() {
            const threeLsId = this.value;
            
            if (threeLsId) {
                threeLsActionsDiv.style.display = 'block';
                
                // Fetch 2 Loksabha for the selected 3 Loksabha and populate dropdown
                twoLsSelect.innerHTML = '<option value="">-- Loading 2 Loksabha --</option>';
                twoLsSelect.disabled = true;
                
                // Reset dependent dropdown
                oneLsSelect.innerHTML = '<option value="">-- Select 2 Loksabha First --</option>';
                oneLsSelect.disabled = true;
                twoLsActionsDiv.style.display = 'none';
                
                fetch(`<?= site_url('admin/constituencies/get_2ls_by_3ls') ?>/${threeLsId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    twoLsSelect.innerHTML = '<option value="">-- Select 2 Loksabha --</option>';
                    if (data.success && data['2ls'] && data['2ls'].length > 0) {
                        data['2ls'].forEach(ls => {
                            const option = document.createElement('option');
                            option.value = ls.id;
                            option.textContent = ls.Name;
                            twoLsSelect.appendChild(option);
                        });
                        twoLsSelect.disabled = false;
                    } else {
                        twoLsSelect.innerHTML = '<option value="">-- No 2 Loksabha Found --</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching 2 Loksabha:', error);
                    twoLsSelect.innerHTML = '<option value="">-- Error Loading 2 Loksabha --</option>';
                });
            } else {
                threeLsActionsDiv.style.display = 'none';
                twoLsSelect.innerHTML = '<option value="">-- Select 3 Loksabha First --</option>';
                twoLsSelect.disabled = true;
                oneLsSelect.innerHTML = '<option value="">-- Select 2 Loksabha First --</option>';
                oneLsSelect.disabled = true;
                twoLsActionsDiv.style.display = 'none';
            }
        });

        // 2 Loksabha Select Change Handler
        twoLsSelect.addEventListener('change', function() {
            const twoLsId = this.value;
            
            if (twoLsId) {
                twoLsActionsDiv.style.display = 'block';
                
                // Fetch 1 Loksabha for the selected 2 Loksabha and populate dropdown
                oneLsSelect.innerHTML = '<option value="">-- Loading 1 Loksabha --</option>';
                oneLsSelect.disabled = true;
                
                fetch(`<?= site_url('admin/constituencies/get_1ls_by_2ls') ?>/${twoLsId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    oneLsSelect.innerHTML = '<option value="">-- Select 1 Loksabha --</option>';
                    if (data.success && data['1ls'] && data['1ls'].length > 0) {
                        data['1ls'].forEach(ls => {
                            const option = document.createElement('option');
                            option.value = ls.id;
                            option.textContent = ls.name;
                            oneLsSelect.appendChild(option);
                        });
                        oneLsSelect.disabled = false;
                    } else {
                        oneLsSelect.innerHTML = '<option value="">-- No 1 Loksabha Found --</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching 1 Loksabha:', error);
                    oneLsSelect.innerHTML = '<option value="">-- Error Loading 1 Loksabha --</option>';
                });
            } else {
                twoLsActionsDiv.style.display = 'none';
                oneLsSelect.innerHTML = '<option value="">-- Select 2 Loksabha First --</option>';
                oneLsSelect.disabled = true;
            }
        });

        // Add/Edit 3 Loksabha Button Handler
        addEdit3LsBtn.addEventListener('click', function() {
            const fourLsId = loksabhaSelect.value;
            if (!fourLsId) {
                showFeedback('Please select a 4 Loksabha first.', 'error');
                return;
            }

            // Fetch existing 3 Loksabha for the selected 4 Loksabha
            fetch(`<?= site_url('admin/constituencies/get_3ls_by_4ls') ?>/${fourLsId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data['3ls']) {
                    const threeLsNames = data['3ls'].map(ls => ls.name).join('\n');
                    dataTextArea.value = threeLsNames;
                } else {
                    dataTextArea.value = '';
                }
                
                // Show the edit area
                editingDataTypeLabel.textContent = '3 Loksabha';
                currentDataTypeInput.value = '3ls';
                updateButtonLabel.textContent = '3 Loksabha';
                dataEditAreaContainer.style.display = 'block';
                formFeedback.innerHTML = '';
            })
            .catch(error => {
                console.error('Error fetching 3 Loksabha:', error);
                showFeedback('Failed to load 3 Loksabha. Please try again.', 'error');
            });
        });

        // Add/Edit 2 Loksabha Button Handler
        addEdit2LsBtn.addEventListener('click', function() {
            const threeLsId = threeLsSelect.value;
            if (!threeLsId) {
                showFeedback('Please select a 3 Loksabha first.', 'error');
                return;
            }

            // Fetch existing 2 Loksabha for the selected 3 Loksabha
            fetch(`<?= site_url('admin/constituencies/get_2ls_by_3ls') ?>/${threeLsId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data['2ls']) {
                    const twoLsNames = data['2ls'].map(ls => ls.Name).join('\n');
                    dataTextArea.value = twoLsNames;
                } else {
                    dataTextArea.value = '';
                }
                
                // Show the edit area
                editingDataTypeLabel.textContent = '2 Loksabha';
                currentDataTypeInput.value = '2ls';
                updateButtonLabel.textContent = '2 Loksabha';
                dataEditAreaContainer.style.display = 'block';
                formFeedback.innerHTML = '';
            })
            .catch(error => {
                console.error('Error fetching 2 Loksabha:', error);
                showFeedback('Failed to load 2 Loksabha. Please try again.', 'error');
            });
        });

        // Add/Edit 1 Loksabha Button Handler
        addEdit1LsBtn.addEventListener('click', function() {
            const twoLsId = twoLsSelect.value;
            if (!twoLsId) {
                showFeedback('Please select a 2 Loksabha first.', 'error');
                return;
            }

            // Fetch existing 1 Loksabha for the selected 2 Loksabha
            fetch(`<?= site_url('admin/constituencies/get_1ls_by_2ls') ?>/${twoLsId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data['1ls']) {
                    const oneLsNames = data['1ls'].map(ls => ls.name).join('\n');
                    dataTextArea.value = oneLsNames;
                } else {
                    dataTextArea.value = '';
                }
                
                // Show the edit area
                editingDataTypeLabel.textContent = '1 Loksabha';
                currentDataTypeInput.value = '1ls';
                updateButtonLabel.textContent = '1 Loksabha';
                dataEditAreaContainer.style.display = 'block';
                formFeedback.innerHTML = '';
            })
            .catch(error => {
                console.error('Error fetching 1 Loksabha:', error);
                showFeedback('Failed to load 1 Loksabha. Please try again.', 'error');
            });
        });

        // Cancel Edit Button Handler
        cancelEditBtn.addEventListener('click', function() {
            dataEditAreaContainer.style.display = 'none';
            dataTextArea.value = '';
            formFeedback.innerHTML = '';
        });

        // Form Submit Handler
        editConstituencyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const dataType = currentDataTypeInput.value;

            let endpoint = '';
            let payload = {};

            if (dataType === '3ls') {
                const fourLsId = loksabhaSelect.value;
                if (!fourLsId) {
                    showFeedback('Please select a 4 Loksabha.', 'error');
                    return;
                }
                endpoint = '<?= site_url('admin/constituencies/add_3ls') ?>';
                const threeLsText = dataTextArea.value.trim();
                const threeLsArray = threeLsText ? threeLsText.split('\n').map(name => name.trim()).filter(name => name) : [];
                payload = {
                    '4ls_id': fourLsId,
                    'names': threeLsArray,
                    [csrfTokenName]: csrfTokenValue
                };
            } else if (dataType === '2ls') {
                const threeLsId = threeLsSelect.value;
                if (!threeLsId) {
                    showFeedback('Please select a 3 Loksabha.', 'error');
                    return;
                }
                endpoint = '<?= site_url('admin/constituencies/add_2ls') ?>';
                const twoLsText = dataTextArea.value.trim();
                const twoLsArray = twoLsText ? twoLsText.split('\n').map(name => name.trim()).filter(name => name) : [];
                payload = {
                    '3ls_id': threeLsId,
                    'names': twoLsArray,
                    [csrfTokenName]: csrfTokenValue
                };
            } else if (dataType === '1ls') {
                const twoLsId = twoLsSelect.value;
                if (!twoLsId) {
                    showFeedback('Please select a 2 Loksabha.', 'error');
                    return;
                }
                endpoint = '<?= site_url('admin/constituencies/add_1ls') ?>';
                const oneLsText = dataTextArea.value.trim();
                const oneLsArray = oneLsText ? oneLsText.split('\n').map(name => name.trim()).filter(name => name) : [];
                payload = {
                    '2ls_id': twoLsId,
                    'names': oneLsArray,
                    [csrfTokenName]: csrfTokenValue
                };
            } else {
                showFeedback('Invalid data type.', 'error');
                return;
            }

            // Send data to server
            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showFeedback(data.message || 'Data saved successfully.', 'success');
                    
                    // Refresh the data by re-fetching
                    if (dataType === '3ls') {
                        fetch(`<?= site_url('admin/constituencies/get_3ls_by_4ls') ?>/${fourLsId}`, {
                            method: 'GET',
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data['3ls']) {
                                const threeLsNames = data['3ls'].map(ls => ls.name).join('\n');
                                dataTextArea.value = threeLsNames;
                            } else {
                                dataTextArea.value = '';
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
            const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            formFeedback.innerHTML = `
                <div class="${alertClass} border px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">${message}</span>
                </div>
            `;
        }
    });
</script>
<?= $this->endSection() ?>
