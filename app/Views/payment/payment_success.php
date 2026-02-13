<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Payment Successful<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md text-center">
        <div class="mb-6">
            <svg class="w-20 h-20 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h1 class="text-2xl font-bold text-green-600 mb-3">Payment Successful!</h1>
        <p class="text-gray-700 mb-6">Thank You for Your Application.</p>

        <div class="bg-gray-50 p-4 rounded-md text-left mb-6 space-y-2">
            <h2 class="text-lg font-semibold mb-2 text-gray-800">Payment Receipt Details:</h2>
            <p><strong class="text-gray-600">Transaction Status:</strong> <span class="text-green-600 font-semibold">Success</span></p>
            <p><strong class="text-gray-600">Order ID:</strong> <?= esc($order_id ?? 'N/A') ?></p>
            <p><strong class="text-gray-600">Amount Paid:</strong> INR <?= esc(number_format($amount_paid ?? 0, 2)) ?></p>
            <p><strong class="text-gray-600">Transaction ID (Tracking ID):</strong> <?= esc($tracking_id ?? 'N/A') ?></p>
            <p><strong class="text-gray-600">Bank Reference No.:</strong> <?= esc($bank_ref_no ?? 'N/A') ?></p>
        </div>

        <div class="bg-blue-50 p-4 rounded-md text-left mb-6">
            <h2 class="text-lg font-semibold mb-2 text-blue-700">Important Information:</h2>
            <p class="text-gray-700">Your User ID is: <strong class="text-blue-600"><?= esc($user['mobile'] ?? 'your mobile number') ?></strong></p>
            <p class="text-gray-700">Your Password is: <strong class="text-blue-600">[The one you set during registration]</strong></p>
            <p class="text-gray-700 mt-2">Your application is now under review by the VPI administration. You will be notified upon approval.</p>
        </div>

        <div class="mt-8">
            <p class="text-sm text-gray-600">You will be redirected to the registration confirmation page in <span id="countdown">10</span> seconds...</p>
            <a href="<?= site_url('dashboard/registration-thanks') // This route needs to be created or use an existing one like 'dashboard' ?>" 
               class="text-blue-600 hover:underline text-sm">
                Click here if you are not redirected.
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let count = 10;
    const countdownElement = document.getElementById('countdown');
    const interval = setInterval(() => {
        count--;
        if (countdownElement) {
            countdownElement.textContent = count;
        }
        if (count <= 0) {
            clearInterval(interval);
            window.location.href = '<?= site_url('dashboard/registration-thanks') // Or 'dashboard' ?>';
        }
    }, 1000);
</script>
<?= $this->endSection() ?>