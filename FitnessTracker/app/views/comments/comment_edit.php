<?php require_once __DIR__ . '/../layout/header.php'; ?>

<style>
    .page-wrapper { max-width: 680px; margin: 0 auto; padding: 10px 20px; }
    .back-nav { display: inline-flex; align-items: center; gap: 8px; color: #64748b; text-decoration: none; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; transition: color 0.2s; margin-bottom: 24px; }
    .back-nav:hover { color: #ffffff; }

    .form-card { background: rgba(30, 41, 59, 0.4); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 24px; padding: 32px; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3); }
    .page-title { font-size: 1.4rem; font-weight: 800; color: #ffffff; margin: 0 0 20px 0; text-transform: uppercase; letter-spacing: -0.3px; }

    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; }
    .form-group textarea {
        width: 100%; padding: 14px 16px; border: 1px solid #1e293b; border-radius: 10px;
        background-color: #0f172a; font-family: inherit; font-size: 1rem; color: #ffffff;
        box-sizing: border-box; transition: border-color 0.2s, box-shadow 0.2s;
        resize: vertical; min-height: 100px;
    }
    .form-group textarea:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12); }

    .btn-submit { width: 100%; background: #10b981; color: #000; padding: 14px; border-radius: 10px; border: none; font-weight: 800; font-size: 0.95rem; cursor: pointer; transition: background-color 0.2s; margin-top: 14px; text-transform: uppercase; letter-spacing: 1px; }
    .btn-submit:hover { background: #059669; }
</style>

<div class="page-wrapper">
    <a href="index.php?url=workout/show/<?= htmlspecialchars($comment['workout_id']) ?>" class="back-nav">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Zpět na trénink
    </a>

    <div class="form-card">
        <h2 class="page-title">Úprava komentáře</h2>
        <form action="index.php?url=comment/update/<?= htmlspecialchars($comment['id']) ?>" method="POST">
            <div class="form-group">
                <label for="content">Obsah komentáře</label>
                <textarea name="content" id="content" rows="4" required><?= htmlspecialchars($comment['content']) ?></textarea>
            </div>
            <button type="submit" class="btn-submit">Uložit změny</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
