<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Registration Complete<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md text-center">
        <div class="mb-6">
            <svg class="w-20 h-20 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h1 class="text-2xl font-bold text-green-600 mb-3">Thank You for Registering with VPI!</h1>

        <p class="text-gray-700 mb-4">
            Your application has been successfully submitted.
        </p>
        <p class="text-gray-600 mb-6">
            You will receive a WhatsApp message after approval of your office-bearer application.
        </p>

        <div class="mt-8">
            <p class="text-sm text-gray-600">You will be logged out and redirected to the login page in <span id="logoutCountdown">10</span> seconds...</p>
            <a href="<?= site_url('auth/logout') ?>" 
               class="text-blue-600 hover:underline text-sm">
                Click here to log out now.
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let logoutCount = 10;
    const logoutCountdownElement = document.getElementById('logoutCountdown');
    const logoutInterval = setInterval(() => {
        logoutCount--;
        if (logoutCountdownElement) {
            logoutCountdownElement.textContent = logoutCount;
        }
        if (logoutCount <= 0) {
            clearInterval(logoutInterval);
            window.location.href = '<?= site_url('auth/logout') ?>';
        }
    }, 1000);
</script>
<?= $this->endSection() ?>