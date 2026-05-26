<?php require_once __DIR__ . '/../layout/header.php'; ?>

<style>
    h2.page-title { font-size: 2rem; font-weight: 800; color: #ffffff; margin: 0 0 10px 0; text-align: center; letter-spacing: 1px; text-transform: uppercase; }
    p.page-subtitle { color: #94a3b8; margin-bottom: 35px; font-size: 1.1rem; text-align: center; }

    .form-glass-wrapper.login-wrapper { 
        background: rgba(30, 41, 59, 0.4); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.05); box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        border-radius: 24px; padding: 50px 40px; max-width: 480px; margin: 0 auto 50px auto; 
    }

    .form-group { margin-bottom: 24px; }
    .form-group label { display: block; margin-bottom: 10px; font-weight: 700; color: #cbd5e1; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; }
    
    .form-group input { width: 100%; padding: 16px 20px; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; background: rgba(15, 23, 42, 0.8); font-family: inherit; font-size: 1.05rem; color: #ffffff; box-sizing: border-box; transition: 0.2s; }
    .form-group input:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15); }
    
    .password-wrapper { position: relative; display: flex; align-items: center; }
    .password-wrapper input { padding-right: 45px !important; }
    .btn-toggle-password { position: absolute; right: 12px; background: transparent; border: none; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0; transition: color 0.2s ease; outline: none; }
    .btn-toggle-password:hover { color: #10b981; }

    .btn-submit { width: 100%; background: #10b981; color: #000000; padding: 20px; border-radius: 12px; border: none; font-weight: 800; font-size: 1.1rem; cursor: pointer; transition: 0.3s; margin-top: 10px; text-transform: uppercase; letter-spacing: 1px;}
    .btn-submit:hover { background: #059669; box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2); }

    .auth-link { text-align: center; margin-top: 25px; color: #94a3b8; font-size: 0.95rem; border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 20px; }
    .auth-link a { color: #10b981; text-decoration: none; font-weight: 600; transition: color 0.2s; }
    .auth-link a:hover { color: #34d399; text-decoration: underline; }
</style>

<h2 class="page-title">Přihlášení</h2>
<p class="page-subtitle">Vítejte zpět ve Fitness Trackeru.</p>

<div class="form-glass-wrapper login-wrapper">
    <form action="/WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=auth/authenticate" method="POST">
        
        <div class="form-group">
            <label for="username">Uživatelské jméno</label>
            <!-- 💡 Změna z email na username -->
            <input type="text" id="username" name="username" required autofocus placeholder="Tvoje přezdívka">
        </div>

        <div class="form-group">
            <label for="password">Heslo</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" required placeholder="••••••••">
                <button type="button" class="btn-toggle-password" onclick="togglePassword('password', this)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-submit">Přihlásit se</button>
        
        <div class="auth-link">
            Nemáš ještě účet? <a href="/WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=auth/register">Zaregistruj se</a>
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