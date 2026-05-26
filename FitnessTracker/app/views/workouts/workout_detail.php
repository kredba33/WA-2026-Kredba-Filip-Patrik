<?php require_once __DIR__ . '/../layout/header.php'; ?>

<style>
    .back-nav { margin-bottom: 25px; display: inline-flex; align-items: center; gap: 8px; color: #94a3b8; text-decoration: none; font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; transition: color 0.2s; }
    .back-nav:hover { color: #ffffff; }

    .workout-detail-card {
        background: rgba(30, 41, 59, 0.4); border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 24px; padding: 40px; max-width: 800px; margin: 0 auto;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
    }

    .detail-header { border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding-bottom: 25px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-start; }
    .detail-title-group { flex: 1; }
    .detail-id { color: #64748b; font-family: ui-monospace, SFMono-Regular, monospace; font-size: 0.9rem; font-weight: 700; margin-bottom: 5px; display: block; }
    
    .title-rpe-row { display: flex; align-items: center; gap: 20px; margin: 0 0 15px 0; }
    .detail-title { font-size: 2.2rem; font-weight: 800; color: #ffffff; margin: 0; text-transform: uppercase; letter-spacing: -0.5px; line-height: 1.1; }
    
    .rpe-badge-large { display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; border-radius: 12px; font-weight: 900; font-size: 1.2rem; color: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.3); }
    
    .detail-badges { display: flex; gap: 10px; flex-wrap: wrap; }
    .badge { padding: 6px 14px; border-radius: 10px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
    .badge-cat { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .badge-date { background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); display: flex; align-items: center; gap: 6px; }

    .detail-byline { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 14px; padding: 12px 18px; margin-bottom: 30px; }
    .byline-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; color: #64748b; }
    .byline-sep { color: #475569; }
    .byline-edit { font-size: 0.82rem; color: #94a3b8; }
    .byline-edit strong { color: #cbd5e1; }

    .detail-stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .detail-stat { background: rgba(15, 23, 42, 0.6); padding: 20px; border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.05); }
    .stat-head { color: #94a3b8; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; display: flex; align-items: center; gap: 8px; letter-spacing: 1px; margin-bottom: 8px; }
    .stat-head .sh-icon { width: 14px; height: 14px; flex-shrink: 0; }
    .sh-time { color: #f59e0b; }
    .sh-fire { color: #ef4444; }
    .sh-loc { color: #10b981; }
    .stat-val { color: #ffffff; font-size: 1.4rem; font-weight: 800; }
    .stat-val small { font-size: 0.9rem; color: #64748b; font-weight: 600; margin-left: 2px; }

    .detail-notes { background: rgba(15, 23, 42, 0.4); padding: 25px; border-radius: 16px; border-left: 4px solid #10b981; margin-bottom: 30px; }
    .notes-title { font-size: 0.8rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; margin: 0 0 10px 0; letter-spacing: 1px; display: flex; align-items: center; gap: 8px; }
    .notes-text { color: #cbd5e1; line-height: 1.7; margin: 0; font-size: 1.05rem; white-space: pre-wrap; }

    .detail-actions { display: flex; justify-content: flex-end; border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 25px; gap: 15px; }
    .btn-edit-large { background: #10b981; color: #000000; padding: 14px 28px; border-radius: 12px; font-size: 0.95rem; font-weight: 800; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; transition: background-color 0.2s, box-shadow 0.2s; }
    .btn-edit-large:hover { background: #059669; box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2); }

    .workout-gallery { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; margin-bottom: 30px; }
    .workout-gallery img { width: 100%; height: 220px; object-fit: cover; border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.08); display: block; }

    .detail-exercises { margin-bottom: 30px; }
    .exercises-title { font-size: 0.8rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; margin: 0 0 14px 0; letter-spacing: 1px; display: flex; align-items: center; gap: 8px; }
    .exercises-table { width: 100%; border-collapse: collapse; background: rgba(15, 23, 42, 0.4); border-radius: 14px; overflow: hidden; }
    .exercises-table th { text-align: left; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: #64748b; font-weight: 700; padding: 12px 16px; background: rgba(15, 23, 42, 0.6); }
    .exercises-table td { padding: 12px 16px; color: #cbd5e1; font-size: 0.95rem; border-top: 1px solid rgba(255, 255, 255, 0.04); }
    .exercises-table td:first-child { color: #ffffff; font-weight: 600; }
    .exercises-table .ex-num { color: #10b981; font-weight: 700; }

    @media (max-width: 600px) { .workout-detail-card { padding: 25px; } .detail-title { font-size: 1.8rem; } .detail-stats-grid { grid-template-columns: 1fr; } .workout-gallery img { height: 200px; } }

    /* Komentáře */
    .comments-section { max-width: 800px; margin: 30px auto 0 auto; }
    .comments-title { color: #94a3b8; font-size: 1rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 700; margin: 0 0 18px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); padding-bottom: 10px; }
    .comments-title small { color: #10b981; margin-left: 6px; font-size: 0.85rem; }

    .comment-form { display: flex; flex-direction: column; gap: 10px; background: rgba(30, 41, 59, 0.4); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 16px; padding: 18px; margin-bottom: 20px; }
    .comment-form textarea { width: 100%; padding: 12px; border: 1px solid #1e293b; border-radius: 10px; background-color: #0f172a; color: #ffffff; font-family: inherit; font-size: 0.95rem; box-sizing: border-box; transition: border-color 0.2s, box-shadow 0.2s; resize: vertical; min-height: 80px; }
    .comment-form textarea:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12); }
    .btn-comment-submit { background: #10b981; color: #000; padding: 10px 18px; border-radius: 10px; border: none; font-weight: 800; font-size: 0.8rem; cursor: pointer; transition: background-color 0.2s; text-transform: uppercase; letter-spacing: 1px; align-self: flex-end; }
    .btn-comment-submit:hover { background: #059669; }

    .comment-cta { background: rgba(30, 41, 59, 0.3); border: 1px dashed rgba(255, 255, 255, 0.1); border-radius: 14px; padding: 16px; color: #94a3b8; text-align: center; font-size: 0.9rem; margin-bottom: 20px; }
    .comment-cta a { color: #10b981; text-decoration: none; font-weight: 700; }
    .comment-cta a:hover { text-decoration: underline; }
    .no-comments { background: rgba(30, 41, 59, 0.3); border: 1px dashed rgba(255, 255, 255, 0.08); border-radius: 14px; padding: 24px; color: #64748b; text-align: center; font-size: 0.92rem; }

    .comments-list { display: flex; flex-direction: column; gap: 14px; }
    .comment { background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255, 255, 255, 0.04); border-radius: 14px; padding: 16px 18px; }
    .comment-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 8px; }
    .comment-date { color: #64748b; font-size: 0.78rem; font-weight: 600; }
    .comment-content { color: #cbd5e1; margin: 0; line-height: 1.55; white-space: pre-wrap; font-size: 0.95rem; }
    .comment-actions { display: flex; gap: 8px; margin-top: 10px; justify-content: flex-end; }
    .comment-action { padding: 4px 10px; border-radius: 6px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; text-decoration: none; transition: background-color 0.2s, color 0.2s; background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .comment-action:hover { background: rgba(16, 185, 129, 0.2); }
    .comment-action.comment-del { background: rgba(244, 63, 94, 0.1); color: #fb7185; }
    .comment-action.comment-del:hover { background: rgba(244, 63, 94, 0.2); color: #f43f5e; }
</style>

<div style="max-width: 800px; margin: 0 auto;">
    <a href="index.php" class="back-nav">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Zpět do deníku
    </a>
</div>

<div class="workout-detail-card">
    
    <?php 
        $rpe = $workout['rpe'] ?? null;
        $rpeColor = '#64748b'; 
        if ($rpe) {
            if ($rpe <= 4) $rpeColor = '#10b981'; 
            elseif ($rpe <= 7) $rpeColor = '#f59e0b'; 
            else $rpeColor = '#ef4444'; 
        }
    ?>

    <div class="detail-header">
        <div class="detail-title-group">
            <span class="detail-id">REPORT TRÉNINKU #<?= htmlspecialchars($workout['id'] ?? '') ?></span>
            <div class="title-rpe-row">
                <h2 class="detail-title"><?= htmlspecialchars($workout['title'] ?? 'Neznámý název') ?></h2>
                <?php if($rpe): ?>
                <div class="rpe-badge-large" style="background-color: <?= $rpeColor ?>;" title="Náročnost (RPE) <?= $rpe ?>/10">
                    <?= htmlspecialchars($rpe) ?>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="detail-badges">
                <span class="badge badge-date">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <?= date('d. m. Y', strtotime($workout['workout_date'])) ?>
                </span>
                <span class="badge badge-cat"><?= htmlspecialchars($workout['category_name'] ?? 'Nezařazeno') ?></span>
            </div>
        </div>
    </div>

    <div class="detail-byline">
        <span class="byline-label">Autor záznamu</span>
        <?= workoutAuthorBadge($workout['author_name'] ?? '') ?>
        <?php if (!empty($workout['editor_name']) && $workout['editor_name'] !== $workout['author_name']): ?>
            <span class="byline-sep">·</span>
            <span class="byline-edit">naposledy upravil <strong><?= htmlspecialchars($workout['editor_name']) ?></strong></span>
        <?php endif; ?>
    </div>

    <?php
        $images = !empty($workout['images']) ? json_decode($workout['images'], true) : [];
        if (!empty($images)): ?>
        <div class="workout-gallery">
            <?php foreach ($images as $img): ?>
                <img src="/WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/uploads/<?= htmlspecialchars($img) ?>" alt="Fotka z tréninku">
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="detail-stats-grid">
        <div class="detail-stat">
            <span class="stat-head"><svg class="sh-icon sh-time" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> Délka trvání</span>
            <span class="stat-val"><?= htmlspecialchars($workout['duration_min'] ?? '0') ?> <small>min</small></span>
        </div>
        <div class="detail-stat">
            <span class="stat-head"><svg class="sh-icon sh-fire" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path></svg> Spáleno</span>
            <span class="stat-val"><?= !empty($workout['calories_burned']) ? htmlspecialchars($workout['calories_burned']) : '---' ?> <small>kcal</small></span>
        </div>
        <div class="detail-stat">
            <span class="stat-head"><svg class="sh-icon sh-loc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> Lokace</span>
            <span class="stat-val" style="font-size: 1.1rem; line-height: 1.3; margin-top: 4px; display:block;"><?= htmlspecialchars($workout['location'] ?? 'Nezadáno') ?></span>
        </div>
    </div>

    <?php $exercises = !empty($workout['exercises']) ? json_decode($workout['exercises'], true) : []; ?>
    <?php if (!empty($exercises)): ?>
    <div class="detail-exercises">
        <h4 class="exercises-title">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="4" height="12" rx="1"></rect><rect x="18" y="6" width="4" height="12" rx="1"></rect><line x1="6" y1="12" x2="18" y2="12"></line></svg>
            Odcvičené cviky
        </h4>
        <table class="exercises-table">
            <thead><tr><th>Cvik</th><th>Váha</th><th>Opakování</th></tr></thead>
            <tbody>
            <?php foreach ($exercises as $ex): ?>
                <tr>
                    <td><?= htmlspecialchars($ex['name'] ?? '') ?></td>
                    <td><?php if (isset($ex['weight']) && $ex['weight'] !== null && $ex['weight'] !== ''): ?><span class="ex-num"><?= htmlspecialchars($ex['weight']) ?></span> kg<?php else: ?><span style="color:#475569;">—</span><?php endif; ?></td>
                    <td><?php if (isset($ex['reps']) && $ex['reps'] !== null && $ex['reps'] !== ''): ?><span class="ex-num"><?= htmlspecialchars($ex['reps']) ?></span>×<?php else: ?><span style="color:#475569;">—</span><?php endif; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <?php if (!empty(trim($workout['notes']))): ?>
    <div class="detail-notes">
        <h4 class="notes-title">Tvé poznámky k tréninku</h4>
        <p class="notes-text"><?= htmlspecialchars($workout['notes']) ?></p>
    </div>
    <?php endif; ?>
    
    <?php if ((isset($_SESSION['user_id']) && $_SESSION['user_id'] === $workout['created_by']) || (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1)): ?>
    <div class="detail-actions">
        <a href="index.php?url=workout/edit/<?= htmlspecialchars($workout['id']) ?>" class="btn-edit-large">Upravit záznam</a>
    </div>
    <?php endif; ?>
</div>

<!-- KOMENTÁŘE K TRÉNINKU -->
<div class="comments-section">
    <h3 class="comments-title">Komentáře <small>(<?= count($comments ?? []) ?>)</small></h3>

    <?php if (isset($_SESSION['user_id'])): ?>
        <form class="comment-form" action="index.php?url=comment/store/<?= htmlspecialchars($workout['id']) ?>" method="POST">
            <textarea name="content" placeholder="Napiš komentář k tréninku..." rows="3" required></textarea>
            <button type="submit" class="btn-comment-submit">Přidat komentář</button>
        </form>
    <?php else: ?>
        <div class="comment-cta">Pro přidání komentáře se <a href="index.php?url=auth/login">přihlas</a>.</div>
    <?php endif; ?>

    <?php if (empty($comments)): ?>
        <div class="no-comments">Zatím žádné komentáře — buď první!</div>
    <?php else: ?>
        <div class="comments-list">
            <?php foreach ($comments as $c):
                $isOwn = isset($_SESSION['user_id']) && (int)$c['user_id'] === (int)$_SESSION['user_id'];
                $isAdminUser = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
                $authorDisplay = !empty($c['author_nickname']) ? $c['author_nickname'] : ($c['author_username'] ?? 'Neznámý');
            ?>
                <div class="comment">
                    <div class="comment-head">
                        <?= workoutAuthorBadge($authorDisplay) ?>
                        <span class="comment-date"><?= date('d. m. Y H:i', strtotime($c['created_at'])) ?></span>
                    </div>
                    <p class="comment-content"><?= htmlspecialchars($c['content']) ?></p>
                    <?php if ($isOwn || $isAdminUser): ?>
                        <div class="comment-actions">
                            <?php if ($isOwn): ?>
                                <a href="index.php?url=comment/edit/<?= htmlspecialchars($c['id']) ?>" class="comment-action">Upravit</a>
                            <?php endif; ?>
                            <a href="index.php?url=comment/delete/<?= htmlspecialchars($c['id']) ?>"
                               class="comment-action comment-del"
                               onclick="return confirm('Opravdu smazat tento komentář?')">Smazat</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>