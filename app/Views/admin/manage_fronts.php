<?= $this->extend('layouts/admin_modern') ?>

<?= $this->section('title') ?>Manage Fronts<?= $this->endSection() ?>

<?= $this->section('headerTitle') ?>Manage Fronts<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p><?= session()->getFlashdata('success') ?></p>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p><?= session()->getFlashdata('error') ?></p>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-md mt-8 overflow-hidden">
    <div class="bg-gray-50 px-6 py-4 border-b">
        <h4 class="text-lg font-bold text-gray-800">Manage Fronts</h4>
        <p class="text-sm text-gray-600 mt-1">Add, edit, or delete fronts. Enter one front name per line.</p>
    </div>
    <div class="p-6">
        <form action="<?= site_url('admin/fronts/update') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-6">
                <label for="frontsText" class="block text-sm font-medium text-gray-700 mb-2">Fronts List (One per line)</label>
                <textarea id="frontsText" name="fronts_text" rows="15" class="w-full px-4 py-3 font-mono text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Enter front names here..."><?= esc($frontsText) ?></textarea>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition">
                    <i class="fas fa-save mr-2"></i>Update Fronts
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
