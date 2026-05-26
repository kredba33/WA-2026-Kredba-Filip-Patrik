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

    .btn-submit { width: 100%; background: #10b981; color: #000000; padding: 16px; border-radius: 8px; border: none; font-weight: 800; font-size: 1rem; cursor: pointer; transition: background-color 0.2s; margin-top: 20px; text-transform: uppercase; letter-spacing: 1px; }
    .btn-submit:hover { background: #059669; }

    .custom-upload-box { border: 1px dashed #334155; border-radius: 8px; padding: 24px; text-align: center; cursor: pointer; transition: border-color 0.2s, color 0.2s, background-color 0.2s; background: #0f172a; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; color: #94a3b8; }
    .custom-upload-box:hover { border-color: #10b981; color: #10b981; }
    .custom-upload-box input[type="file"] { display: none; }
    .upload-icon { color: #10b981; margin-bottom: 4px; }
    .upload-text { font-size: 0.9rem; font-weight: 600; }
    .upload-hint { font-size: 0.75rem; color: #64748b; }

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
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg> Zpět
    </a>

    <div class="form-card">
        <div class="form-header">
            <div class="form-header-icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
            </div>
            <div>
                <h2 class="page-title">Zapsat trénink</h2>
                <p class="page-subtitle">Zaznamenej svůj dnešní výkon do deníku.</p>
            </div>
        </div>

        <form action="index.php?url=workout/store" method="POST" enctype="multipart/form-data">
        <div class="form-grid">
            <div class="form-group full-width">
                <label for="title">Název tréninku <span>*</span></label>
                <input type="text" name="title" id="title" required placeholder="Např. Upper Body">
            </div>

            <div class="form-group">
                <label for="workout_date">Datum</label>
                <input type="date" name="workout_date" id="workout_date" value="<?= date('Y-m-d') ?>">
            </div>

            <div class="form-group">
                <label for="location">Lokace (Kde)</label>
                <input type="text" name="location" id="location" placeholder="Např. Olymp Fitness">
            </div>

            <div class="form-group">
                <label for="duration_min">Délka (min)</label>
                <input type="number" name="duration_min" id="duration_min" placeholder="60" min="1">
            </div>

            <div class="form-group">
                <label for="calories_burned">Spáleno (kcal)</label>
                <input type="number" name="calories_burned" id="calories_burned" placeholder="Např. 450">
            </div>

            <div class="form-group">
                <label for="rpe">Náročnost RPE (1-10)</label>
                <input type="number" name="rpe" id="rpe" min="1" max="10" placeholder="1 = lehké, 10 = max">
            </div>

            <div class="form-group full-width">
                <label for="category">Kategorie</label>
                <select name="category" id="category">
                    <option value="">-- Vyber --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group full-width">
                <label for="notes">Poznámky</label>
                <textarea name="notes" id="notes" rows="3" placeholder="Jak to dneska šlo?"></textarea>
            </div>

            <div class="form-group full-width">
                <label>Cviky (název, váha v kg, opakování)</label>
                <div class="exercise-list" id="exercise-list"></div>
                <button type="button" class="btn-add-exercise" onclick="addExerciseRow()">+ Přidat cvik</button>
            </div>

            <div class="form-group full-width">
                <label for="images">Fotky z tréninku</label>
                <label for="images" class="custom-upload-box">
                    <svg class="upload-icon" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                    <span class="upload-text" id="file-title">Klikni pro výběr fotek</span>
                    <span class="upload-hint" id="file-info">Můžeš nahrát i více snímků naráz</span>
                    <input type="file" name="images[]" id="images" multiple accept="image/*">
                </label>
            </div>

            <div class="form-group full-width">
                <button type="submit" class="btn-submit">Uložit trénink</button>
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
            fileInfo.textContent = 'Můžeš nahrát i více snímků naráz';
        } else if (files.length === 1) {
            fileTitle.textContent = 'Fotka připravena';
            fileInfo.textContent = files[0].name;
        } else {
            fileTitle.textContent = 'Fotky připraveny';
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
    // jeden prázdný řádek na začátku
    addExerciseRow();
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>