<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Register - Step 1: Personal & Contact<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2 text-sm">
                <span class="text-blue-600 font-semibold">Step 1: Personal</span>
                <span class="text-gray-500">Step 2: Location</span>
                <span class="text-gray-500">Step 3: Address & Password</span>
                <span class="text-gray-500">Step 4: Apply for Post</span>
                <span class="text-gray-500">Step 5: Review Application</span>
            </div>
            <div class="h-2.5 bg-gray-200 rounded-full">
                <div class="h-2.5 bg-blue-600 rounded-full" style="width: 20%;"></div>
            </div>
        </div>

        <h1 class="text-2xl font-bold mb-6 text-center">Step 1: Personal & Contact Details</h1>

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

        <form action="<?= site_url('auth/process-step1') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <h2 class="text-xl font-semibold mb-4 border-b pb-2">Personal Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" id="first_name" value="<?= esc($savedData['first_name'] ?? old('first_name'), 'attr') ?>" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" id="last_name" value="<?= esc($savedData['last_name'] ?? old('last_name'), 'attr') ?>" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                    <input type="date" name="date_of_birth" id="date_of_birth" value="<?= esc($savedData['date_of_birth'] ?? old('date_of_birth'), 'attr') ?>" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                    <select name="gender" id="gender" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Gender</option>
                        <option value="male" <?= ($savedData['gender'] ?? old('gender')) === 'male' ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= ($savedData['gender'] ?? old('gender')) === 'female' ? 'selected' : '' ?>>Female</option>
                        <option value="other" <?= ($savedData['gender'] ?? old('gender')) === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Passport Size Photo <span class="text-red-500">*</span></label>
                    <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/jpg" <?= (isset($savedData['photo']) && $savedData['photo']) ? '' : 'required' ?>
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-1">Upload your recent passport-sized photo (JPG/PNG, max 2MB).</p>
                    <div id="photo-preview-container" class="mt-2">
                        <?php if (isset($savedData['photo']) && $savedData['photo']): ?>
                            <img src="<?= base_url('uploads/photos/' . esc($savedData['photo'], 'attr')) ?>" 
                                 alt="Current photo" class="h-24 w-auto rounded border p-1" id="photo-preview-img">
                            <p class="text-xs text-gray-500 mt-1">Current photo. Uploading a new one will replace this.</p>
                        <?php else: ?>
                            <img src="#" alt="Photo Preview" class="hidden h-24 w-auto rounded border p-1" id="photo-preview-img">
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <h2 class="text-xl font-semibold mb-4 mt-8 border-b pb-2">Contact Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                <div>
                    <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Number (Primary) <span class="text-red-500">*</span></label>
                    <input type="tel" name="mobile" id="mobile" value="<?= esc($savedData['mobile'] ?? old('mobile'), 'attr') ?>" required pattern="[6789][0-9]{9}" title="Enter a valid 10-digit Indian mobile number"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="alternate_mobile" class="block text-sm font-medium text-gray-700 mb-1">Alternate Number</label>
                    <input type="tel" name="alternate_mobile" id="alternate_mobile" value="<?= esc($savedData['alternate_mobile'] ?? old('alternate_mobile'), 'attr') ?>" pattern="[6789][0-9]{9}" title="Enter a valid 10-digit Indian mobile number"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="<?= esc($savedData['email'] ?? old('email'), 'attr') ?>" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <div id="email-availability-status" class="text-xs mt-1"></div>
                </div>
            </div>

            <div class="mt-8 flex justify-between items-center">
                 <a href="<?= site_url('auth/login') ?>" 
                   class="text-sm text-gray-600 hover:text-blue-600">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                    Next: Location &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Email availability check (from existing file)
    const emailInput = document.getElementById('email');
    const emailStatusDiv = document.getElementById('email-availability-status');
    if (emailInput) { /* ... (keep existing email check JS) ... */ }

    // Photo preview
    const photoInput = document.getElementById('photo');
    const photoPreviewImg = document.getElementById('photo-preview-img');
    if (photoInput && photoPreviewImg) {
        photoInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreviewImg.src = e.target.result;
                    photoPreviewImg.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
<?= $this->endSection() ?>