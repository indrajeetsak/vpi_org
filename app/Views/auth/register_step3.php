<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Register - Step 3: Address & Password<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2 text-sm">
                <span class="text-gray-500">Step 1: Personal</span>
                <span class="text-gray-500">Step 2: Location</span>
                <span class="text-blue-600 font-semibold">Step 3: Address & Password</span>
                <span class="text-gray-500">Step 4: Apply for Post</span>
                <span class="text-gray-500">Step 5: Review Application</span>
            </div>
            <div class="h-2.5 bg-gray-200 rounded-full">
                <div class="h-2.5 bg-blue-600 rounded-full" style="width: 60%;"></div>
            </div>
        </div>

        <h1 class="text-2xl font-bold mb-6 text-center">Step 3: Address & Password</h1>

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

        <form action="<?= site_url('auth/process-step3') ?>" method="POST">
            <?= csrf_field() ?>

            <h2 class="text-xl font-semibold mb-4 border-b pb-2">Address Details</h2>
            <div class="grid grid-cols-1 gap-y-4 mb-6">
                <div>
                    <label for="address_line1" class="block text-sm font-medium text-gray-700 mb-1">Address Line 1 <span class="text-red-500">*</span></label>
                    <input type="text" name="address_line1" id="address_line1" value="<?= esc($savedData['address_line1'] ?? old('address_line1'), 'attr') ?>" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="address_line2" class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                    <input type="text" name="address_line2" id="address_line2" value="<?= esc($savedData['address_line2'] ?? old('address_line2'), 'attr') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="pin_code" class="block text-sm font-medium text-gray-700 mb-1">Pin Code <span class="text-red-500">*</span></label>
                    <input type="text" name="pin_code" id="pin_code" value="<?= esc($savedData['pin_code'] ?? old('pin_code'), 'attr') ?>" required pattern="\d{6}" title="Enter 6 digit PIN code"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <h2 class="text-xl font-semibold mb-4 mt-8 border-b pb-2">Login Credentials</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">User ID (Mobile Number)</label>
                    <input type="text" id="user_id" value="<?= esc($savedData['mobile'] ?? 'Auto-filled from Step 1', 'attr') ?>" disabled
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-500">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="password" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Min. 8 chars, incl. uppercase, lowercase, number, special char.</p>
                    <!-- TODO: Add password strength indicator -->
                </div>
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                    <input type="password" name="confirm_password" id="confirm_password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="mt-8 flex justify-between">
                <button type="button" onclick="window.location.href='<?= site_url('auth/register?step=2') ?>'"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-md">
                    &larr; Previous
                </button>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md focus:outline-none focus:shadow-outline">
                    Apply for Post &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // TODO: Add JavaScript for password strength indicator and show/hide password toggle
});
</script>
<?= $this->endSection() ?>