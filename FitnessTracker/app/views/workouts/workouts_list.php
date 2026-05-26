<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php
// Pomocná funkce: vykreslí jeden spojnicový graf jako inline SVG (hodnoty + datumy).
if (!function_exists('lineChart')) {
    function lineChart($title, $unit, $labels, $values, $color) {
        $n = count($values);
        if ($n === 0) return '';
        $VW = 520; $VH = 165; $padX = 32; $padTop = 30; $padBot = 34;
        $plotW = $VW - 2 * $padX; $plotH = $VH - $padTop - $padBot; $baseY = $padTop + $plotH;
        $maxV = max($values); if ($maxV <= 0) $maxV = 1;
        $fmt = function ($x) { return (floor($x) == $x) ? (string)(int)$x : (string)round($x, 1); };

        $coords = [];
        foreach ($values as $i => $v) {
            $x = $n > 1 ? $padX + $i * ($plotW / ($n - 1)) : $VW / 2;
            $y = $baseY - ($v / $maxV) * $plotH;
            $coords[] = [round($x, 1), round($y, 1), $v];
        }
        $polyline = implode(' ', array_map(function ($c) { return $c[0] . ',' . $c[1]; }, $coords));
        $firstX = $coords[0][0]; $lastX = $coords[$n - 1][0];
        $area = "M $firstX,$baseY L $polyline L $lastX,$baseY Z";
        $last = end($values);

        ob_start(); ?>
        <div class="line-chart-card">
            <div class="line-chart-head">
                <span class="line-chart-label"><?= htmlspecialchars($title) ?> <span class="line-chart-unit">(<?= htmlspecialchars($unit) ?>)</span></span>
                <span class="line-chart-last">poslední: <strong style="color: <?= $color ?>;"><?= $fmt($last) ?></strong> <?= htmlspecialchars($unit) ?></span>
            </div>
            <svg class="line-svg" viewBox="0 0 <?= $VW ?> <?= $VH ?>" preserveAspectRatio="xMidYMid meet">
                <line x1="<?= $padX ?>" y1="<?= $baseY ?>" x2="<?= $VW - $padX ?>" y2="<?= $baseY ?>" stroke="rgba(255,255,255,0.08)" stroke-width="1"></line>
                <?php if ($n > 1): ?>
                    <path d="<?= $area ?>" fill="<?= $color ?>" fill-opacity="0.12"></path>
                    <polyline points="<?= $polyline ?>" fill="none" stroke="<?= $color ?>" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round"></polyline>
                <?php endif; ?>
                <?php foreach ($coords as $i => $c): ?>
                    <circle cx="<?= $c[0] ?>" cy="<?= $c[1] ?>" r="3.5" fill="<?= $color ?>"></circle>
                    <text class="pt-value" x="<?= $c[0] ?>" y="<?= $c[1] - 10 ?>" text-anchor="middle"><?= htmlspecialchars($fmt($c[2])) ?></text>
                    <text class="pt-date" x="<?= $c[0] ?>" y="<?= $baseY + 20 ?>" text-anchor="middle"><?= htmlspecialchars($labels[$i] ?? '') ?></text>
                <?php endforeach; ?>
            </svg>
        </div>
        <?php
        return ob_get_clean();
    }
}
?>

<style>
    .dashboard-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px; }
    @media (max-width: 920px) { .dashboard-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 520px) { .dashboard-grid { grid-template-columns: 1fr; } }
    .stat-card { background: rgba(30, 41, 59, 0.4); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 20px; padding: 20px; display: flex; align-items: center; gap: 16px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); transition: border-color 0.3s ease, box-shadow 0.3s ease; }
    .stat-card:hover { border-color: rgba(16, 185, 129, 0.3); box-shadow: 0 12px 32px rgba(0, 0, 0, 0.28); }
    .stat-icon { width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; border-radius: 14px; background: rgba(16, 185, 129, 0.1); color: #10b981; flex-shrink: 0; }
    .stat-icon svg { width: 24px; height: 24px; }
    .icon-workout { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .icon-time { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .icon-fire { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .icon-rpe { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
    .stat-info { display: flex; flex-direction: column; min-width: 0; }
    .stat-label { color: #94a3b8; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; margin-bottom: 6px; }
    .stat-value { color: #ffffff; font-size: 1.7rem; font-weight: 800; line-height: 1; white-space: nowrap; }
    .stat-value small { font-size: 0.85rem; font-weight: 600; color: #64748b; margin-left: 2px; }

    .section-title { font-size: 1rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 20px; font-weight: 700; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 10px; }
    .workout-feed { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 24px; }
    .workout-card { background: rgba(30, 41, 59, 0.4); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 24px; padding: 24px; display: flex; flex-direction: column; gap: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); transition: border-color 0.2s, box-shadow 0.2s; position: relative; overflow: hidden; }
    .workout-card:hover { border-color: rgba(16, 185, 129, 0.3); box-shadow: 0 14px 36px rgba(0,0,0,0.22); }
    .workout-cover { margin: -24px -24px 0 -24px; height: 160px; overflow: hidden; }
    .workout-cover img { width: 100%; height: 100%; object-fit: cover; display: block; }

    .workout-header { display: flex; justify-content: space-between; align-items: flex-start; }
    .workout-title { font-size: 1.3rem; font-weight: 800; color: #ffffff; margin: 0 0 5px 0; letter-spacing: -0.5px; }
    .workout-location { color: #64748b; font-size: 0.9rem; font-weight: 500; display: flex; align-items: center; gap: 4px; }
    .workout-date { background: rgba(15, 23, 42, 0.8); color: #94a3b8; font-size: 0.8rem; font-weight: 700; padding: 6px 12px; border-radius: 12px; }

    .workout-middle { display: flex; gap: 16px; align-items: center; }
    .workout-rpe-box { width: 80px; height: 80px; flex-shrink: 0; border-radius: 16px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: rgba(15,23,42,0.8); border: 1px solid rgba(255,255,255,0.05); }

    .workout-stats-mini { display: flex; flex-direction: column; gap: 10px; flex: 1; }
    .stat-mini-row { display: flex; align-items: center; gap: 8px; color: #cbd5e1; font-weight: 600; font-size: 0.95rem; }
    .stat-mini-icon { color: #10b981; }

    .workout-footer { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 16px; margin-top: auto; }
    .workout-meta { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .badge-category { background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 6px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }

    .workout-actions { display: flex; gap: 10px; }
    .btn-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: 0.2s; }
    .btn-detail { background: rgba(255, 255, 255, 0.05); color: #cbd5e1; }
    .btn-detail:hover { background: rgba(255, 255, 255, 0.1); color: #ffffff; }
    .btn-edit { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .btn-edit:hover { background: rgba(16, 185, 129, 0.2); color: #34d399; }
    .btn-delete { background: rgba(244, 63, 94, 0.1); color: #fb7185; }
    .btn-delete:hover { background: rgba(244, 63, 94, 0.2); color: #f43f5e; }

    .empty-state { text-align: center; padding: 60px 20px; background: rgba(30, 41, 59, 0.3); border-radius: 20px; border: 1px dashed rgba(255, 255, 255, 0.1); color: #94a3b8; font-size: 1.05rem; }

    /* Spojnicové grafy vývoje v čase (inline SVG) */
    .charts-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 40px; }
    @media (max-width: 720px) { .charts-grid { grid-template-columns: 1fr; } }
    .line-chart-card { background: rgba(30, 41, 59, 0.4); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 20px; padding: 20px 22px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); }
    .line-chart-head { display: flex; justify-content: space-between; align-items: baseline; gap: 10px; margin-bottom: 10px; }
    .line-chart-label { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; color: #94a3b8; }
    .line-chart-last { font-size: 0.8rem; color: #64748b; font-weight: 600; white-space: nowrap; }
    .line-chart-last strong { font-size: 1.05rem; font-weight: 800; }
    .line-chart-unit { color: #64748b; font-weight: 600; }
    .line-svg { width: 100%; height: auto; display: block; overflow: visible; }
    .line-svg text { font-family: 'Inter', sans-serif; }
    .pt-value { font-size: 14px; font-weight: 800; fill: #e2e8f0; }
    .pt-date { font-size: 12px; font-weight: 600; fill: #64748b; }

    /* Filtr nad výpisem */
    .filter-bar { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; margin-bottom: 24px; }
    .filter-bar input, .filter-bar select { padding: 12px 14px; border: 1px solid #1e293b; border-radius: 10px; background-color: rgba(15, 23, 42, 0.8); color: #ffffff; font-family: inherit; font-size: 0.9rem; box-sizing: border-box; transition: border-color 0.2s; }
    .filter-bar input { flex: 1; min-width: 180px; }
    .filter-bar input:focus, .filter-bar select:focus { outline: none; border-color: #10b981; }
    .filter-bar select { cursor: pointer; }
    .filter-bar .btn-filter { background: #10b981; color: #000; font-weight: 800; border: none; cursor: pointer; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px 20px; border-radius: 10px; transition: background-color 0.2s; }
    .filter-bar .btn-filter:hover { background: #059669; }
    .filter-reset { color: #94a3b8; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: color 0.2s; }
    .filter-reset:hover { color: #f43f5e; }
</style>

<?php if (isset($_SESSION['user_id']) && isset($data['stats'])): ?>
    <div class="dashboard-grid">
        <div class="stat-card">
            <div class="stat-icon icon-workout">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="4" height="12" rx="1"></rect><rect x="18" y="6" width="4" height="12" rx="1"></rect><line x1="6" y1="12" x2="18" y2="12"></line></svg>
            </div>
            <div class="stat-info">
                <span class="stat-label">Odcvičeno</span>
                <span class="stat-value"><?= htmlspecialchars($data['stats']['total_workouts']) ?> <small>x</small></span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon icon-time">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <div class="stat-info">
                <span class="stat-label">Čas ve fitku</span>
                <span class="stat-value"><?= htmlspecialchars($data['stats']['total_minutes']) ?> <small>min</small></span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon icon-fire">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path></svg>
            </div>
            <div class="stat-info">
                <span class="stat-label">Spáleno</span>
                <span class="stat-value"><?= htmlspecialchars($data['stats']['total_calories']) ?> <small>kcal</small></span>
            </div>
        </div>

        <?php
            $avgRpe = $data['stats']['avg_rpe'] ?? null;
            $avgRpeColor = '#94a3b8';
            if ($avgRpe !== null) {
                if ($avgRpe <= 4) $avgRpeColor = '#10b981';
                elseif ($avgRpe <= 7) $avgRpeColor = '#f59e0b';
                else $avgRpeColor = '#ef4444';
            }
        ?>
        <div class="stat-card">
            <div class="stat-icon icon-rpe">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
            </div>
            <div class="stat-info">
                <span class="stat-label">Prům. náročnost</span>
                <span class="stat-value" style="color: <?= $avgRpeColor ?>;"><?= $avgRpe !== null ? htmlspecialchars($avgRpe) : '–' ?> <small>/ 10</small></span>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['user_id']) && !empty($data['progress'])):
    $progress = $data['progress'];
    $dates     = array_map(function ($w) { return date('j.n.', strtotime($w['workout_date'])); }, $progress);
    $durations = array_map(function ($w) { return (float)($w['duration_min'] ?? 0); }, $progress);
    $calories  = array_map(function ($w) { return (float)($w['calories_burned'] ?? 0); }, $progress);
?>
    <h3 class="section-title">Vývoj v čase</h3>
    <div class="charts-grid">
        <?= lineChart('Délka tréninku', 'min', $dates, $durations, '#10b981') ?>
        <?= lineChart('Spálené kalorie', 'kcal', $dates, $calories, '#ef4444') ?>
    </div>
<?php endif; ?>

<?php $hasFilter = !empty($data['filterCategory']) || !empty($data['filterSearch']); ?>

<?php if (!empty($data['workouts']) || $hasFilter): ?>
    <form class="filter-bar" method="GET" action="index.php">
        <input type="text" name="q" placeholder="Hledat podle názvu…" value="<?= htmlspecialchars($data['filterSearch'] ?? '') ?>">
        <select name="category">
            <option value="">Všechny kategorie</option>
            <?php foreach ($data['categories'] as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($data['filterCategory'] == $cat['id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn-filter">Filtrovat</button>
        <?php if ($hasFilter): ?>
            <a href="index.php" class="filter-reset">Zrušit filtr</a>
        <?php endif; ?>
    </form>
<?php endif; ?>

<?php if (!empty($data['workouts'])): ?>
    <h3 class="section-title">Historie tréninků</h3>

    <div class="workout-feed">
        <?php foreach ($data['workouts'] as $workout): ?>
            <article class="workout-card">

                <?php
                    $cardImages = !empty($workout['images']) ? json_decode($workout['images'], true) : [];
                    if (!empty($cardImages)): ?>
                    <div class="workout-cover">
                        <img src="/WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/uploads/<?= htmlspecialchars($cardImages[0]) ?>" alt="Foto z tréninku">
                    </div>
                <?php endif; ?>

                <div class="workout-header">
                    <div>
                        <h3 class="workout-title"><?= htmlspecialchars($workout['title']); ?></h3>
                        <span class="workout-location">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            <?= htmlspecialchars($workout['location']); ?>
                        </span>
                    </div>
                    <span class="workout-date"><?= date('d. m.', strtotime($workout['workout_date'])) ?></span>
                </div>

                <div class="workout-middle">

                    <div class="workout-rpe-box">
                        <?php
                            $rpe = $workout['rpe'] ?? null;
                            $rpeColor = '#64748b';
                            if ($rpe) {
                                if ($rpe <= 4) $rpeColor = '#10b981';
                                elseif ($rpe <= 7) $rpeColor = '#f59e0b';
                                else $rpeColor = '#ef4444';
                            }
                        ?>
                        <span style="font-size: 0.65rem; text-transform: uppercase; font-weight: 800; color: #64748b; letter-spacing: 1px;">RPE</span>
                        <span style="font-size: 1.8rem; font-weight: 900; color: <?= $rpeColor ?>; line-height: 1;"><?= $rpe ?: '-' ?></span>
                    </div>

                    <div class="workout-stats-mini">
                        <div class="stat-mini-row">
                            <svg class="stat-mini-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            <?= htmlspecialchars($workout['duration_min']); ?> minut
                        </div>
                        <?php if(!empty($workout['calories_burned'])): ?>
                        <div class="stat-mini-row">
                            <svg class="stat-mini-icon" style="color: #ef4444;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path></svg>
                            <?= htmlspecialchars($workout['calories_burned']); ?> kcal
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="workout-footer">
                    <div class="workout-meta">
                        <?= workoutAuthorBadge($workout['author_name'] ?? '') ?>
                        <span class="badge-category"><?= htmlspecialchars($workout['category_name'] ?? 'Nezařazeno') ?></span>
                    </div>

                    <div class="workout-actions">
                        <a href="index.php?url=workout/show/<?= htmlspecialchars($workout['id']); ?>" class="btn-icon btn-detail" title="Detail">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </a>

                        <?php
                        $isOwner = isset($_SESSION['user_id']) && $_SESSION['user_id'] === $workout['created_by'];
                        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
                        if ($isOwner || $isAdmin):
                        ?>
                            <a href="index.php?url=workout/edit/<?= htmlspecialchars($workout['id']); ?>" class="btn-icon btn-edit" title="Upravit">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </a>
                            <a href="index.php?url=workout/delete/<?= htmlspecialchars($workout['id']); ?>" class="btn-icon btn-delete" onclick="return confirm('Smazat trénink?')" title="Smazat">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

            </article>
        <?php endforeach; ?>
    </div>

<?php else: ?>
    <div class="empty-state">
        <?php if ($hasFilter): ?>
            <p>Žádné tréninky neodpovídají zadanému filtru. <a href="index.php" style="color:#10b981; font-weight:600;">Zrušit filtr</a></p>
        <?php else: ?>
            <p>Zatím sis nezapsal žádný trénink. Přidej svůj první výkon pomocí tlačítka nahoře!</p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>