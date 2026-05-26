<?php require_once __DIR__ . '/../layout/header.php'; ?>

<style>
    .users-wrapper { max-width: 980px; margin: 0 auto; padding: 10px 20px; }
    .back-nav { display: inline-flex; align-items: center; gap: 8px; color: #64748b; text-decoration: none; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; transition: color 0.2s; margin-bottom: 18px; }
    .back-nav:hover { color: #ffffff; }

    .users-header { display: flex; align-items: center; gap: 16px; margin-bottom: 22px; }
    .users-header-icon { width: 52px; height: 52px; border-radius: 14px; background: rgba(139, 92, 246, 0.12); color: #8b5cf6; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .page-title { font-size: 1.5rem; font-weight: 800; color: #ffffff; margin: 0; letter-spacing: -0.3px; text-transform: uppercase; }
    .page-subtitle { color: #94a3b8; margin: 4px 0 0 0; font-size: 0.9rem; }

    .users-card { background: rgba(30, 41, 59, 0.4); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); overflow: hidden; }
    .users-table { width: 100%; border-collapse: collapse; font-size: 0.92rem; }
    .users-table th { background: rgba(15, 23, 42, 0.6); color: #94a3b8; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; text-align: left; padding: 14px 16px; border-bottom: 1px solid rgba(255, 255, 255, 0.06); white-space: nowrap; }
    .users-table td { padding: 14px 16px; color: #cbd5e1; vertical-align: middle; border-bottom: 1px solid rgba(255, 255, 255, 0.04); }
    .users-table tr:last-child td { border-bottom: none; }
    .users-table tbody tr:hover td { background: rgba(255, 255, 255, 0.02); }

    .uid-cell { color: #64748b; font-family: ui-monospace, SFMono-Regular, monospace; font-size: 0.8rem; }
    .uname-cell { color: #ffffff; font-weight: 700; }
    .uname-cell .uname-sub { display: block; color: #64748b; font-size: 0.78rem; font-weight: 500; margin-top: 2px; }
    .role-badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
    .role-admin { background: rgba(139, 92, 246, 0.15); color: #c4b5fd; border: 1px solid rgba(139, 92, 246, 0.3); }
    .role-user { background: rgba(255, 255, 255, 0.05); color: #94a3b8; }
    .actions-cell { text-align: center; white-space: nowrap; width: 110px; }
    .th-actions { text-align: center !important; }
    .btn-del { display: inline-block; background: rgba(244, 63, 94, 0.1); color: #fb7185; padding: 6px 14px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; text-decoration: none; transition: background-color 0.2s, color 0.2s; }
    .btn-del:hover { background: rgba(244, 63, 94, 0.2); color: #f43f5e; }
</style>

<div class="users-wrapper">
    <a href="index.php" class="back-nav">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Zpět
    </a>

    <div class="users-header">
        <div class="users-header-icon">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <div>
            <h2 class="page-title">Správa uživatelů</h2>
            <p class="page-subtitle">Sekce dostupná pouze administrátorovi.</p>
        </div>
    </div>

    <div class="users-card">
        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Uživatel</th>
                    <th>Jméno</th>
                    <th>E-mail</th>
                    <th>Role</th>
                    <th>Registrace</th>
                    <th class="th-actions">Akce</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td class="uid-cell">#<?= htmlspecialchars($u['id']) ?></td>
                        <td class="uname-cell">
                            <?= workoutAuthorBadge($u['nickname'] ?: $u['username']) ?>
                            <span class="uname-sub">@<?= htmlspecialchars($u['username']) ?></span>
                        </td>
                        <td>
                            <?php
                            $full = trim(($u['first_name'] ?? '') . ' ' . ($u['last_name'] ?? ''));
                            echo $full !== '' ? htmlspecialchars($full) : '<span style="color:#475569;">—</span>';
                            ?>
                        </td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <?php if ((int)$u['is_admin'] === 1): ?>
                                <span class="role-badge role-admin">Admin</span>
                            <?php else: ?>
                                <span class="role-badge role-user">Uživatel</span>
                            <?php endif; ?>
                        </td>
                        <td style="color:#94a3b8; white-space:nowrap;"><?= date('d. m. Y', strtotime($u['created_at'])) ?></td>
                        <td class="actions-cell">
                            <?php if ((int)$u['id'] !== (int)$_SESSION['user_id']): ?>
                                <a href="index.php?url=user/delete/<?= htmlspecialchars($u['id']) ?>"
                                   class="btn-del"
                                   onclick="return confirm('Opravdu smazat uživatele &quot;<?= htmlspecialchars(addslashes($u['username'])) ?>&quot; včetně jeho tréninků?')">Smazat</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
