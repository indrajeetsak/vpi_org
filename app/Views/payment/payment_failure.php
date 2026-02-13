<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Payment Failed<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md text-center">
        <div class="mb-6">
            <svg class="w-20 h-20 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h1 class="text-2xl font-bold text-red-600 mb-3">Payment Unsuccessful</h1>

        <p class="text-gray-700 mb-4">
            Unfortunately, your payment could not be processed.
        </p>

        <?php if (!empty($order_id) && $order_id !== 'N/A'): ?>
            <p class="text-sm text-gray-600 mb-1">Order ID: <?= esc($order_id) ?></p>
        <?php endif; ?>

        <?php if (!empty($reason)): ?>
            <p class="text-sm text-gray-600 mb-6">Reason: <?= esc($reason) ?></p>
        <?php endif; ?>

        <div class="mt-8">
            <a href="<?= site_url('payment/initiate') ?>"
               class="w-full inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-md focus:outline-none focus:shadow-outline transition duration-150 ease-in-out text-lg mb-4">
                Retry Payment
            </a>
            <p class="text-xs text-gray-500">If you continue to experience issues, please contact VPI support.</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>