<?php
// Vykreslí "odznak" autora: barevné kolečko s iniciálami + jméno.
// Barva se odvozuje z hashe jména -> pro daného uživatele je vždy stejná.
if (!function_exists('workoutAuthorBadge')) {
    function workoutAuthorBadge($name) {
        $name = trim((string)($name ?? ''));
        if ($name === '') { $name = 'Neznámý'; }

        $parts = preg_split('/\s+/', $name);
        if (count($parts) >= 2) {
            $initials = mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1);
        } else {
            $initials = mb_substr($name, 0, 2);
        }
        $initials = mb_strtoupper($initials);

        $hue = abs(crc32($name)) % 360;          // deterministický odstín z názvu
        $bg = "hsl({$hue}, 55%, 45%)";

        $safeName = htmlspecialchars($name);
        $safeInit = htmlspecialchars($initials);
        return '<span class="author-badge" title="Přidal: ' . $safeName . '">'
             . '<span class="author-avatar" style="background:' . $bg . ';">' . $safeInit . '</span>'
             . '<span class="author-name">' . $safeName . '</span>'
             . '</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Tracker</title>
    
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 36 22' fill='none' stroke='%2310b981' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'><rect x='1' y='6' width='3' height='10' rx='1'/><line x1='4' y1='11' x2='10' y2='11'/><polyline points='10,11 13,11 15,4 18,18 20,11 26,11'/><line x1='26' y1='11' x2='32' y2='11'/><rect x='32' y='6' width='3' height='10' rx='1'/></svg>">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;700;800&display=swap');

        /* TEMNÝ FITNESS MOTIV */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #0b0f19;
            background-image: 
                radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.05) 0, transparent 50%), 
                radial-gradient(at 100% 100%, rgba(16, 185, 129, 0.05) 0, transparent 50%);
            color: #f1f5f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            position: sticky; top: 15px; z-index: 50; max-width: 1040px; margin: 0 auto; width: calc(100% - 40px); 
            background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 20px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
            height: 70px; display: flex; align-items: center; justify-content: space-between; padding: 0 30px; box-sizing: border-box;
        }

        header .logo { display: flex; align-items: center; font-size: 1.4rem; font-weight: 800; color: #ffffff; text-decoration: none; letter-spacing: -0.5px; transition: opacity 0.3s ease; }
        header .logo:hover { opacity: 0.8; }
        
        /* ČISTÁ NAVIGACE ZAROVNANÁ DOPRAVA */
        header nav { display: flex; align-items: center; gap: 24px; }

        header nav a.nav-link { text-decoration: none; color: #94a3b8; font-size: 0.95rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: color 0.3s ease; position: relative; }
        header nav a.nav-link:hover { color: #ffffff; }

        /* Ikonka pro odhlášení místo textu */
        header nav a.logout-icon { color: #64748b; transition: all 0.2s ease; display: flex; align-items: center; padding: 8px; border-radius: 8px; }
        header nav a.logout-icon:hover { color: #f43f5e; background: rgba(244, 63, 94, 0.1); }

        /* UPRAVENÉ TLAČÍTKO - Bez transform: translateY */
        header nav a.btn-pill { background-color: #10b981; color: #000000; padding: 10px 20px; border-radius: 12px; font-size: 0.85rem; font-weight: 800; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; transition: background-color 0.2s ease; }
        header nav a.btn-pill:hover { background-color: #059669; }

        header .user-greeting { font-size: 0.9rem; color: #ffffff; font-weight: 700; letter-spacing: 0.2px; display: flex; align-items: center; text-decoration: none; transition: opacity 0.2s ease; }
        header .user-greeting:hover { opacity: 0.85; }
        header .user-greeting .admin-badge { background: #10b981; color: #000; padding: 2px 6px; border-radius: 6px; font-size: 0.6rem; font-weight: 800; margin-left: 8px; text-transform: uppercase; letter-spacing: 1px; }
        header nav a.admin-link { color: #c4b5fd; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; text-decoration: none; padding: 6px 12px; border-radius: 8px; background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.25); transition: background-color 0.2s; }
        header nav a.admin-link:hover { background: rgba(139, 92, 246, 0.2); }

        .main-wrapper { flex: 1; width: 100%; max-width: 1040px; margin: 0 auto; padding: 40px 20px 50px 20px; box-sizing: border-box; }

        h2.page-title { color: #ffffff !important; text-transform: uppercase; font-weight: 800 !important; letter-spacing: 1px !important; }
        p.page-subtitle { color: #94a3b8 !important; }
        
        .table-glass-wrapper, .form-glass-wrapper, .detail-glass-wrapper {
            background: rgba(30, 41, 59, 0.4) !important; border: 1px solid rgba(255, 255, 255, 0.05) !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3) !important; border-radius: 16px !important;
        }

        .books-table th { background: rgba(15, 23, 42, 0.8) !important; color: #94a3b8 !important; border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;}
        .books-table td, .detail-table td, .detail-table th { color: #cbd5e1 !important; border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important; }
        .books-table tbody tr:hover td { background: rgba(255, 255, 255, 0.02) !important; }
        .book-title, strong { color: #ffffff !important; font-weight: 700 !important; }
        
        .empty-state { background: rgba(30, 41, 59, 0.3) !important; border-color: rgba(255, 255, 255, 0.1) !important; color: #94a3b8 !important; }
        
        .form-group label { color: #cbd5e1 !important; letter-spacing: 1px !important; }
        .form-group input, .form-group textarea, .form-group select { background: rgba(15, 23, 42, 0.8) !important; color: #ffffff !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { border-color: #10b981 !important; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15) !important; }
        .custom-upload-box { background: rgba(15, 23, 42, 0.4) !important; border-color: rgba(255, 255, 255, 0.1) !important; }
        .custom-upload-box:hover { border-color: #10b981 !important; background: rgba(16, 185, 129, 0.05) !important; }
        #file-title { color: #ffffff !important; }
        
        .btn-submit { background: #10b981 !important; color: #000000 !important; font-weight: 800 !important; text-transform: uppercase; letter-spacing: 1px; border-radius: 12px !important; }
        .btn-submit:hover { background: #059669 !important; }
        
        .btn-action.btn-detail { background: rgba(255, 255, 255, 0.05) !important; color: #e2e8f0 !important; }
        .btn-action.btn-detail:hover { background: rgba(255, 255, 255, 0.1) !important; color: #ffffff !important; }
        .btn-action.btn-edit { background: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; border: 1px solid rgba(16, 185, 129, 0.2) !important; }
        .btn-action.btn-edit:hover { background: rgba(16, 185, 129, 0.2) !important; color: #34d399 !important; }
        .btn-action.btn-delete { background: rgba(244, 63, 94, 0.1) !important; color: #f43f5e !important; border: 1px solid rgba(244, 63, 94, 0.2) !important; }
        .btn-action.btn-delete:hover { background: rgba(244, 63, 94, 0.2) !important; color: #fb7185 !important; }

        .alert-container { position: relative; margin-bottom: 30px; min-height: 1px; }
        .alert-item { margin-bottom: 12px; transition: opacity 0.6s ease, transform 0.6s ease; opacity: 1; transform: translateY(0); }
        .alert-item.fade-out { opacity: 0; transform: translateY(-5px); pointer-events: none; }
        .alert { padding: 14px 20px; border-radius: 12px; font-size: 0.95rem; font-weight: 600; display: flex; align-items: center; justify-content: space-between; }
        .alert-success { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .alert-error { background: rgba(244, 63, 94, 0.15); color: #fb7185; border: 1px solid rgba(244, 63, 94, 0.3); }
        .alert-close { background: none; border: none; font-size: 1.2rem; cursor: pointer; color: inherit; opacity: 0.5; padding: 0; margin-left: 15px; transition: opacity 0.2s; }
        .alert-close:hover { opacity: 1; }

        /* Odznak autora (avatar s iniciálami + jméno) */
        .author-badge { display: inline-flex; align-items: center; gap: 7px; vertical-align: middle; }
        .author-avatar { width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 800; color: #ffffff; flex-shrink: 0; letter-spacing: 0.3px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.35); }
        .author-name { font-size: 0.8rem; font-weight: 600; color: #cbd5e1; }
    </style>
</head>
<body>

    <header>
        <a href="/WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php" class="logo">
            <!-- Vlastní logo: činka + tep skrz tyč -->
            <svg width="40" height="26" viewBox="0 0 36 22" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px; color: #10b981;">
                <rect x="1" y="6" width="3" height="10" rx="1"></rect>
                <line x1="4" y1="11" x2="10" y2="11"></line>
                <polyline points="10,11 13,11 15,4 18,18 20,11 26,11"></polyline>
                <line x1="26" y1="11" x2="32" y2="11"></line>
                <rect x="32" y="6" width="3" height="10" rx="1"></rect>
            </svg>
            FITNESS<span style="color: #10b981;">TRACKER</span>
        </a>

        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=user/profile" class="user-greeting" title="Můj profil">
                    <?= htmlspecialchars($_SESSION['user_name'] ?? 'Uživatel') ?>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                        <span class="admin-badge">Admin</span>
                    <?php endif; ?>
                </a>

                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                    <a href="/WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=user/index" class="admin-link" title="Správa uživatelů">Uživatelé</a>
                <?php endif; ?>

                <a href="/WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=workout/create" class="btn-pill">+ Přidat</a>
                
                <a href="/WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=auth/logout" class="logout-icon" title="Odhlásit se">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </a>
            <?php else: ?>
                <a href="/WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=auth/login" class="nav-link">Přihlásit</a>
                <a href="/WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=auth/register" class="btn-pill">Registrace</a>
            <?php endif; ?>
        </nav>
    </header>

    <main class="main-wrapper">
        
        <?php if (isset($_SESSION['messages']) && !empty($_SESSION['messages'])): ?>
            <div class="alert-container" id="global-alerts">
                <?php foreach ($_SESSION['messages'] as $type => $messages): ?>
                    <?php 
                        $styles = ['success' => 'alert-success', 'error' => 'alert-error', 'notice' => 'alert-notice'];
                        $style = $styles[$type] ?? 'alert-notice';
                    ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="alert-item">
                            <div class="alert <?= $style ?>">
                                <span><?= htmlspecialchars($message) ?></span>
                                <button class="alert-close" onclick="this.closest('.alert-item').classList.add('fade-out');">&times;</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <?php unset($_SESSION['messages']); ?>
            </div>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(function() {
                        const alerts = document.querySelectorAll('#global-alerts .alert-item');
                        alerts.forEach(function(alert) {
                            alert.classList.add('fade-out');
                        });
                    }, 4000); 
                });
            </script>
        <?php endif; ?>