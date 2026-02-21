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

        <!-- ═══ Committee Query Assistant ═══ -->
        <div class="bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 rounded-2xl p-6 shadow-xl border border-indigo-700">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-indigo-700 p-2.5 rounded-xl">
                    <i class="fas fa-robot text-indigo-200 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">Committee Query Assistant</h3>
                    <p class="text-indigo-300 text-xs">Ask any question about your committees in plain English</p>
                </div>
            </div>

            <div class="flex gap-3">
                <input id="cqInput"
                    type="text"
                    placeholder="e.g. &quot;Who is president of Goalpara district?&quot; or &quot;How many posts are occupied in bajli block?&quot;"
                    class="flex-1 bg-indigo-950/60 border border-indigo-600 text-white placeholder-indigo-400 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition"
                />
                <button id="cqBtn"
                    onclick="runCommitteeQuery()"
                    class="px-5 py-3 bg-indigo-500 hover:bg-indigo-400 active:scale-95 text-white font-semibold rounded-xl shadow transition-all duration-200 flex items-center gap-2 whitespace-nowrap">
                    <i class="fas fa-search text-sm"></i> Ask
                </button>
            </div>

            <!-- Example hints -->
            <div class="flex flex-wrap gap-2 mt-3" id="cqHints">
                <?php
                $hints = [
                    'Who is president of Assam state committee?',
                    'How many posts are occupied in bajli block?',
                    'List members of Goalpara district committee',
                ];
                foreach ($hints as $hint): ?>
                <button type="button"
                    onclick="document.getElementById('cqInput').value=this.dataset.q; runCommitteeQuery()"
                    data-q="<?= esc($hint) ?>"
                    class="text-xs bg-indigo-800/60 hover:bg-indigo-700 text-indigo-200 px-3 py-1 rounded-full border border-indigo-700 transition cursor-pointer">
                    <?= esc($hint) ?>
                </button>
                <?php endforeach; ?>
            </div>

            <!-- Answer Area -->
            <div id="cqResult" class="hidden mt-5 bg-indigo-950/50 rounded-xl border border-indigo-700 overflow-hidden">
                <div class="px-4 py-3 border-b border-indigo-800 flex items-center gap-2">
                    <i class="fas fa-comment-dots text-indigo-300"></i>
                    <span id="cqAnswerText" class="text-white text-sm font-medium"></span>
                </div>
                <div id="cqResultCards" class="p-4"></div>
            </div>

            <!-- Loading -->
            <div id="cqLoading" class="hidden mt-4 flex items-center gap-2 text-indigo-300 text-sm">
                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Searching committee data…
            </div>
        </div>
        <!-- ═══ END Committee Query Assistant ═══ -->

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
<script>
const CQ_URL = '<?= site_url('admin/query-committee') ?>';
const CQ_TOKEN = '<?= csrf_hash() ?>';
const CQ_NAME  = '<?= csrf_token() ?>';

async function runCommitteeQuery() {
    const input   = document.getElementById('cqInput');
    const result  = document.getElementById('cqResult');
    const loading = document.getElementById('cqLoading');
    const answer  = document.getElementById('cqAnswerText');
    const cards   = document.getElementById('cqResultCards');
    const btn     = document.getElementById('cqBtn');

    const question = input.value.trim();
    if (!question) { input.focus(); return; }

    // Show loading
    result.classList.add('hidden');
    loading.classList.remove('hidden');
    btn.disabled = true;

    try {
        const fd = new FormData();
        fd.append('question', question);
        fd.append(CQ_NAME, CQ_TOKEN);

        const res  = await fetch(CQ_URL, { method: 'POST', body: fd });
        const data = await res.json();

        loading.classList.add('hidden');
        btn.disabled = false;

        if (data.error) {
            answer.innerHTML = '<span style="color:#f87171">⚠ ' + escHtml(data.error) + '</span>';
            cards.innerHTML = '';
            result.classList.remove('hidden');
            return;
        }

        // Render answer text (support **bold**)
        answer.innerHTML = (data.answer || '').replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');

        // Render result cards
        const rows = data.results || [];
        if (rows.length === 0 || data.type === 'count') {
            cards.innerHTML = '';
        } else {
            cards.innerHTML = rows.map(r => {
                const name    = escHtml((r.first_name || '') + ' ' + (r.last_name || ''));
                const post    = escHtml(r.post_name  || '—');
                const mobile  = escHtml(r.mobile     || '');
                const level   = escHtml(r.level_name || '');
                const locParts = [r.sector_name, r.block_name, r.mla_name, r.district_name, r.state_name]
                                   .filter(Boolean).join(', ');
                return `
                <div class="flex items-center justify-between bg-indigo-900/40 rounded-lg px-4 py-3 mb-2 border border-indigo-800 last:mb-0">
                    <div>
                        <p class="text-white font-semibold text-sm">${name}</p>
                        <p class="text-indigo-300 text-xs mt-0.5">${post} ${level ? '· ' + level : ''}</p>
                        ${locParts ? '<p class="text-indigo-400 text-xs mt-0.5"><i class="fas fa-map-marker-alt mr-1"></i>' + escHtml(locParts) + '</p>' : ''}
                    </div>
                    ${mobile ? '<a href="tel:' + escHtml(mobile) + '" class="text-indigo-300 hover:text-white text-xs"><i class="fas fa-phone mr-1"></i>' + escHtml(mobile) + '</a>' : ''}
                </div>`;
            }).join('');
        }

        result.classList.remove('hidden');
    } catch (e) {
        loading.classList.add('hidden');
        btn.disabled = false;
        answer.innerHTML = '<span style="color:#f87171">⚠ Network error. Try again.</span>';
        cards.innerHTML = '';
        result.classList.remove('hidden');
    }
}

function escHtml(str) {
    return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Allow Enter key
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('cqInput').addEventListener('keydown', e => {
        if (e.key === 'Enter') runCommitteeQuery();
    });
});
</script>
<?= $this->endSection() ?>