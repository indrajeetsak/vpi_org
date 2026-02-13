<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Apply for Post<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Apply for a VPI Office Bearer Post</h1>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')): // Validation errors ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Please correct the following errors:</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('dashboard/apply') ?>" method="POST" id="applyPostForm">
            <?= csrf_field() ?>

            <div class="mb-4">
                <label for="organ" class="block text-sm font-medium text-gray-700 mb-1">Organ <span class="text-red-500">*</span></label>
                <select name="organ_id" id="organ" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Organ</option>
                    <?php foreach ($organs as $organ): ?>
                        <option value="<?= esc($organ['id'], 'attr') ?>" <?= set_select('organ_id', $organ['id']) ?>><?= esc($organ['name']) ?></option>
                    <?php endforeach; ?>
                    <!-- Example static options if not from DB -->
                    <option value="Main Committee" <?= set_select('organ_id', 'Main Committee') ?>>Main Committee</option>
                     <option value="Front" <?= set_select('organ_id', 'Front') ?>>Front</option>
                     <option value="Cell" <?= set_select('organ_id', 'Cell') ?>>Cell</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="level" class="block text-sm font-medium text-gray-700 mb-1">Level <span class="text-red-500">*</span></label>
                <select name="level_id" id="level" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" disabled>
                    <option value="">Select Level</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="post" class="block text-sm font-medium text-gray-700 mb-1">Post <span class="text-red-500">*</span></label>
                <select name="post_id" id="post" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" disabled>
                    <option value="">Select Post</option>
                </select>
                <div id="post-availability-status" class="text-xs mt-1"></div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" id="submitApplicationBtn"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md focus:outline-none focus:shadow-outline transition duration-150 ease-in-out" disabled>
                    Submit Application & Proceed to Payment
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const organSelect = document.getElementById('organ');
    const levelSelect = document.getElementById('level');
    const postSelect = document.getElementById('post');
    const postStatusDiv = document.getElementById('post-availability-status');
    const submitBtn = document.getElementById('submitApplicationBtn');

    organSelect.addEventListener('change', function () {
        const organId = this.value;
        levelSelect.innerHTML = '<option value="">Loading Levels...</option>';
        levelSelect.disabled = true;
        postSelect.innerHTML = '<option value="">Select Post</option>';
        postSelect.disabled = true;
        submitBtn.disabled = true;
        postStatusDiv.textContent = '';

        if (organId) {
            fetch(`<?= site_url('dashboard/get-levels-by-organ') ?>?organ_id=${encodeURIComponent(organId)}`)
                .then(response => response.json())
                .then(data => {
                    levelSelect.innerHTML = '<option value="">Select Level</option>';
                    data.forEach(level => levelSelect.add(new Option(level.name, level.id)));
                    levelSelect.disabled = false;
                });
        }
    });

    levelSelect.addEventListener('change', function () {
        const organId = organSelect.value;
        const levelId = this.value;
        postSelect.innerHTML = '<option value="">Loading Posts...</option>';
        postSelect.disabled = true;
        submitBtn.disabled = true;
        postStatusDiv.textContent = '';

        if (organId && levelId) {
            fetch(`<?= site_url('dashboard/get-posts-by-organ-level') ?>?organ_id=${encodeURIComponent(organId)}&level_id=${encodeURIComponent(levelId)}`)
                .then(response => response.json())
                .then(data => {
                    postSelect.innerHTML = '<option value="">Select Post</option>';
                    data.forEach(post => postSelect.add(new Option(post.name, post.id)));
                    postSelect.disabled = false;
                });
        }
    });

    postSelect.addEventListener('change', function() {
        // Basic validation: enable submit if a post is selected.
        // AJAX post availability check can be added here as per frontend_overview.md
        if (this.value) {
            submitBtn.disabled = false;
            postStatusDiv.textContent = 'Post available'; // Placeholder
            postStatusDiv.className = 'text-xs mt-1 text-green-600';
        } else {
            submitBtn.disabled = true;
            postStatusDiv.textContent = '';
        }
    });
});
</script>
<?= $this->endSection() ?>