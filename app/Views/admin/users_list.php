<?= $this->extend('layouts/admin_modern') ?>

<?= $this->section('title') ?>Office Bearers List<?= $this->endSection() ?>

<?= $this->section('headerTitle') ?>Office Bearers List<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Fix for SweetAlert2 buttons visibility */
    .swal2-confirm, .swal2-cancel {
        opacity: 1 !important;
        color: white !important;
        display: inline-block !important;
    }
    .swal2-confirm {
        background-color: #d33 !important;
    }
    .swal2-cancel {
        background-color: #3085d6 !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6 bg-white rounded-xl shadow-md p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Search Form -->
        <form action="<?= site_url('admin/usersList') ?>" method="get" class="flex-grow flex flex-col md:flex-row gap-4">
            <div class="flex-grow">
                <input type="text" placeholder="Search by Name, Post, Location, Committee..." name="search_term" value="<?= esc(service('request')->getGet('search_term'), 'attr') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            </div>
            <div class="flex-shrink-0">
                <button type="submit" class="w-full md:w-auto px-6 py-2 bg-gray-800 text-white font-medium rounded-lg shadow hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </div>
        </form>
        
        <!-- Add Button -->
        <div class="flex-shrink-0 border-t md:border-t-0 md:border-l border-gray-200 md:pl-4 pt-4 md:pt-0 flex gap-2">
            <a href="<?= site_url('admin/export/active_users') ?>" class="inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-white hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 whitespace-nowrap shadow-md">
                <i class="fas fa-file-csv mr-2"></i> Export CSV
            </a>
            <a href="<?= site_url('admin/add-office-bearer') ?>" class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-white hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 whitespace-nowrap shadow-md">
                <i class="fas fa-plus mr-2"></i> Add Office Bearer
            </a>
        </div>
    </div>
</div>

<?php if (!empty($users) && is_array($users)): ?>
    <div class="bg-white rounded-xl shadow-md overflow-hidden w-full">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Mobile</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Post</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Level</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc($user['mobile']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc($user['post_name'] ?? ($user['post_id'] ?? 'N/A')) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php 
                                    $locName = $user['sector_name'] ?? $user['block_name'] ?? $user['mla_name'] ?? $user['district_name'] ?? $user['ls_name'] ?? $user['ls2_name'] ?? $user['ls3_name'] ?? $user['ls4_name'] ?? $user['state_name'] ?? '';
                                    echo esc($locName ? $locName . ' ' . $user['level_name'] : $user['level_name']);
                                ?>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <?= ucfirst(esc($user['status'])) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="<?= site_url('admin/users/view/' . $user['id']) ?>" class="text-indigo-600 hover:text-indigo-800 transition-colors duration-150">View</a>
                                <a href="<?= site_url('admin/users/edit/' . $user['id']) ?>" class="text-blue-600 hover:text-blue-800 transition-colors duration-150">Edit</a>
                                <?php if (session('admin_type') == 1): ?>
                                    <a href="javascript:void(0)" onclick="confirmDelete(<?= $user['id'] ?>)" class="text-red-600 hover:text-red-800 transition-colors duration-150">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="p-4">
            <?= $pager->links('default', 'tailwind_full') ?? '' ?>
        </div>
    </div>
<?php else: ?>
    <div class="bg-white rounded-xl shadow-md p-10 text-center">
        <p class="text-gray-500">No active office bearers found.</p>
    </div>
<?php endif; ?>

<!-- The Modal HTML structure -->
<div id="myModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <div id="modal-body-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(userId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the office bearer and their appointments!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= site_url('admin/users/delete/') ?>" + userId;
            }
        })
    }

    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('myModal');
        const modalBodyContent = document.getElementById('modal-body-content');

        document.querySelectorAll('.actions .view').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const url = this.href;

                console.log('Fetching URL:', url);
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // Identify as AJAX request
                    }
                })
                .then(response => response.text())
                .then(data => {
                    modalBodyContent.innerHTML = data;
                    modal.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching application details:', error);
                    modalBodyContent.innerHTML = '<p>Error loading details.</p>';
                    modal.classList.remove('hidden');
                });
            });
        });

        // Close modal when clicking outside of the modal content
        window.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
                modalBodyContent.innerHTML = '';
            }
        });
    });
</script>
<?= $this->endSection() ?>