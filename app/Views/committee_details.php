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
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden mb-12 border border-gray-100 transition-all duration-300 hover:shadow-2xl">
        <div class="p-6 sm:p-10">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                
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
                        <select id="level" name="level" class="block w-full pl-3 pr-8 py-2 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm transition-all duration-200" disabled>
                            <option value="">Select Level</option>
                            <?php 
                                $allowedLevels = [3, 5, 6, 7, 11, 16];
                                foreach ($levels as $level): 
                                    if (in_array($level['id'], $allowedLevels)):
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
    <div id="results-container" class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-200" style="display: none;">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-600 text-white">
                    <tr>
                         <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            S. No.
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            Post
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            Image
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            Name
                        </th>
                    </tr>
                </thead>
                <tbody id="results-body" class="bg-white divide-y divide-gray-200">
                    <!-- Rows will be populated via JS -->
                </tbody>
            </table>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
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
        }
         else if (levelId == 11) { // State Level
             // No further location needed, just State
        }
    });

    // Helper functions to load data
    function loadDistricts(stateId, elementId, label) {
        $(`#${elementId}`).html('<option value="">Loading...</option>');
        $(`#location-label`).text(label);
        $.get(`<?= base_url('auth/get-districts/') ?>${stateId}`, function(response) {
             let options = `<option value="">Select ${label}</option>`;
             const list = response.data || []; // Handle {success:true, data:[...]} format
             list.forEach(item => {
                 options += `<option value="${item.id}">${item.name}</option>`;
             });
             $(`#${elementId}`).html(options);
        });
    }

    function loadBlocks(districtId, elementId, label) {
        $(`#${elementId}`).html('<option value="">Loading...</option>');
         $(`#sub-location-label`).text(label);
        $.get(`<?= base_url('auth/get-blocks/') ?>${districtId}`, function(response) {
             let options = `<option value="">Select ${label}</option>`;
             const list = response.data || []; // Handle {success:true, data:[...]} format
             list.forEach(item => {
                 options += `<option value="${item.id}">${item.name}</option>`;
             });
             $(`#${elementId}`).html(options);
        });
    }
    
     function loadMlaAreas(districtId, elementId, label) {
        $(`#${elementId}`).html('<option value="">Loading...</option>');
         $(`#sub-location-label`).text(label);
        $.get(`<?= base_url('auth/get-mla-areas/') ?>${districtId}`, function(response) {
             let options = `<option value="">Select ${label}</option>`;
             const list = response.data || []; // Handle {success:true, data:[...]} format
             list.forEach(item => {
                 options += `<option value="${item.id}">${item.name}</option>`;
             });
             $(`#${elementId}`).html(options);
        });
    }
    
    function loadSectors(blockId, elementId, label) {
        $(`#${elementId}`).html('<option value="">Loading...</option>');
         $(`#sector-location-label`).text(label);
        $.get(`<?= base_url('committee/get-sectors/') ?>${blockId}`, function(data) {
             let options = `<option value="">Select ${label}</option>`;
             // CommitteeController returns array directly via respond() usually, but let's be safe
             const list = Array.isArray(data) ? data : (data.data || []);
             list.forEach(item => {
                 options += `<option value="${item.id}">${item.name}</option>`;
             });
             $(`#${elementId}`).html(options);
        });
    }

    function resetLocations() {
        $('#location-container').hide();
        $('#sub-location-container').hide();
        $('#sector-location-container').hide();
        
        // Reset and clear events to prevent cascading to unwanted levels
        $('#dynamic-location').html('<option value="">Select Location</option>').off('change');
        $('#sub-dynamic-location').html('<option value="">Select Sub Location</option>').off('change');
        $('#sector-dynamic-location').html('<option value="">Select Sector</option>').off('change');
        
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
    $(document).on('change', '#dynamic-location, #sub-dynamic-location, #sector-dynamic-location', checkButtonState);

    $('#search-btn').click(function() {
        const stateId = $('#state').val();
        const levelId = $('#level').val();
        
        if (!stateId || !levelId) {
            alert('Please select State and Level');
            return;
        }

        // Collect all potential location IDs
        const locationId = $('#dynamic-location').val();
        const subLocationId = $('#sub-dynamic-location').val(); // Could be Block or MLA Area
        const sectorId = $('#sector-dynamic-location').val();
        
        let data = {
            state_id: stateId,
            level_id: levelId
        };
        
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
        } else if (levelId == 6) { // MLA
             if(locationId) data.district_id = locationId;
             if(subLocationId) data.mla_area_id = subLocationId;
        }

        $('#loading').show();
        $('#results-container').hide(); // Hide container instead of emptying to keep table structure
        $('#results-body').empty();
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
                    response.forEach((member, index) => {
                        let photoHtml = '';
                        if (member.photo_url === 'No image') {
                             photoHtml = '<span class="text-gray-400 text-sm italic">No image</span>';
                        } else {
                             photoHtml = `<div class="flex-shrink-0 h-12 w-12">
                                            <img class="h-12 w-12 rounded-full object-cover ring-2 ring-white shadow-sm" src="${member.photo_url}" alt="${member.full_name}">
                                          </div>`;
                        }

                        let nameClass = member.full_name === 'Not appointed' ? 'text-red-500 italic font-medium' : 'text-gray-900 font-bold';
                        let rowClass = member.full_name === 'Not appointed' ? 'bg-red-50/50' : 'hover:bg-indigo-50/50';

                        html += `
                        <tr class="${rowClass} transition-colors duration-150 border-b border-gray-200 last:border-0">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">
                                ${index + 1}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-indigo-100 text-indigo-800 shadow-sm border border-indigo-200">
                                    ${member.post_name}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                ${photoHtml}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-base ${nameClass}">${member.full_name}</div>
                            </td>
                        </tr>
                        `;
                    });
                    $('#results-body').html(html);
                    $('#results-container').fadeIn();
                } else {
                    $('#no-results').fadeIn();
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
