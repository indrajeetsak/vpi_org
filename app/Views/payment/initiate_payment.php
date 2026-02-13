<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Complete Payment<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md text-center">
        <h1 class="text-2xl font-bold mb-4">Complete Your Application Payment</h1>

        <?php if (session()->getFlashdata('info')): ?>
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?= session()->getFlashdata('info') ?>
            </div>
        <?php endif; ?>

        <p class="text-gray-700 mb-2">Hello, <?= esc($user['first_name'] ?? 'User') ?>!</p>
        <p class="text-gray-600 mb-4">Your application for the VPI Office Bearer Post:
            <strong class="block text-lg text-gray-800"><?= esc($user['post_name'] ?? 'N/A') ?></strong>
            (<?= esc($user['level_name'] ?? 'N/A') ?>, <?= esc($user['organ_name'] ?? 'N/A') ?>)
            requires a one-time registration fee.
        </p>

        <div class="my-6">
            <p class="text-gray-600 text-lg">Amount Due:</p>
            <p class="text-3xl font-bold text-blue-600">INR <?= number_format($payment_amount ?? 0, 2) ?></p>
        </div>

        <p class="text-sm text-gray-500 mb-6">You will be redirected to our secure payment gateway powered by CCAvenue.</p>

        <form action="<?= site_url('payment/initiate_ccavenue') ?>" method="POST">
            <?= csrf_field() ?>
            <!-- You would include hidden fields for CCAvenue here like order_id, amount, etc. -->
            <!-- These would be generated in the PaymentController::initiate() method -->
            <input type="hidden" name="order_id" value="<?= esc($order_id ?? 'TEMP_ORD_'.($user['id'] ?? '0'), 'attr') ?>">
            <input type="hidden" name="amount" value="<?= esc($payment_amount ?? 0, 'attr') ?>">
            <input type="hidden" name="user_id" value="<?= esc($user['id'] ?? '', 'attr') ?>">
            
            <button type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-md focus:outline-none focus:shadow-outline transition duration-150 ease-in-out text-lg">
                Proceed to Payment
            </button>
        </form>

        <a href="<?= site_url('dashboard') ?>" class="block text-sm text-gray-600 hover:text-blue-600 mt-6">Cancel and return to Dashboard</a>
    </div>
</div>
<?= $this->endSection() ?>