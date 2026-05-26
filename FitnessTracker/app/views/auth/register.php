<?php require_once __DIR__ . '/../layout/header.php'; ?>

<style>
    h2.page-title { font-size: 2rem; font-weight: 800; color: #ffffff; margin: 0 0 10px 0; text-align: center; letter-spacing: 1px; text-transform: uppercase; }
    p.page-subtitle { color: #94a3b8; margin-bottom: 35px; font-size: 1.1rem; text-align: center; }

    .form-glass-wrapper { 
        background: rgba(30, 41, 59, 0.4); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.05); box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        border-radius: 24px; padding: 50px; max-width: 850px; margin: 0 auto 50px auto; 
    }
    
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .full-width { grid-column: span 2; }

    .section-title { font-size: 1.05rem; font-weight: 700; color: #10b981; text-transform: uppercase; letter-spacing: 1px; margin: 10px 0 5px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding-bottom: 10px; }

    .form-group { margin-bottom: 8px; }
    .form-group label { display: block; margin-bottom: 10px; font-weight: 700; color: #cbd5e1; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; }
    .form-group label span { color: #f43f5e; }
    
    .form-group input { width: 100%; padding: 16px 20px; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; background: rgba(15, 23, 42, 0.8); font-family: inherit; font-size: 1.05rem; color: #ffffff; box-sizing: border-box; transition: 0.2s; }
    .form-group input:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15); }

    /* Moderní obal pro heslo */
    .password-wrapper { position: relative; display: flex; align-items: center; }
    .password-wrapper input { padding-right: 45px !important; }
    .btn-toggle-password { position: absolute; right: 12px; background: transparent; border: none; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0; transition: color 0.2s ease; outline: none; }
    .btn-toggle-password:hover { color: #10b981; }
    input[type="password"]::-ms-reveal, input[type="password"]::-ms-clear { display: none; }

    .input-hint { display: block; margin-top: 8px; font-size: 0.8rem; color: #64748b; }

    .btn-submit { width: 100%; background: #10b981; color: #000000; padding: 20px; border-radius: 12px; border: none; font-weight: 800; font-size: 1.1rem; cursor: pointer; transition: 0.3s; margin-top: 20px; text-transform: uppercase; letter-spacing: 1px; }
    .btn-submit:hover { background: #059669; box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2); }

    .auth-link { text-align: center; margin-top: 25px; color: #94a3b8; font-size: 0.95rem; }
    .auth-link a { color: #10b981; text-decoration: none; font-weight: 600; transition: color 0.2s; }
    .auth-link a:hover { color: #34d399; text-decoration: underline; }
</style>

<h2 class="page-title">Nová registrace</h2>
<p class="page-subtitle">Vytvořte si účet a začněte trackovat své tréninky.</p>

<div class="form-glass-wrapper">
    <!-- 💡 Opravená cesta do FitnessTracker -->
    <form action="/WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=auth/storeUser" method="POST">
        <div class="form-grid">
            
            <div class="full-width section-title">Přihlašovací údaje</div>

            <div class="form-group">
                <label for="username">Uživatelské jméno <span>*</span></label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail <span>*</span></label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Heslo <span>*</span></label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required minlength="8" pattern="(?=.*[A-Za-z])(?=.*\d).{8,}" title="Alespoň 8 znaků, jedno písmeno a jedna číslice.">
                    <button type="button" class="btn-toggle-password" onclick="togglePassword('password', this)">
                        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
                <small class="input-hint">Min. 8 znaků, alespoň 1 písmeno a 1 číslice.</small>
            </div>

            <div class="form-group">
                <label for="password_confirm">Potvrzení hesla <span>*</span></label>
                <div class="password-wrapper">
                    <input type="password" id="password_confirm" name="password_confirm" required>
                    <button type="button" class="btn-toggle-password" onclick="togglePassword('password_confirm', this)">
                        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
            </div>

            <div class="full-width section-title" style="margin-top: 20px;">Osobní údaje (Volitelné)</div>

            <div class="form-group">
                <label for="first_name">Křestní jméno</label>
                <input type="text" id="first_name" name="first_name">
            </div>

            <div class="form-group">
                <label for="last_name">Příjmení</label>
                <input type="text" id="last_name" name="last_name">
            </div>

            <div class="form-group full-width">
                <label for="nickname">Zobrazovaná přezdívka</label>
                <input type="text" id="nickname" name="nickname" placeholder="Jak Ti máme v aplikaci říkat?">
            </div>

            <div class="form-group full-width">
                <button type="submit" class="btn-submit">Vytvořit účet</button>
                <div class="auth-link">
                    Už máš účet? <a href="/WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=auth/login">Přihlas se zde</a>.
                </div>
            </div>

        </div>
    </form>
</div>

<script>
function togglePassword(inputId, btnElement) {
    const input = document.getElementById(inputId);
    const eyeOpen = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
    const eyeClosed = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>`;

    if (input.type === 'password') {
        input.type = 'text';
        btnElement.innerHTML = eyeClosed; 
    } else {
        input.type = 'password';
        btnElement.innerHTML = eyeOpen; 
    }
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>