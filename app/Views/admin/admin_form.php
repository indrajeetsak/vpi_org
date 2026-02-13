<?= $this->extend('layouts/admin_modern') ?>

<?= $this->section('title') ?><?= isset($admin) ? 'Edit Admin' : 'Add New Admin' ?><?= $this->endSection() ?>

<?= $this->section('headerTitle') ?><?= isset($admin) ? 'Edit Admin' : 'Add New Admin' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700"><?= isset($admin) ? 'Edit Admin' : 'Create New Admin' ?></h2>
        <a href="<?= base_url('admin/manage-admins') ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded shadow-md transition duration-300 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-sm" role="alert">
            <p class="font-bold">Please fix the following errors:</p>
            <ul class="list-disc list-inside">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden max-w-2xl mx-auto">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-800">Admin Details</h3>
        </div>
        <div class="p-6">
            <form action="<?= isset($admin) ? base_url('admin/update-admin/' . $admin['id']) : base_url('admin/store-admin') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Name
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500" id="name" type="text" name="name" value="<?= old('name', $admin['name'] ?? '') ?>" required placeholder="Enter admin name">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email Address
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500" id="email" type="email" name="email" value="<?= old('email', $admin['email'] ?? '') ?>" required placeholder="Enter email address">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="mobile">
                        Mobile Number
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500" id="mobile" type="text" name="mobile" value="<?= old('mobile', $admin['mobile'] ?? '') ?>" required pattern="[0-9]{10}" title="10 digit mobile number" placeholder="Enter 10-digit mobile number">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500" id="password" type="password" name="password" <?= isset($admin) ? '' : 'required' ?> placeholder="<?= isset($admin) ? 'Leave blank to keep current password' : 'Enter password' ?>">
                    <?php if (isset($admin)): ?>
                        <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password.</p>
                    <?php endif; ?>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="admin_type">
                        Admin Type
                    </label>
                    <div class="relative">
                        <select class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500" id="admin_type" name="admin_type" required>
                            <option value="1" <?= (old('admin_type', $admin['admin_type'] ?? '') == 1) ? 'selected' : '' ?>>Super Admin</option>
                            <option value="2" <?= (old('admin_type', $admin['admin_type'] ?? '') == 2) ? 'selected' : '' ?>>Admin</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-300" type="submit">
                        <?= isset($admin) ? 'Update Admin' : 'Create Admin' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
