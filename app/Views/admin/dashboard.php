<?= $this->extend('layouts/admin_modern') ?>

<?= $this->section('title') ?>Admin Dashboard<?= $this->endSection() ?>

<?= $this->section('headerTitle') ?>Admin Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Dashboard Overview</h2>
                <p class="text-gray-500 mt-1 flex items-center">
                    <i class="far fa-calendar-alt mr-2"></i>
                    <?= date('l, F j, Y') ?>
                </p>
            </div>
            <!-- Optional Actions -->
            <!-- <div class="flex gap-3">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">
                    Generate Report
                </button>
            </div> -->
        </div>

        <!-- Primary Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <!-- New Applications -->
            <div class="bg-amber-800 rounded-2xl shadow-sm border border-gray-700 p-6 hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1 bg-orange-500"></div>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-300 uppercase tracking-wide">New Applications</p>
                        <h3 class="text-3xl font-bold text-white mt-2"><?= $pendingApplicationsCount ?? '0' ?></h3>
                    </div>
                    <div class="p-3 bg-gray-700 rounded-xl text-orange-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <a href="<?= site_url('admin/usersRequest') ?>" class="text-sm font-medium text-orange-400 hover:text-orange-300 flex items-center group-hover:underline">
                        Review Requests <i class="fas fa-arrow-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>

            <!-- Active Bearers -->
            <div class="bg-emerald-800 rounded-2xl shadow-sm border border-gray-700 p-6 hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1 bg-emerald-500"></div>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-300 uppercase tracking-wide">Active Office Bearers</p>
                        <h3 class="text-3xl font-bold text-white mt-2"><?= $activeOfficeBearersCount ?? '0' ?></h3>
                    </div>
                    <div class="p-3 bg-gray-700 rounded-xl text-emerald-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.125-1.273-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.125-1.273.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <a href="<?= site_url('admin/usersList') ?>" class="text-sm font-medium text-emerald-400 hover:text-emerald-300 flex items-center group-hover:underline">
                        View List <i class="fas fa-arrow-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>

            <!-- Recently Approved -->
            <div class="bg-blue-800 rounded-2xl shadow-sm border border-gray-700 p-6 hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1 bg-blue-500"></div>
                <div class="flex justify-between items-start">
                    <div class="flex-1 pr-4">
                        <p class="text-sm font-medium text-gray-300 uppercase tracking-wide">Recently Approved</p>
                        <div class="mt-2 text-white">
                             <?= $recentlyApprovedSummary ?? '<span class="text-gray-500 italic">No recent approvals</span>' ?>
                        </div>
                    </div>
                    <div class="p-3 bg-gray-700 rounded-xl text-blue-400 group-hover:scale-110 transition-transform duration-300">
                         <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                     <a href="<?= site_url('admin/usersList') ?>" class="text-sm font-medium text-blue-400 hover:text-blue-300 flex items-center group-hover:underline">
                        DETAILS <i class="fas fa-arrow-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>

            <!-- Payment Rate -->
            <div class="bg-purple-800 rounded-2xl shadow-sm border border-gray-700 p-6 hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1 bg-purple-500"></div>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-300 uppercase tracking-wide">Payment Success</p>
                        <h3 class="text-3xl font-bold text-white mt-2"><?= $paymentSuccessRate ?? 'N/A' ?></h3>
                    </div>
                    <div class="p-3 bg-gray-700 rounded-xl text-purple-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <span class="text-sm font-medium text-gray-500">Analytics coming soon</span>
                </div>
            </div>
        </div>

        <!-- Section: Committee Constitution Stats -->
        <div>
            <div class="flex items-center mb-6">
                <div class="h-8 w-1 bg-indigo-600 rounded-full mr-3"></div>
                <h3 class="text-xl font-bold text-gray-800">Constituted Committees</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Helper for cards -->
                <?php
                $committeeCards = [
                    ['title' => 'State Level', 'count' => $constitutedStateCommittees ?? 0, 'color' => 'indigo', 'icon' => 'fas fa-map', 'level_id' => 11],
                    ['title' => 'District Level', 'count' => $constitutedDistrictCommittees ?? 0, 'color' => 'blue', 'icon' => 'fas fa-city', 'level_id' => 16],
                    ['title' => 'MLA Level', 'count' => $constitutedMlaCommittees ?? 0, 'color' => 'teal', 'icon' => 'fas fa-landmark', 'level_id' => 6],
                    ['title' => 'Block/Town Level', 'count' => $constitutedBlockCommittees ?? 0, 'color' => 'amber', 'icon' => 'fas fa-th-large', 'level_id' => 5],
                    ['title' => 'MP Level', 'count' => $constitutedMpCommittees ?? 0, 'color' => 'rose', 'icon' => 'fas fa-flag', 'level_id' => 7],
                    ['title' => 'Sector Level', 'count' => $constitutedSectorCommittees ?? 0, 'color' => 'violet', 'icon' => 'fas fa-vector-square', 'level_id' => 3],
                ];

                foreach ($committeeCards as $card): 
                    // Dark mode adjustments
                    $iconClass = match($card['color']) {
                        'indigo' => 'bg-gray-700 text-indigo-400',
                        'blue' => 'bg-gray-700 text-blue-400',
                        'teal' => 'bg-gray-700 text-teal-400',
                        'amber' => 'bg-gray-700 text-amber-400',
                        'rose' => 'bg-gray-700 text-rose-400',
                        'violet' => 'bg-gray-700 text-violet-400',
                        default => 'bg-gray-700 text-gray-400'
                    };
                ?>
                <a href="<?= site_url('admin/committee-details/' . $card['level_id']) ?>" class="block">
                    <div class="bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-700 hover:shadow-lg hover:border-gray-500 transition-all duration-300 flex items-center justify-between cursor-pointer group">
                        <div>
                            <p class="text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Committees at</p>
                            <h4 class="text-lg font-bold text-white leading-tight mb-2"><?= $card['title'] ?></h4>
                            <p class="text-3xl font-extrabold text-white"><?= $card['count'] ?></p>
                        </div>
                        <div class="<?= $iconClass ?> p-4 rounded-2xl shadow-inner group-hover:scale-110 transition-transform duration-300">
                            <i class="<?= $card['icon'] ?> text-2xl"></i>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<?= $this->endSection() ?>