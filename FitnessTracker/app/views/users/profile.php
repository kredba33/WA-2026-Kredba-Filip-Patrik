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

    .form-group input {
        width: 100%; padding: 14px 16px; border: 1px solid #1e293b; border-radius: 10px;
        background-color: #0f172a; font-family: inherit; font-size: 1rem; color: #ffffff;
        box-sizing: border-box; transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-group input:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12); }
    .form-group input[readonly] { background: rgba(15, 23, 42, 0.5); color: #64748b; cursor: not-allowed; }

    .section-divider { grid-column: span 2; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #10b981; margin: 14px 0 -4px 0; padding-top: 18px; border-top: 1px solid rgba(255, 255, 255, 0.06); }
    .input-hint { display: block; margin-top: 8px; font-size: 0.78rem; color: #64748b; }

    .btn-submit { width: 100%; background: #10b981; color: #000000; padding: 16px; border-radius: 10px; border: none; font-weight: 800; font-size: 1rem; cursor: pointer; transition: background-color 0.2s; margin-top: 18px; text-transform: uppercase; letter-spacing: 1px; }
    .btn-submit:hover { background: #059669; }

    @media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } .full-width { grid-column: span 1; } .section-divider { grid-column: span 1; } }
</style>

<div class="page-wrapper">
    <a href="index.php" class="back-nav">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Zpět
    </a>

    <div class="form-card">
        <div class="form-header">
            <div class="form-header-icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </div>
            <div>
                <h2 class="page-title">Můj profil</h2>
                <p class="page-subtitle">Tady si upravíš své údaje a heslo.</p>
            </div>
        </div>

        <form action="index.php?url=user/update" method="POST">
            <div class="form-grid">

                <div class="form-group">
                    <label for="username">Uživatelské jméno</label>
                    <input type="text" id="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" required value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="first_name">Křestní jméno</label>
                    <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="last_name">Příjmení</label>
                    <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>">
                </div>

                <div class="form-group full-width">
                    <label for="nickname">Přezdívka (zobrazí se v aplikaci)</label>
                    <input type="text" name="nickname" id="nickname" value="<?= htmlspecialchars($user['nickname'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="height_cm">Výška (cm)</label>
                    <input type="number" name="height_cm" id="height_cm" min="50" max="250" value="<?= htmlspecialchars($user['height_cm'] ?? '') ?>" placeholder="Např. 180">
                </div>

                <div class="form-group">
                    <label for="weight_kg">Váha (kg)</label>
                    <input type="number" name="weight_kg" id="weight_kg" step="0.1" min="20" max="300" value="<?= htmlspecialchars($user['weight_kg'] ?? '') ?>" placeholder="Např. 78.5">
                </div>

                <div class="section-divider">Změna hesla (nepovinné)</div>

                <div class="form-group">
                    <label for="new_password">Nové heslo</label>
                    <input type="password" name="new_password" id="new_password" minlength="8" pattern="(?=.*[A-Za-z])(?=.*\d).{8,}" title="Alespoň 8 znaků, jedno písmeno a jedna číslice." autocomplete="new-password">
                    <small class="input-hint">Nech prázdné, pokud heslo měnit nechceš.</small>
                </div>

                <div class="form-group">
                    <label for="new_password_confirm">Potvrzení nového hesla</label>
                    <input type="password" name="new_password_confirm" id="new_password_confirm" autocomplete="new-password">
                </div>

                <div class="form-group full-width">
                    <button type="submit" class="btn-submit">Uložit změny</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
