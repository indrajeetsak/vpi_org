<div class="p-8">
    <h2 class="text-3xl font-extrabold text-gray-900 mb-6 border-b pb-3">Application Details for <?= esc($user['first_name']) ?> <?= esc($user['last_name']) ?></h2>

    <div class="bg-gray-50 shadow-md rounded-lg p-6 mb-6 border border-gray-200">
        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Personal Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-6 text-gray-700 leading-relaxed">
            <div><strong>Full Name:</strong> <span class="text-gray-900"><?= esc($user['first_name']) ?> <?= esc($user['last_name']) ?></span></div>
            <div><strong>Email:</strong> <span class="text-gray-900"><?= esc($user['email']) ?></span></div>
            <div><strong>Mobile:</strong> <span class="text-gray-900"><?= esc($user['mobile']) ?></span></div>
            <div><strong>Alternate Mobile:</strong> <span class="text-gray-900"><?= esc($user['alternate_mobile'] ?? 'N/A') ?></span></div>
            <div><strong>Date of Birth:</strong> <span class="text-gray-900"><?= esc($user['date_of_birth'] ?? 'N/A') ?></span></div>
            <div><strong>Gender:</strong> <span class="text-gray-900"><?= esc($user['gender'] ?? 'N/A') ?></span></div>
            <div><strong>Father's Name:</strong> <span class="text-gray-900"><?= esc($user['father_name'] ?? 'N/A') ?></span></div>
            <div><strong>Mother's Name:</strong> <span class="text-gray-900"><?= esc($user['mother_name'] ?? 'N/A') ?></span></div>
            <div><strong>Aadhaar Number:</strong> <span class="text-gray-900"><?= esc($user['aadhaar_number'] ?? 'N/A') ?></span></div>
        </div>
    </div>

    <div class="bg-gray-50 shadow-md rounded-lg p-6 mb-6 border border-gray-200">
        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Address Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-6 text-gray-700 leading-relaxed">
            <div><strong>Address Line 1:</strong> <span class="text-gray-900"><?= esc($user['address_line1'] ?? 'N/A') ?></span></div>
            <div><strong>Address Line 2:</strong> <span class="text-gray-900"><?= esc($user['address_line2'] ?? 'N/A') ?></span></div>
            <div><strong>State:</strong> <span class="text-gray-900"><?= esc($user['state_name'] ?? 'N/A') ?></span></div>
            <div><strong>District:</strong> <span class="text-gray-900"><?= esc($user['district_name'] ?? 'N/A') ?></span></div>
            <div><strong>MLA Area:</strong> <span class="text-gray-900"><?= esc($user['mla_area_name'] ?? 'N/A') ?></span></div>
            <div><strong>Block:</strong> <span class="text-gray-900"><?= esc($user['block_name'] ?? 'N/A') ?></span></div>
            <div><strong>PIN Code:</strong> <span class="text-gray-900"><?= esc($user['pin_code'] ?? 'N/A') ?></span></div>
        </div>
    </div>

    <div class="bg-gray-50 shadow-md rounded-lg p-6 mb-6 border border-gray-200">
        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Application Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-6 text-gray-700 leading-relaxed">
            <div><strong>Organ:</strong> <span class="text-gray-900"><?= esc($user['organ_name'] ?? 'N/A') ?></span></div>
            <div><strong>Level:</strong> <span class="text-gray-900"><?= esc($user['level_name'] ?? 'N/A') ?></span></div>
            <div><strong>Post:</strong> <span class="text-gray-900"><?= esc($user['post_name'] ?? 'N/A') ?></span></div>
            <div><strong>Application Status:</strong> <span class="text-gray-900"><?= ucfirst(esc($user['status'] ?? 'N/A')) ?></span></div>
            <div><strong>Registered At:</strong> <span class="text-gray-900"><?= esc($user['created_at'] ?? 'N/A') ?></span></div>
            <div><strong>Last Updated:</strong> <span class="text-gray-900"><?= esc($user['updated_at'] ?? 'N/A') ?></span></div>
        </div>
    </div>

    <div class="flex justify-end mt-8">
        <button type="button" class="close-modal bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg shadow-md inline-flex items-center transition duration-300 ease-in-out">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            Close
        </button>
    </div>
</div>