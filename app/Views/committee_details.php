<?= $this->extend('layouts/modern') ?>

<?= $this->section('title') ?>Committee Details<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 sm:text-4xl">
            Committee Details
        </h1>
        <p class="mt-4 text-xl text-gray-500">
            Select a state and level to view committee members.
        </p>
    </div>

    <!-- Filters Section -->
    <div class="bg-white shadow-xl rounded-2xl overflow-visible mb-12 border border-gray-100 transition-all duration-300 hover:shadow-2xl">
        <div class="p-6 sm:p-10">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                
                <!-- Organ Selection -->
                <div class="sm:col-span-1">
                    <label for="organ" class="block text-sm font-semibold text-gray-700 mb-2">Organ</label>
                    <div class="relative">
                         <select id="organ" name="organ" class="block w-full pl-3 pr-8 py-2 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm transition-all duration-200">
                            <option value="">Select Organ</option>
                            <?php foreach ($organs as $organ): ?>
                                <option value="<?= $organ['id'] ?>"><?= esc($organ['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                 <!-- Front Selection -->
                 <div class="sm:col-span-1" id="front-container" style="display: none;">
                    <label for="front" class="block text-sm font-semibold text-gray-700 mb-2">Front</label>
                    <div class="relative">
                         <select id="front" name="front" class="block w-full pl-3 pr-8 py-2 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm transition-all duration-200">
                            <option value="">Select Front</option>
                            <?php foreach ($fronts as $front): ?>
                                <option value="<?= $front['id'] ?>"><?= esc($front['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- State Selection -->
                <div class="sm:col-span-1">
                    <label for="state" class="block text-sm font-semibold text-gray-700 mb-2">State</label>
                    <div class="relative">
                        <select id="state" name="state" class="block w-full pl-3 pr-8 py-2 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm transition-all duration-200">
                            <option value="">Select State</option>
                            <?php foreach ($states as $state): ?>
                                <option value="<?= $state['id'] ?>"><?= esc($state['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Level Selection -->
                <div class="sm:col-span-1">
                    <label for="level" class="block text-sm font-semibold text-gray-700 mb-2">Level</label>
                    <div class="relative">
                         <select id="level" name="level" class="block w-full pl-3 pr-8 py-2 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm transition-all duration-200">
                            <option value="">Select Level</option>
                            <?php 
                                // Allowed IDs: Polling Booth(1), Village(2), Sector(3), Circle(4), Block(5), MLA(6), MP(7), State(11), District(16)
                                $allowedLevels = [1, 2, 3, 4, 5, 6, 7, 11, 16];
                                foreach ($levels as $level): 
                                    // Robust check: Convert both to int for comparison or check in_array loosely
                                    // Using loose comparison by default in_array is safer for string/int mix
                                    if (in_array($level['id'], $allowedLevels) || in_array((int)$level['id'], $allowedLevels)):
                            ?>
                                <option value="<?= $level['id'] ?>"><?= esc($level['name']) ?></option>
                            <?php 
                                    endif;
                                endforeach; 
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Dynamic Locations (District, Block, etc.) -->
                <div class="sm:col-span-1" id="location-container" style="display: none;">
                    <label for="dynamic-location" class="block text-sm font-semibold text-gray-700 mb-2" id="location-label">Location</label>
                    <div class="relative">
                         <select id="dynamic-location" name="dynamic_location" class="block w-full pl-3 pr-8 py-2 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm transition-all duration-200">
                            <option value="">Select Location</option>
                        </select>
                    </div>
                </div>
                 <!-- Additional Dynamic Locations (Block, MLA, etc.) -->
                <div class="sm:col-span-1" id="sub-location-container" style="display: none;">
                     <label for="sub-dynamic-location" class="block text-sm font-semibold text-gray-700 mb-2" id="sub-location-label">Sub Location</label>
                    <div class="relative">
                         <select id="sub-dynamic-location" name="sub_dynamic_location" class="block w-full pl-3 pr-8 py-2 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm transition-all duration-200">
                            <option value="">Select Sub Location</option>
                        </select>
                    </div>
                </div>

                <!-- Sector Location -->
                 <div class="sm:col-span-1" id="sector-location-container" style="display: none;">
                     <label for="sector-dynamic-location" class="block text-sm font-semibold text-gray-700 mb-2" id="sector-location-label">Sector</label>
                    <div class="relative">
                         <select id="sector-dynamic-location" name="sector_dynamic_location" class="block w-full pl-3 pr-8 py-2 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm transition-all duration-200">
                            <option value="">Select Sector</option>
                        </select>
                    </div>
                </div>

                <!-- Circle Location -->
                 <div class="sm:col-span-1" id="circle-location-container" style="display: none;">
                     <label for="circle-dynamic-location" class="block text-sm font-semibold text-gray-700 mb-2" id="circle-location-label">Circle</label>
                    <div class="relative">
                         <select id="circle-dynamic-location" name="circle_dynamic_location" class="block w-full pl-3 pr-8 py-2 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm transition-all duration-200">
                            <option value="">Select Circle</option>
                        </select>
                    </div>
                </div>

                <!-- Village Location -->
                 <div class="sm:col-span-1" id="village-location-container" style="display: none;">
                     <label for="village-dynamic-location" class="block text-sm font-semibold text-gray-700 mb-2" id="village-location-label">Village</label>
                    <div class="relative">
                         <select id="village-dynamic-location" name="village_dynamic_location" class="block w-full pl-3 pr-8 py-2 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm transition-all duration-200">
                            <option value="">Select Village</option>
                        </select>
                    </div>
                </div>

                <!-- Polling Booth Location -->
                 <div class="sm:col-span-1" id="polling-location-container" style="display: none;">
                     <label for="polling-dynamic-location" class="block text-sm font-semibold text-gray-700 mb-2" id="polling-location-label">Polling Booth</label>
                    <div class="relative">
                         <select id="polling-dynamic-location" name="polling_dynamic_location" class="block w-full pl-3 pr-8 py-2 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm transition-all duration-200">
                            <option value="">Select Polling Booth</option>
                        </select>
                    </div>
                </div>
                <!-- Search Button -->
                <div class="sm:col-span-5 text-right mt-4">
                    <button type="button" id="search-btn" disabled class="inline-flex justify-center py-2 px-6 border border-transparent shadow-md text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                        Show Committee
                    </button>
                </div>
            </div>
        </div>
        </div>
    </div>

    <!-- Results Section -->
    <div id="results-container" style="display: none;">
        <div id="results-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Cards will be populated via JS -->
        </div>
    </div>
    
    <div id="no-results" class="text-center py-12" style="display: none;">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="mt-4 text-lg font-medium text-gray-900">No members found</p>
        <p class="text-gray-500">Try adjusting your search criteria.</p>
    </div>
     <div id="loading" class="text-center py-12" style="display: none;">
        <svg class="animate-spin mx-auto h-10 w-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="mt-4 text-gray-500 text-lg">Loading members...</p>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<style>
    /* Tom Select dropdown list scroll */
    .ts-dropdown .ts-dropdown-content {
        max-height: 320px !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch;
    }
    /* Scrollbar styling */
    .ts-dropdown .ts-dropdown-content::-webkit-scrollbar {
        width: 8px;
    }
    .ts-dropdown .ts-dropdown-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    .ts-dropdown .ts-dropdown-content::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    .ts-dropdown .ts-dropdown-content::-webkit-scrollbar-thumb:hover {
        background: #888;
    }
    /* Ensure dropdown overlays content below */
    .ts-dropdown {
        z-index: 9999 !important;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let pollingBoothChoices = null;

    // Enable/Disable Level based on State
    $('#state').change(function() {
        if ($(this).val()) {
            $('#level').prop('disabled', false);
        } else {
            $('#level').prop('disabled', true).val('');
            resetLocations();
        }
    });

    // Handle Level Change
    $('#level').change(function() {
        const levelId = $(this).val();
        const stateId = $('#state').val();
         resetLocations();

        if (!levelId || !stateId) return;
        
        // Logic to show appropriate location dropdowns
        // Assuming Level IDs:
        // Sector => 3
        // Block => 5
        // District => 11
        // MLA Constituency => 6
        // State => 12

        if (levelId == 16) { // District Level
             loadDistricts(stateId, 'dynamic-location', 'District');
             $('#location-container').fadeIn();
        } else if (levelId == 5) { // Block Level (ID 5)
             loadDistricts(stateId, 'dynamic-location', 'District'); // First select District
             $('#location-container').fadeIn();
             
             // When district selected, show blocks
             $('#dynamic-location').off('change').on('change', function() {
                 const districtId = $(this).val();
                 if(districtId) {
                     loadBlocks(districtId, 'sub-dynamic-location', 'Block');
                      $('#sub-location-container').fadeIn();
                 } else {
                      $('#sub-location-container').hide();
                 }
             });

        } else if (levelId == 3) { // Sector Level (ID 3)
             loadDistricts(stateId, 'dynamic-location', 'District'); // First select District
             $('#location-container').fadeIn();
             
              $('#dynamic-location').off('change').on('change', function() {
                 const districtId = $(this).val();
                 if(districtId) {
                     loadBlocks(districtId, 'sub-dynamic-location', 'Block');
                      $('#sub-location-container').fadeIn();
                 } else {
                      $('#sub-location-container').hide();
                      $('#sector-location-container').hide();
                 }
             });
             
             // When block selected, show sectors
             $('#sub-dynamic-location').off('change').on('change', function() {
                 const blockId = $(this).val();
                 if(blockId) {
                     loadSectors(blockId, 'sector-dynamic-location', 'Sector');
                      $('#sector-location-container').fadeIn();
                 } else {
                      $('#sector-location-container').hide();
                 }
             });
        } 
         else if (levelId == 6) { // MLA Constituency
             loadDistricts(stateId, 'dynamic-location', 'District');
             $('#location-container').fadeIn();
             
              $('#dynamic-location').off('change').on('change', function() {
                 const districtId = $(this).val();
                 if(districtId) {
                     loadMlaAreas(districtId, 'sub-dynamic-location', 'MLA Area');
                      $('#sub-location-container').fadeIn();
                 } else {
                      $('#sub-location-container').hide();
                 }
             });
        } else if (levelId == 4) { // Circle Level (ID 4)
             loadDistricts(stateId, 'dynamic-location', 'District'); // First select District
             $('#location-container').fadeIn();
             
              $('#dynamic-location').off('change').on('change', function() {
                 const districtId = $(this).val();
                 if(districtId) {
                     loadBlocks(districtId, 'sub-dynamic-location', 'Block');
                      $('#sub-location-container').fadeIn();
                 } else {
                      $('#sub-location-container').hide();
                      $('#circle-location-container').hide();
                 }
             });
             
             // When block selected, show circles
             $('#sub-dynamic-location').off('change').on('change', function() {
                 const blockId = $(this).val();
                 if(blockId) {
                     loadCircles('circle-dynamic-location', 'Circle');
                      $('#circle-location-container').fadeIn();
                 } else {
                      $('#circle-location-container').hide();
                 }
             });
        } else if (levelId == 11) { // State Level
             // No further location needed, just State
        } else if (levelId == 2) { // Village Level (ID 2)
             loadDistricts(stateId, 'dynamic-location', 'District'); // First select District
             $('#location-container').fadeIn();
             
              $('#dynamic-location').off('change').on('change', function() {
                 const districtId = $(this).val();
                 if(districtId) {
                     loadBlocks(districtId, 'sub-dynamic-location', 'Block');
                      $('#sub-location-container').fadeIn();
                 } else {
                      $('#sub-location-container').hide();
                      $('#sector-location-container').hide();
                      $('#village-location-container').hide();
                 }
             });
             
             // When block selected, show sectors
             $('#sub-dynamic-location').off('change').on('change', function() {
                 const blockId = $(this).val();
                 if(blockId) {
                     loadSectors(blockId, 'sector-dynamic-location', 'Sector');
                      $('#sector-location-container').fadeIn();
                 } else {
                      $('#sector-location-container').hide();
                      $('#village-location-container').hide();
                 }
             });

             // When sector selected, show villages
             $('#sector-dynamic-location').off('change').on('change', function() {
                 const sectorId = $(this).val();
                 if(sectorId) {
                     loadVillages(sectorId, 'village-dynamic-location', 'Village');
                      $('#village-location-container').fadeIn();
                 } else {
                      $('#village-location-container').hide();
                 }
             });
        } else if (levelId == 1) { // Polling Booth Level (ID 1)
             loadDistricts(stateId, 'dynamic-location', 'District');
             $('#location-container').fadeIn();
             
              $('#dynamic-location').off('change').on('change', function() {
                 const districtId = $(this).val();
                 if(districtId) {
                     loadMlaAreas(districtId, 'sub-dynamic-location', 'MLA Area');
                      $('#sub-location-container').fadeIn();
                 } else {
                      $('#sub-location-container').hide();
                      $('#polling-location-container').hide();
                 }
             });
             
             $('#sub-dynamic-location').off('change').on('change', function() {
                 const mlaId = $(this).val();
                 if(mlaId) {
                     loadPollingBooths(mlaId, 'polling-dynamic-location', 'Polling Booth');
                      $('#polling-location-container').fadeIn();
                 } else {
                      $('#polling-location-container').hide();
                 }
             });
        }
    });

    // Helper functions to load data
    function loadDistricts(stateId, elementId, label) {
        $(`#${elementId}`).html('<option value="">Loading...</option>');
        $(`#location-label`).text(label);
        $.ajax({
            url: `<?= base_url('auth/get-districts/') ?>${stateId}`,
            method: 'GET',
            complete: function(xhr) {
                 const response = xhr.responseText;
                 let options = `<option value="">Select ${label}</option>`;
                 let res = { data: [] };
                 try {
                     res = typeof response === 'string' ? JSON.parse(response) : response;
                 } catch (e) {}
                 const list = res.data || [];
                 list.forEach(item => {
                     options += `<option value="${item.id}">${item.name}</option>`;
                 });
                 $(`#${elementId}`).html(options);
            }
        });
    }

    function loadBlocks(districtId, elementId, label) {
        $(`#${elementId}`).html('<option value="">Loading...</option>');
         $(`#sub-location-label`).text(label);
        $.ajax({
            url: `<?= base_url('auth/get-blocks/') ?>${districtId}`,
            method: 'GET',
            complete: function(xhr) {
                 const response = xhr.responseText;
                 let options = `<option value="">Select ${label}</option>`;
                 let res = { data: [] };
                 try {
                     res = typeof response === 'string' ? JSON.parse(response) : response;
                 } catch (e) {}
                 const list = res.data || [];
                 list.forEach(item => {
                     options += `<option value="${item.id}">${item.name}</option>`;
                 });
                 $(`#${elementId}`).html(options);
            }
        });
    }
    
     function loadMlaAreas(districtId, elementId, label) {
        $(`#${elementId}`).html('<option value="">Loading...</option>');
         $(`#sub-location-label`).text(label);
        $.ajax({
            url: `<?= base_url('auth/get-mla-areas/') ?>${districtId}`,
            method: 'GET',
            complete: function(xhr) {
                 const response = xhr.responseText;
                 let options = `<option value="">Select ${label}</option>`;
                 let res = { data: [] };
                 try {
                     res = typeof response === 'string' ? JSON.parse(response) : response;
                 } catch (e) {}
                 const list = res.data || [];
                 list.forEach(item => {
                     options += `<option value="${item.id}">${item.name}</option>`;
                 });
                 $(`#${elementId}`).html(options);
            }
        });
    }
    
    function loadSectors(blockId, elementId, label) {
        $(`#${elementId}`).html('<option value="">Loading...</option>');
         $(`#sector-location-label`).text(label);
        $.ajax({
            url: `<?= base_url('committee/get-sectors/') ?>${blockId}`,
            method: 'GET',
            complete: function(xhr) {
                 const data = xhr.responseText;
                 let options = `<option value="">Select ${label}</option>`;
                 let res = { data: [] };
                 try {
                     res = typeof data === 'string' ? JSON.parse(data) : data;
                 } catch (e) {}
                 const list = Array.isArray(res) ? res : (res.data || []);
                 list.forEach(item => {
                     options += `<option value="${item.id}">${item.name}</option>`;
                 });
                 $(`#${elementId}`).html(options);
            }
        });
    }

    function loadCircles(elementId, label) {
        $(`#${elementId}`).html('<option value="">Loading...</option>');
         $(`#circle-location-label`).text(label);
        $.ajax({
            url: `<?= base_url('committee/get-all-circles') ?>`,
            method: 'GET',
            complete: function(xhr) {
                 const data = xhr.responseText;
                 let options = `<option value="">Select ${label}</option>`;
                 let res = { circles: [] };
                 try {
                     res = typeof data === 'string' ? JSON.parse(data) : data;
                 } catch (e) {}
                 const list = res.circles || []; 
                 list.forEach(item => {
                     options += `<option value="${item.id}">${item.name}</option>`;
                 });
                 $(`#${elementId}`).html(options);
            }
        });
    }

    function loadVillages(sectorId, elementId, label) {
        $(`#${elementId}`).html('<option value="">Loading...</option>');
         $(`#village-location-label`).text(label);
        $.ajax({
            url: `<?= base_url('committee/get-villages-by-sector/') ?>${sectorId}`,
            method: 'GET',
            complete: function(xhr) {
                 const data = xhr.responseText;
                 let options = `<option value="">Select ${label}</option>`;
                 let res = { villages: [] };
                 try {
                     res = typeof data === 'string' ? JSON.parse(data) : data;
                 } catch (e) {}
                 const list = res.villages || [];
                 list.forEach(item => {
                     options += `<option value="${item.id}">${item.name}</option>`;
                 });
                 $(`#${elementId}`).html(options);
            }
        });
    }

    function loadPollingBooths(mlaId, elementId, label) {
        // Destroy existing Tom Select instance if any
        if (pollingBoothChoices) {
            pollingBoothChoices.destroy();
            pollingBoothChoices = null;
        }
        $(`#${elementId}`).html('<option value="">Loading...</option>');
        $(`#polling-location-label`).text(label);
        $.ajax({
            url: `<?= base_url('auth/get-polling-booths/') ?>${mlaId}`,
            method: 'GET',
            complete: function(xhr) {
                 const response = xhr.responseText;
                 let options = `<option value="">Select ${label}</option>`;
                 let res = { data: [] };
                 try {
                     res = typeof response === 'string' ? JSON.parse(response) : response;
                 } catch (e) {}
                 const list = res.data || [];
                 list.forEach(item => {
                     options += `<option value="${item.id}">${item.name}</option>`;
                 });
                 $(`#${elementId}`).html(options);

                 // Initialize Tom Select (replaces Choices.js)
                 pollingBoothChoices = new TomSelect(`#${elementId}`, {
                     placeholder: `Select ${label}`,
                     searchField: 'text',
                     maxOptions: null,
                     plugins: [],
                     render: {
                         no_results: function(data, escape) {
                             return '<div class="no-results">No results found for "' + escape(data.input) + '"</div>';
                         }
                     },
                     onItemAdd: function() { checkButtonState(); }
                 });
            }
        });
    }

    function resetLocations() {
        $('#location-container').hide();
        $('#sub-location-container').hide();
        $('#sector-location-container').hide();
        $('#circle-location-container').hide();
        $('#village-location-container').hide();
        $('#polling-location-container').hide();
        
        if (pollingBoothChoices) {
            pollingBoothChoices.destroy();
            pollingBoothChoices = null;
        }
        
        // Reset and clear events to prevent cascading to unwanted levels
        $('#dynamic-location').html('<option value="">Select Location</option>').off('change');
        $('#sub-dynamic-location').html('<option value="">Select Sub Location</option>').off('change');
        $('#sector-dynamic-location').html('<option value="">Select Sector</option>').off('change');
        $('#circle-dynamic-location').html('<option value="">Select Circle</option>').off('change');
        $('#village-dynamic-location').html('<option value="">Select Village</option>').off('change');
        $('#polling-dynamic-location').html('<option value="">Select Polling Booth</option>').off('change');
        
        checkButtonState(); // Check state on reset
    }

    function checkButtonState() {
        const levelId = $('#level').val();
        let enable = false;

        if (levelId == 11) { // State
            enable = !!$('#state').val();
        } else if (levelId == 16) { // District
            enable = !!$('#dynamic-location').val();
        } else if (levelId == 5) { // Block
            enable = !!$('#sub-dynamic-location').val();
        } else if (levelId == 3) { // Sector
            enable = !!$('#sector-dynamic-location').val();
        } else if (levelId == 4) { // Circle
            enable = !!$('#circle-dynamic-location').val();
        } else if (levelId == 2) { // Village
            enable = !!$('#village-dynamic-location').val();
        } else if (levelId == 1) { // Polling Booth
            enable = !!$('#polling-dynamic-location').val();
        } else if (levelId == 6) { // MLA
            enable = !!$('#sub-dynamic-location').val();
        }

        if (enable) {
            $('#search-btn').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
        } else {
            $('#search-btn').prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
        }
    }

    // Attach checkButtonState to all dropdowns
    $('#state, #level').change(checkButtonState);
    
    // We need to attach to dynamic dropdowns as they change. 
    // Since we re-create options, we can use a delegated event or attach in the load callbacks.
    // Delegated event is cleaner.
    $(document).on('change', '#dynamic-location, #sub-dynamic-location, #sector-dynamic-location, #circle-dynamic-location, #village-dynamic-location, #polling-dynamic-location', checkButtonState);

    // Organ Change
    $('#organ').change(function() {
        const organText = $(this).find('option:selected').text().toLowerCase().trim();
        if (organText === 'front') {
            $('#front-container').fadeIn();
        } else {
            $('#front-container').hide();
            $('#front').val('');
        }
    });

    $('#search-btn').click(function() {
        const stateId = $('#state').val();
        const levelId = $('#level').val();
        const organId = $('#organ').val();
        const frontId = $('#front').val();
        
        if (!stateId || !levelId) {
            alert('Please select State and Level');
            return;
        }

        // Collect all potential location IDs
        const locationId = $('#dynamic-location').val();
        const subLocationId = $('#sub-dynamic-location').val(); // Could be Block or MLA Area
        const sectorId = $('#sector-dynamic-location').val();
        const circleId = $('#circle-dynamic-location').val();
        const villageId = $('#village-dynamic-location').val();
        const pollingId = $('#polling-dynamic-location').val();
        
        let data = {
            state_id: stateId,
            level_id: levelId
        };

        if (organId) data.organ_id = organId;
        if (frontId) data.front_id = frontId;
        
        // Map dynamic fields to specific IDs based on Level
        if (levelId == 16) { // District
             if(locationId) data.district_id = locationId;
        } else if (levelId == 5) { // Block
             if(locationId) data.district_id = locationId;
             if(subLocationId) data.block_id = subLocationId;
        } else if (levelId == 3) { // Sector
             if(locationId) data.district_id = locationId;
             if(subLocationId) data.block_id = subLocationId;
             if(sectorId) data.sector_id = sectorId;
        } else if (levelId == 4) { // Circle
             if(locationId) data.district_id = locationId;
             if(subLocationId) data.block_id = subLocationId;
             if(circleId) data.circle_id = circleId;
        } else if (levelId == 2) { // Village
             if(locationId) data.district_id = locationId;
             if(subLocationId) data.block_id = subLocationId;
             if(sectorId) data.sector_id = sectorId;
             if(villageId) data.village_id = villageId;
        } else if (levelId == 6) { // MLA
             if(locationId) data.district_id = locationId;
             if(subLocationId) data.mla_area_id = subLocationId;
        } else if (levelId == 1) { // Polling Booth
             if(locationId) data.district_id = locationId;
             if(subLocationId) data.mla_area_id = subLocationId;
             if(pollingId) data.polling_booth_id = pollingId;
        }

        $('#loading').show();
        $('#results-container').hide(); 
        $('#results-grid').empty();
        $('#no-results').hide();

        $.ajax({
            url: '<?= base_url('committee/get-members') ?>',
            method: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(response) {
                $('#loading').hide();
                if (response.length > 0) {
                    let html = '';
                    response.forEach((member) => {
                        let photoHtml = '';
                        // Use a larger image for the card
                        if (member.photo_url === 'No image') {
                             photoHtml = `<div class="h-28 w-24 rounded-lg bg-gray-200 flex items-center justify-center text-gray-500">
                                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                          </div>`;
                        } else {
                             photoHtml = `<img class="h-28 w-24 rounded-lg object-cover shadow-sm border border-gray-200" src="${member.photo_url}" alt="${member.full_name}">`;
                        }

                        let isAppointed = member.full_name !== 'Not appointed';
                        let nameClass = isAppointed ? 'text-green-900 font-bold' : 'text-red-500 italic font-medium';
                        let postClass = 'text-gray-900 font-semibold'; // Post name in red/specific color

                        html += `
                        <div class="bg-white rounded-xl shadow border border-gray-200 p-4 transition duration-300 hover:shadow-lg flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                ${photoHtml}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-base ${nameClass} truncate" title="${member.full_name}">
                                    ${member.full_name}
                                </p>
                                <p class="text-xs ${postClass} uppercase font-bold tracking-wide mt-1 truncate" title="${member.post_name}">
                                    ${member.post_name}
                                </p>
                            </div>
                        </div>
                        `;
                    });
                    $('#results-grid').html(html);
                    $('#results-container').fadeIn();
                    $('html, body').animate({
                        scrollTop: $('#results-container').offset().top - 50
                    }, 500);
                } else {
                    $('#no-results').fadeIn();
                    $('html, body').animate({
                        scrollTop: $('#no-results').offset().top - 50
                    }, 500);
                }
            },
            error: function(xhr, status, error) {
                $('#loading').hide();
                console.error(error);
                alert('Error fetching members. Please try again.');
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
