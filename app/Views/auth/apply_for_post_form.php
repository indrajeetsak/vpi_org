<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Register - Step 4: Apply for Post<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-8">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2 text-sm">
                 <span class="text-gray-500">Step 1: Personal</span>
                <span class="text-gray-500">Step 2: Location</span>
                <span class="text-gray-500">Step 3: Address & Password</span>
                <span class="text-blue-600 font-semibold">Step 4: Apply for Post</span>
                <span class="text-gray-500">Step 5: Review Application</span>
            </div>
            <div class="h-2.5 bg-gray-200 rounded-full">
                <div class="h-2.5 bg-blue-600 rounded-full" style="width: 80%;"></div>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Apply for a VPI Office Bearer Post</h1>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline"><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>
        <?php if (session()->has('errors')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Please correct the following errors:</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline"><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('auth/process-post-application') ?>" method="POST" id="applyPostForm">
            <?= csrf_field() ?>
            <!-- orgam -->

        <div class="mb-4">
                <label for="organ" class="block text-sm font-medium text-gray-700 mb-1">Organ <span class="text-red-500">*</span></label>
                <select name="organ_id" id="organ" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Organ</option>
                    <?php foreach ($organs as $organ): ?>
                        <option value="<?= esc($organ['id'], 'attr') ?>" <?= set_select('organ_id', $organ['id']) ?>><?= esc($organ['name']) ?></option>
                    <?php endforeach; ?>
                    
                </select>
            </div>
            <!-- Level -->
            <div class="mb-6">
                <label for="level" class="block text-gray-700 text-sm font-semibold mb-2">Level:</label>
                <select name="level_id" id="level" 
                        class="shadow-sm appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        disabled>
                    <option value="">Loading Levels...</option>
                </select>
            </div>

            <!-- Post -->
            <div class="mb-6">
                <label for="post" class="block text-gray-700 text-sm font-semibold mb-2">Post:</label>
                <select name="post_id" id="post" 
                        class="shadow-sm appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        disabled>
                    <option value="">Select Level first</option>
                </select>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-between items-center mt-10">
                <button type="button" onclick="window.location.href='<?= site_url('auth/register?step=3') ?>'"
                        class="appearance-none block bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-md focus:outline-none focus:shadow-outline transition duration-150 ease-in-out visible opacity-100">
                    &larr; Previous
                </button>
                <button type="submit" id="submitApplicationBtn"
                        class="appearance-none block bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-md focus:outline-none focus:shadow-outline transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed visible opacity-100"
                        disabled>
                   
                    Next: Review Application &rarr;
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const levelSelect = document.getElementById('level');
        const postSelect = document.getElementById('post');
        const submitButton = document.getElementById('submitApplicationBtn');

        // Initially disable submit button
        submitButton.disabled = true;

        function populateDropdown(selectElement, items, defaultOptionText) {
            selectElement.innerHTML = `<option value="">${defaultOptionText}</option>`;
            if (items && items.length > 0) {
                items.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    selectElement.appendChild(option);
                });
                selectElement.disabled = false;
            } else {
                selectElement.disabled = true;
            }
        }

        async function fetchLevels() {
            levelSelect.disabled = true;
            postSelect.disabled = true; // Also disable post select while levels are loading
            populateDropdown(levelSelect, [], 'Loading Levels...');
            populateDropdown(postSelect, [], 'Select Level first'); // Reset post dropdown
            submitButton.disabled = true;

            try {
                const response = await fetch(`<?= site_url('auth/get-levels') ?>`, {
                    headers: {'X-Requested-With': 'XMLHttpRequest'}
                });
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const result = await response.json();
                if (result.success) {
                    populateDropdown(levelSelect, result.data, 'Select Level');
                } else {
                    populateDropdown(levelSelect, [], 'Error loading levels');
                    console.error('Error fetching levels:', result.message);
                }
            } catch (error) {
                populateDropdown(levelSelect, [], 'Error loading levels');
                console.error('Fetch error for levels:', error);
            }
        }

        async function fetchPosts(levelId) {
            postSelect.disabled = true;
            populateDropdown(postSelect, [], 'Loading Posts...');
            submitButton.disabled = true; // Disable submit while posts are loading

            if (!levelId) {
                populateDropdown(postSelect, [], 'Select Level first');
                return;
            }

            try {
                const response = await fetch(`<?= site_url('auth/get-posts-by-level') ?>/${levelId}`, {
                    headers: {'X-Requested-With': 'XMLHttpRequest'}
                });
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const result = await response.json();
                if (result.success) {
                    populateDropdown(postSelect, result.data, 'Select Post');
                } else {
                    populateDropdown(postSelect, [], 'Error loading posts');
                    console.error('Error fetching posts:', result.message);
                }
            } catch (error) {
                populateDropdown(postSelect, [], 'Error loading posts');
                console.error('Fetch error for posts:', error);
            }
        }

        // Load levels when the page is ready
        fetchLevels();

        levelSelect.addEventListener('change', function() {
            fetchPosts(this.value);
        });

        postSelect.addEventListener('change', function() {
            // Enable submit button only if a post is selected
            submitButton.disabled = this.value === '';
        });
    });
</script>

<?= $this->endSection() ?>
