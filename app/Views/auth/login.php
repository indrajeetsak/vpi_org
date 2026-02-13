<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-md">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                VPI Office Bearer Login
            </h2>        </div>        <form class="mt-8 space-y-6" action="<?= base_url('auth/authenticate-mobile') ?>" method="POST">
            <?= csrf_field() ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="mobile" class="sr-only">Mobile Number</label>
                    <input id="mobile" name="mobile" type="text" required 
                        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                        pattern="[0-9]{10}" title="Please enter a valid 10-digit mobile number"
                        placeholder="Mobile Number">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" required 
                        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                        placeholder="Password">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Login
                </button>
            </div>

            <div class="text-center">
                <a href="<?= base_url('auth/register') ?>" class="font-medium text-blue-600 hover:text-blue-500">
                    New User? Register Here
                </a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
