<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Register<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('registrationForm', () => ({
            step: 1,
            init() {
                console.log('Registration form initialized');
                this.step = 1;
            },
            goToNextStep() {
                console.log('Current step before next:', this.step);
                const form = this.$refs.registrationForm;
                const currentStepDiv = form.querySelector(`[data-form-step='${this.step}']`);
                if (!currentStepDiv) return;

                const fieldsInCurrentStep = currentStepDiv.querySelectorAll('[name][required]');
                let allValidInStep = true;
                for (const field of fieldsInCurrentStep) {
                    if (!field.checkValidity()) {
                        allValidInStep = false;
                        if (field.offsetParent !== null) {
                            field.reportValidity();
                            field.focus();
                        }
                        break;
                    }
                }

                if (allValidInStep && this.step < 3) {
                    this.step++;
                    console.log('Moved to step:', this.step);
                }
            },
            goToPreviousStep() {
                if (this.step > 1) {
                    this.step--;
                    console.log('Moved to step:', this.step);
                }
            },
            handleFormSubmit() {
                console.log('Form submission attempted at step:', this.step);
                const form = this.$refs.registrationForm;
                
                if (this.step === 3) {
                    const currentStepDiv = form.querySelector(`[data-form-step='${this.step}']`);
                    const fieldsInCurrentStep = currentStepDiv.querySelectorAll('[name][required]');
                    let allValidInFinalStep = true;
                    
                    for (const field of fieldsInCurrentStep) {
                        if (!field.checkValidity()) {
                            allValidInFinalStep = false;
                            if (field.offsetParent !== null) {
                                field.reportValidity();
                                field.focus();
                            }
                            break;
                        }
                    }
                    
                    if (allValidInFinalStep) {
                        console.log('Form is valid, submitting...');
                        form.submit();
                    }
                } else {
                    this.goToNextStep();
                }
            }
        }))
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen py-8" x-data="registrationForm"><div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2">
                <span x-bind:class="{ 'text-blue-600 font-bold': step === 1, 'text-gray-500': step !== 1 }">Personal Details</span>
                <span x-bind:class="{ 'text-blue-600 font-bold': step === 2, 'text-gray-500': step !== 2 }">Location & Family</span>
                <span x-bind:class="{ 'text-blue-600 font-bold': step === 3, 'text-gray-500': step !== 3 }">Address & Password</span>
            </div>
            <div class="h-2 bg-gray-200 rounded">
                <div class="h-2 bg-blue-600 rounded transition-all duration-300"
                    x-bind:style="'width: ' + ((step / 3) * 100) + '%'"></div>
            </div>
        </div>

        <?php if (isset($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Success message is handled by redirecting to login page -->

        <form x-ref="registrationForm" action="<?= base_url('auth/register') ?>" method="POST" enctype="multipart/form-data" @submit.prevent="handleFormSubmit">
            <?= csrf_field() ?>            <!-- Step 1: Personal Details -->
            <div x-show.transition.in.opacity.duration.500="step === 1" data-form-step="1" x-cloak>
                <h2 class="text-2xl font-bold mb-6">Personal & Contact Details</h2>
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" name="first_name" required value="<?= old('first_name') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?= isset($errors['first_name']) ? 'border-red-400' : '' ?>">
                        <?php if (isset($errors['first_name'])): ?>
                            <p class="text-red-700 text-sm mt-1"><?= $errors['first_name'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" name="last_name" required value="<?= old('last_name') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?= isset($errors['last_name']) ? 'border-red-400' : '' ?>">
                        <?php if (isset($errors['last_name'])): ?>
                            <p class="text-red-700 text-sm mt-1"><?= $errors['last_name'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                        <input type="date" name="date_of_birth" required value="<?= old('date_of_birth') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?= isset($errors['date_of_birth']) ? 'border-red-400' : '' ?>">
                        <?php if (isset($errors['date_of_birth'])): ?>
                            <p class="text-red-700 text-sm mt-1"><?= $errors['date_of_birth'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <select name="gender" required value="<?= old('gender') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?= isset($errors['gender']) ? 'border-red-400' : '' ?>">
                            <option value="">Select Gender</option>
                            <option value="male" <?= old('gender') === 'male' ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= old('gender') === 'female' ? 'selected' : '' ?>>Female</option>
                            <option value="other" <?= old('gender') === 'other' ? 'selected' : '' ?>>Other</option>
                        </select>
                        <?php if (isset($errors['gender'])): ?>
                            <p class="text-red-700 text-sm mt-1"><?= $errors['gender'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                        <input type="file" name="photo" accept="image/*" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?= isset($errors['photo']) ? 'border-red-400' : '' ?>">
                        <p class="text-sm text-gray-500 mt-1">Upload your recent passport-sized photo (JPG/PNG, max 2MB)</p>
                        <?php if (isset($errors['photo'])): ?>
                            <p class="text-red-700 text-sm mt-1"><?= $errors['photo'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Number</label>
                        <input type="tel" name="mobile" required value="<?= old('mobile') ?>" x-ref="mobileInput"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?= isset($errors['mobile']) ? 'border-red-400' : '' ?>">
                        <?php if (isset($errors['mobile'])): ?>
                            <p class="text-red-700 text-sm mt-1"><?= $errors['mobile'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alternate Number</label>
                        <input type="tel" name="alternate_mobile" value="<?= old('alternate_mobile') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?= isset($errors['alternate_mobile']) ? 'border-red-400' : '' ?>">
                        <?php if (isset($errors['alternate_mobile'])): ?>
                            <p class="text-red-700 text-sm mt-1"><?= $errors['alternate_mobile'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="email" required value="<?= old('email') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?= isset($errors['email']) ? 'border-red-400' : '' ?>">
                        <?php if (isset($errors['email'])): ?>
                            <p class="text-red-700 text-sm mt-1"><?= $errors['email'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>            <!-- Step 2: Location & Family -->
            <div x-show.transition.in.opacity.duration.500="step === 2" data-form-step="2" x-cloak>
                <h2 class="text-2xl font-bold mb-6">Location & Family Details</h2>
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                        <select name="state_id" required value="<?= old('state_id') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?= isset($errors['state_id']) ? 'border-red-400' : '' ?>">
                            <option value="">Select State</option>
                            <!-- Add states dynamically -->
                            <option value="Example State" <?= old('state_id') === 'Example State' ? 'selected' : '' ?>>Example State</option>
                        </select>
                        <?php if (isset($errors['state_id'])): ?>
                            <p class="text-red-700 text-sm mt-1"><?= $errors['state_id'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">District</label>
                        <select name="district_id" required value="<?= old('district_id') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?= isset($errors['district_id']) ? 'border-red-400' : '' ?>">
                            <option value="">Select District</option>
                            <!-- Add districts dynamically based on state -->
                            <option value="Example District" <?= old('district_id') === 'Example District' ? 'selected' : '' ?>>Example District</option>
                        </select>
                        <?php if (isset($errors['district_id'])): ?>
                            <p class="text-red-700 text-sm mt-1"><?= $errors['district_id'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">MLA Area</label>
                        <select name="mla_area_id" required value="<?= old('mla_area_id') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?= isset($errors['mla_area_id']) ? 'border-red-400' : '' ?>">
                            <option value="">Select MLA Area</option>
                            <!-- Add MLA areas dynamically based on district -->
                            <option value="Example MLA Area" <?= old('mla_area_id') === 'Example MLA Area' ? 'selected' : '' ?>>Example MLA Area</option>
                        </select>
                        <?php if (isset($errors['mla_area_id'])): ?>
                            <p class="text-red-700 text-sm mt-1"><?= $errors['mla_area_id'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Block</label>
                        <select name="block_id" required value="<?= old('block_id') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?= isset($errors['block_id']) ? 'border-red-400' : '' ?>">
                            <option value="">Select Block</option>
                            <!-- Add blocks dynamically based on district -->
                            <option value="Example Block" <?= old('block_id') === 'Example Block' ? 'selected' : '' ?>>Example Block</option>
                        </select>
                        <?php if (isset($errors['block_id'])): ?>
                            <p class="text-red-700 text-sm mt-1"><?= $errors['block_id'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Father's/Husband's Name</label>
                        <input type="text" name="father_name" required value="<?= old('father_name') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?= isset($errors['father_name']) ? 'border-red-400' : '' ?>">
                        <?php if (isset($errors['father_name'])): ?>
                            <p class="text-red-700 text-sm mt-1"><?= $errors['father_name'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mother's/Wife's Name</label>
                        <input type="text" name="mother_name" required value="<?= old('mother_name') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?= isset($errors['mother_name']) ? 'border-red-400' : '' ?>">
                        <?php if (isset($errors['mother_name'])): ?>
                            <p class="text-red-700 text-sm mt-1"><?= $errors['mother_name'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Aadhaar Card Number</label>
                        <input type="text" name="aadhaar_number" required pattern="[0-9]{12}" value="<?= old('aadhaar_number') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?= isset($errors['aadhaar_number']) ? 'border-red-400' : '' ?>">
                        <p class="text-sm text-gray-500 mt-1">Enter your 12-digit Aadhaar number</p>
                        <?php if (isset($errors['aadhaar_number'])): ?>
                            <p class="text-red-700 text-sm mt-1"><?= $errors['aadhaar_number'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>            <!-- Step 3: Address & Password -->
            <div x-show.transition.in.opacity.duration.500="step === 3" data-form-step="3" x-cloak>
                <h2 class="text-2xl font-bold mb-6">Address & Password</h2>
                
                <div class="grid grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                        <input type="text" name="address_line1" required value="<?= old('address_line1') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?= isset($errors['address_line1']) ? 'border-red-400' : '' ?>">
                        <?php if (isset($errors['address_line1'])): ?>
                            <p class="text-red-700 text-sm mt-1"><?= $errors['address_line1'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                        <input type="text" name="address_line2" value="<?= old('address_line2') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?= isset($errors['address_line2']) ? 'border-red-400' : '' ?>">
                        <?php if (isset($errors['address_line2'])): ?>
                            <p class="text-red-700 text-sm mt-1"><?= $errors['address_line2'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">PIN Code</label>
                        <input type="text" name="pin_code" required pattern="[0-9]{6}" value="<?= old('pin_code') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 <?= isset($errors['pin_code']) ? 'border-red-400' : '' ?>">
                        <?php if (isset($errors['pin_code'])): ?>
                            <p class="text-red-700 text-sm mt-1"><?= $errors['pin_code'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">User ID (Mobile Number)</label>
                        <input type="text" disabled x-bind:value="$refs.mobileInput.value"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md">
                        <?php // No server-side validation error for this disabled field ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required minlength="8"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1">Minimum 8 characters, including uppercase, lowercase, number, and special character</p>
                    </div>
                    <?php if (isset($errors['password'])): ?>
                        <p class="text-red-700 text-sm mt-1"><?= $errors['password'] ?></p>
                    <?php endif; ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" name="confirm_password" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <?php if (isset($errors['confirm_password'])): ?>
                        <p class="text-red-700 text-sm mt-1"><?= $errors['confirm_password'] ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="mt-8 flex justify-between">
                <button type="button" 
                    x-show="step > 1"
                    @click="goToPreviousStep()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Previous
                </button>
                <button type="button" 
                    x-show="step < 3"
                    @click="goToNextStep()"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Next
                </button>
                <button type="submit"
                    x-show="step === 3"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"                    >
                    Submit Registration
                </button>            </div>
        </form>    </div>
</div>
<?= $this->endSection() ?>
