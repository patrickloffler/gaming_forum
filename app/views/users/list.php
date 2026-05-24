<?php require_once '../app/views/layout/header.php'; ?>

<div class="container mx-auto px-4 py-8 max-w-5xl">

    <div class="flex items-center justify-between mb-6">
        <h2 class="orbitron text-xl font-bold tracking-widest">
            <span style="color: #ffd700; text-shadow: 0 0 10px #ffd700;"> ADMIN</span>
            <span class="text-slate-400 text-lg"> — SPRÁVA UŽIVATELŮ</span>
        </h2>
        <span class="text-slate-600 text-xs orbitron"><?= count($users) ?> uživatelů celkem</span>
    </div>

    <div class="bg-[#0d1117] border border-[#1f2937] rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-[#1f2937] bg-[#080b0f]">
                    <th class="px-4 py-3 text-left text-xs orbitron text-slate-500 tracking-widest uppercase">ID</th>
                    <th class="px-4 py-3 text-left text-xs orbitron text-slate-500 tracking-widest uppercase">Uživatel</th>
                    <th class="px-4 py-3 text-left text-xs orbitron text-slate-500 tracking-widest uppercase">E-mail</th>
                    <th class="px-4 py-3 text-left text-xs orbitron text-slate-500 tracking-widest uppercase">Role</th>
                    <th class="px-4 py-3 text-left text-xs orbitron text-slate-500 tracking-widest uppercase">Registrace</th>
                    <th class="px-4 py-3 text-center text-xs orbitron text-slate-500 tracking-widest uppercase">Akce</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#1f2937]">
                <?php foreach ($users as $u): ?>
                <?php
                    $uName = !empty($u['nickname']) ? $u['nickname'] : $u['username'];
                    $roleColors = ['admin' => '#ffd700', 'moderator' => 'var(--neon-purple)', 'user' => '#4b5563'];
                    $roleColor  = $roleColors[$u['role']] ?? '#4b5563';
                    $roleLabels = ['admin' => 'Admin', 'moderator' => 'Mod', 'user' => 'Člen'];
                    $roleLabel  = $roleLabels[$u['role']] ?? $u['role'];
                    $isSelf = $u['id'] === $_SESSION['user_id'];
                ?>
                <tr class="hover:bg-[#111827] transition-colors <?= $isSelf ? 'bg-[#0d1a1a]' : '' ?>">
                    <td class="px-4 py-3 text-slate-600 orbitron text-xs">#<?= $u['id'] ?></td>
                    <td class="px-4 py-3">
                        <a href="<?= BASE_URL ?>/index.php?url=user/profile/<?= $u['id'] ?>"
                           class="font-bold text-slate-200 hover:text-cyan-400 transition-colors">
                            <?= htmlspecialchars($uName) ?>
                        </a>
                        <div class="text-xs text-slate-600">@<?= htmlspecialchars($u['username']) ?></div>
                        <?php if ($isSelf): ?>
                            <span class="text-xs" style="color: var(--neon-cyan)">(ty)</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-slate-500 text-xs"><?= htmlspecialchars($u['email']) ?></td>
                    <td class="px-4 py-3">
                        <span class="platform-badge border py-0.5"
                              style="border-color:<?= $roleColor ?>;color:<?= $roleColor ?>">
                            <?= $roleLabel ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-slate-600 text-xs"><?= date('d.m.Y', strtotime($u['created_at'])) ?></td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="<?= BASE_URL ?>/index.php?url=user/edit/<?= $u['id'] ?>"
                               class="btn-cyber btn-success px-3 py-1 rounded text-xs">UPRAVIT</a>
                            <?php if (!$isSelf): ?>
                            <a href="<?= BASE_URL ?>/index.php?url=user/delete/<?= $u['id'] ?>"
                               onclick="return confirm('Opravdu smazat uživatele <?= htmlspecialchars(addslashes($uName)) ?>?')"
                               class="btn-cyber btn-danger px-3 py-1 rounded text-xs">SMAZAT</a>
                            <?php else: ?>
                            <span class="text-slate-700 text-xs px-3 py-1">—</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>