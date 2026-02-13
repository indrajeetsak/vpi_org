<?= $this->extend('layouts/admin_modern') ?>

<?= $this->section('title') ?>Manage Admins<?= $this->endSection() ?>

<?= $this->section('headerTitle') ?>Admin Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700">Manage Administrators</h2>
        <a href="<?= base_url('admin/create-admin') ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-300 flex items-center">
            <i class="fas fa-plus mr-2"></i> Add New Admin
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm" role="alert">
            <p><?= session()->getFlashdata('success') ?></p>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-sm" role="alert">
            <p><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-800">Administrator List</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 font-semibold">ID</th>
                        <th class="py-3 px-6 font-semibold">Name</th>
                        <th class="py-3 px-6 font-semibold">Email</th>
                        <th class="py-3 px-6 font-semibold">Mobile</th>
                        <th class="py-3 px-6 font-semibold">Type</th>
                        <th class="py-3 px-6 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    <?php foreach ($admins as $admin): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition duration-150">
                            <td class="py-3 px-6 text-left whitespace-nowrap">
                                <span class="font-medium"><?= $admin['id'] ?></span>
                            </td>
                            <td class="py-3 px-6 text-left">
                                <div class="flex items-center">
                                    <div class="mr-2">
                                        <div class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 font-bold uppercase">
                                            <?= substr($admin['name'], 0, 1) ?>
                                        </div>
                                    </div>
                                    <span class="font-medium"><?= esc($admin['name']) ?></span>
                                </div>
                            </td>
                            <td class="py-3 px-6 text-left">
                                <span><?= esc($admin['email']) ?></span>
                            </td>
                            <td class="py-3 px-6 text-left">
                                <span><?= esc($admin['mobile']) ?></span>
                            </td>
                            <td class="py-3 px-6 text-left">
                                <?php if ($admin['admin_type'] == 1): ?>
                                    <span class="bg-red-200 text-red-700 py-1 px-3 rounded-full text-xs font-semibold">Super Admin</span>
                                <?php else: ?>
                                    <span class="bg-blue-200 text-blue-700 py-1 px-3 rounded-full text-xs font-semibold">Admin</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center space-x-2">
                                    <a href="<?= base_url('admin/edit-admin/' . $admin['id']) ?>" class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center hover:bg-yellow-200 hover:text-yellow-800 transition transform hover:scale-110" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($admin['id'] != session('admin_id')): ?>
                                        <a href="<?= base_url('admin/delete-admin/' . $admin['id']) ?>" class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 hover:text-red-800 transition transform hover:scale-110" onclick="return confirm('Are you sure you want to delete this admin?');" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if (empty($admins)): ?>
            <div class="p-6 text-center text-gray-500">
                No administrators found.
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
