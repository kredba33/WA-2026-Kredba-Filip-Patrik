<?php require_once __DIR__ . '/../layout/header.php'; ?>

<style>
    .page-wrapper { max-width: 760px; margin: 0 auto; padding: 10px 20px; }
    .back-nav { display: inline-flex; align-items: center; gap: 8px; color: #64748b; text-decoration: none; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; transition: color 0.2s; margin-bottom: 24px; }
    .back-nav:hover { color: #ffffff; }

    .form-card { background: rgba(30, 41, 59, 0.4); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 24px; padding: 36px; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3); }
    .form-header { display: flex; align-items: center; gap: 16px; margin-bottom: 30px; padding-bottom: 24px; border-bottom: 1px solid rgba(255, 255, 255, 0.07); }
    .form-header-icon { width: 52px; height: 52px; border-radius: 14px; background: rgba(16, 185, 129, 0.12); color: #10b981; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .page-title { font-size: 1.5rem; font-weight: 800; color: #ffffff; margin: 0; letter-spacing: -0.3px; text-transform: uppercase; }
    .page-subtitle { color: #94a3b8; margin: 4px 0 0 0; font-size: 0.9rem; }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 22px 16px; }
    .full-width { grid-column: span 2; }

    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; }
    .form-group label span { color: #ef4444; }

    .form-group input, .form-group textarea, .form-group select {
        width: 100%; padding: 14px 16px; border: 1px solid #1e293b; border-radius: 10px;
        background-color: #0f172a; font-family: inherit; font-size: 1rem; color: #ffffff;
        box-sizing: border-box; transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12); }
    .form-group textarea { resize: vertical; }

    .form-group select {
        appearance: none; -webkit-appearance: none; -moz-appearance: none; cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2310b981' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 16px center; padding-right: 40px;
    }
    .form-group select option { background-color: #1e293b; color: #ffffff; padding: 12px; }

    .existing-images { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }
    .img-preview { width: 80px; height: 80px; object-fit: cover; border-radius: 10px; border: 1px solid rgba(255, 255, 255, 0.1); }
    .no-images-text { color: #64748b; font-size: 0.85rem; margin: 0 0 16px 0; }

    .custom-upload-box { border: 1px dashed #334155; border-radius: 8px; padding: 24px; text-align: center; cursor: pointer; transition: border-color 0.2s, color 0.2s, background-color 0.2s; background: #0f172a; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; color: #94a3b8; }
    .custom-upload-box:hover { border-color: #10b981; color: #10b981; }
    .custom-upload-box input[type="file"] { display: none; }
    .upload-icon { color: #10b981; margin-bottom: 4px; }
    .upload-text { font-size: 0.9rem; font-weight: 600; }
    .upload-hint { font-size: 0.75rem; color: #64748b; }
    
    .btn-submit { width: 100%; background: #10b981; color: #000000; padding: 16px; border-radius: 8px; border: none; font-weight: 800; font-size: 1rem; cursor: pointer; transition: background-color 0.2s; margin-top: 20px; text-transform: uppercase; letter-spacing: 1px; }
    .btn-submit:hover { background: #059669; }

    /* Cviky (dynamické řádky) */
    .exercise-list { display: flex; flex-direction: column; gap: 8px; margin-bottom: 10px; }
    .exercise-row { display: grid; grid-template-columns: 1fr 92px 92px 42px; gap: 8px; }
    .exercise-row input { width: 100%; padding: 12px; border: 1px solid #1e293b; border-radius: 10px; background-color: #0f172a; font-family: inherit; font-size: 0.95rem; color: #ffffff; box-sizing: border-box; transition: border-color 0.2s ease; }
    .exercise-row input:focus { outline: none; border-color: #10b981; }
    .btn-add-exercise { width: 100%; background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px dashed rgba(16, 185, 129, 0.35); border-radius: 10px; padding: 12px; cursor: pointer; font-weight: 700; font-size: 0.85rem; font-family: inherit; transition: background-color 0.2s; }
    .btn-add-exercise:hover { background: rgba(16, 185, 129, 0.18); }
    .btn-remove-exercise { background: rgba(244, 63, 94, 0.1); color: #fb7185; border: none; border-radius: 10px; cursor: pointer; font-size: 1.3rem; line-height: 1; transition: background-color 0.2s; }
    .btn-remove-exercise:hover { background: rgba(244, 63, 94, 0.2); }

    @media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } .full-width { grid-column: span 1; } .exercise-row { grid-template-columns: 1fr 64px 64px 38px; } }
</style>

<div class="page-wrapper">
    <a href="index.php" class="back-nav">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg> Zrušit úpravy
    </a>

    <div class="form-card">
        <div class="form-header">
            <div class="form-header-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            </div>
            <div>
                <h2 class="page-title">Úprava tréninku</h2>
                <p class="page-subtitle">Uprav detaily záznamu #<?= htmlspecialchars($workout['id']); ?>.</p>
            </div>
        </div>

        <form action="index.php?url=workout/update/<?= htmlspecialchars($workout['id']); ?>" method="POST" enctype="multipart/form-data">
        <div class="form-grid">
            <div class="form-group full-width">
                <label for="title">Název tréninku <span>*</span></label>
                <input type="text" name="title" id="title" required value="<?= htmlspecialchars($workout['title']); ?>">
            </div>

            <div class="form-group">
                <label for="workout_date">Datum</label>
                <input type="date" name="workout_date" id="workout_date" value="<?= htmlspecialchars($workout['workout_date']); ?>">
            </div>

            <div class="form-group">
                <label for="location">Lokace</label>
                <input type="text" name="location" id="location" value="<?= htmlspecialchars($workout['location']); ?>">
            </div>

            <div class="form-group">
                <label for="duration_min">Délka (min)</label>
                <input type="number" name="duration_min" id="duration_min" value="<?= htmlspecialchars($workout['duration_min']); ?>" min="1">
            </div>

            <div class="form-group">
                <label for="calories_burned">Spáleno (kcal)</label>
                <input type="number" name="calories_burned" id="calories_burned" value="<?= htmlspecialchars($workout['calories_burned'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="rpe">Náročnost RPE (1-10)</label>
                <input type="number" name="rpe" id="rpe" min="1" max="10" value="<?= htmlspecialchars($workout['rpe'] ?? ''); ?>" placeholder="1 = lehké, 10 = max">
            </div>

            <div class="form-group full-width">
                <label for="category">Kategorie</label>
                <select name="category" id="category">
                    <option value="">-- Vyber --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $workout['category']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group full-width">
                <label for="notes">Poznámky</label>
                <textarea name="notes" id="notes" rows="3"><?= htmlspecialchars($workout['notes'] ?? ''); ?></textarea>
            </div>

            <div class="form-group full-width">
                <label>Cviky (název, váha v kg, opakování)</label>
                <div class="exercise-list" id="exercise-list">
                    <?php
                    $currentExercises = !empty($workout['exercises']) ? json_decode($workout['exercises'], true) : [];
                    if (!empty($currentExercises)):
                        foreach ($currentExercises as $ex): ?>
                            <div class="exercise-row">
                                <input type="text" name="exercise_name[]" placeholder="Cvik (např. Bench press)" value="<?= htmlspecialchars($ex['name'] ?? '') ?>">
                                <input type="number" name="exercise_weight[]" placeholder="kg" step="0.5" min="0" value="<?= htmlspecialchars($ex['weight'] ?? '') ?>">
                                <input type="number" name="exercise_reps[]" placeholder="opak." min="0" value="<?= htmlspecialchars($ex['reps'] ?? '') ?>">
                                <button type="button" class="btn-remove-exercise" onclick="this.parentElement.remove()" title="Odebrat cvik">&times;</button>
                            </div>
                    <?php endforeach;
                    endif; ?>
                </div>
                <button type="button" class="btn-add-exercise" onclick="addExerciseRow()">+ Přidat cvik</button>
            </div>

            <div class="form-group full-width">
                <label>Aktuální fotky</label>
                <div class="existing-images">
                    <?php
                    $currentImages = !empty($workout['images']) ? json_decode($workout['images'], true) : [];
                    if ($currentImages):
                        foreach ($currentImages as $img): ?>
                            <img src="uploads/<?= htmlspecialchars($img) ?>" class="img-preview" title="<?= htmlspecialchars($img) ?>">
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-images-text">Zatím nejsou nahrané žádné fotky.</p>
                    <?php endif; ?>
                </div>
                <input type="hidden" name="old_images" value="<?= htmlspecialchars($workout['images'] ?? '') ?>">

                <label for="images">Nahrát nové fotky</label>
                <label for="images" class="custom-upload-box">
                    <svg class="upload-icon" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                    <span class="upload-text" id="file-title">Klikni pro výběr fotek</span>
                    <span class="upload-hint" id="file-info">Ponech prázdné, pokud nechceš měnit galerii</span>
                    <input type="file" name="images[]" id="images" multiple accept="image/*">
                </label>
            </div>

            <div class="form-group full-width">
                <button type="submit" class="btn-submit">Uložit změny</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
    const fileInput = document.getElementById('images');
    const fileTitle = document.getElementById('file-title');
    const fileInfo = document.getElementById('file-info');

    fileInput.addEventListener('change', function(event) {
        const files = event.target.files;
        if (files.length === 0) {
            fileTitle.textContent = 'Klikni pro výběr fotek';
            fileInfo.textContent = 'Ponech prázdné, pokud nechceš měnit galerii';
        } else if (files.length === 1) {
            fileTitle.textContent = 'Nová fotka připravena';
            fileInfo.textContent = files[0].name;
        } else {
            fileTitle.textContent = 'Nové fotky připraveny';
            fileInfo.textContent = 'Vybráno celkem: ' + files.length + ' souborů';
        }
    });

    // --- Dynamické řádky cviků ---
    function exerciseRowHtml(name, weight, reps) {
        const esc = s => String(s).replace(/"/g, '&quot;');
        return `<div class="exercise-row">
            <input type="text" name="exercise_name[]" placeholder="Cvik (např. Bench press)" value="${esc(name || '')}">
            <input type="number" name="exercise_weight[]" placeholder="kg" step="0.5" min="0" value="${esc(weight || '')}">
            <input type="number" name="exercise_reps[]" placeholder="opak." min="0" value="${esc(reps || '')}">
            <button type="button" class="btn-remove-exercise" onclick="this.parentElement.remove()" title="Odebrat cvik">&times;</button>
        </div>`;
    }
    function addExerciseRow(name, weight, reps) {
        document.getElementById('exercise-list').insertAdjacentHTML('beforeend', exerciseRowHtml(name, weight, reps));
    }
    // pokud trénink zatím nemá žádné cviky, nabídni jeden prázdný řádek
    if (document.getElementById('exercise-list').children.length === 0) {
        addExerciseRow();
    }
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>