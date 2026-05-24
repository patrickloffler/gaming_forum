<?php require_once '../app/views/layout/header.php'; ?>

<?php
$displayName = !empty($user['nickname']) ? $user['nickname'] : $user['username'];
$isOwnProfile = isset($_SESSION['user_id']) && $_SESSION['user_id'] === $user['id'];
$roleLabels = ['admin' => ' Admin', 'moderator' => 'Moderátor', 'user' => 'Člen'];
$roleLabel  = $roleLabels[$user['role']] ?? 'Člen';
$roleColors = ['admin' => '#ffd700', 'moderator' => 'var(--neon-purple)', 'user' => '#4b5563'];
$roleColor  = $roleColors[$user['role']] ?? '#4b5563';
?>

<div class="container mx-auto px-4 py-8 max-w-4xl">

    <!-- Profil header -->
    <div class="bg-[#0d1117] border border-[#1f2937] rounded-lg p-6 mb-6">
        <div class="flex items-start gap-6">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                <?php if ($user['avatar']): ?>
                    <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($user['avatar']) ?>" 
                         class="w-20 h-20 rounded border-2 object-cover" 
                         style="border-color: <?= $roleColor ?>; box-shadow: 0 0 15px <?= $roleColor ?>40" alt="avatar">
                <?php else: ?>
                    <div class="w-20 h-20 rounded border-2 flex items-center justify-center orbitron font-black text-2xl"
                         style="border-color: <?= $roleColor ?>; background: #0d1117; color: <?= $roleColor ?>; box-shadow: 0 0 15px <?= $roleColor ?>40">
                        <?= strtoupper(substr($displayName, 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="flex-1">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="orbitron text-2xl font-black text-white mb-1"><?= htmlspecialchars($displayName) ?></h1>
                        <p class="text-slate-600 text-sm mb-2">@<?= htmlspecialchars($user['username']) ?></p>
                        <span class="platform-badge border py-1" 
                              style="border-color: <?= $roleColor ?>; color: <?= $roleColor ?>; text-shadow: 0 0 6px <?= $roleColor ?>">
                            <?= $roleLabel ?>
                        </span>
                    </div>
                    
                    <?php if ($isOwnProfile || (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin')): ?>
                    <a href="<?= BASE_URL ?>/index.php?url=user/edit/<?= $user['id'] ?>" 
                       class="btn-cyber btn-success px-4 py-2 rounded text-xs flex-shrink-0">UPRAVIT PROFIL</a>
                    <?php endif; ?>
                </div>

                <?php if ($user['bio']): ?>
                <p class="text-slate-400 text-sm mt-3 leading-relaxed"><?= nl2br(htmlspecialchars($user['bio'])) ?></p>
                <?php endif; ?>

                <div class="flex gap-6 mt-4 text-xs text-slate-600">
                    <span> Člen od <?= date('d.m.Y', strtotime($user['created_at'])) ?></span>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Příspěvky uživatele -->
    <h2 class="orbitron text-lg font-bold tracking-widest mb-4">
        <span class="neon-cyan">PŘÍSPĚVKY</span> <span class="text-slate-600 text-sm">(<?= count($posts) ?>)</span>
    </h2>

    <?php if (empty($posts)): ?>
        <div class="bg-[#0d1117] border border-[#1f2937] rounded-lg p-10 text-center">
            <p class="orbitron text-slate-700 text-xs tracking-widest">// ŽÁDNÉ PŘÍSPĚVKY //</p>
        </div>
    <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($posts as $post): ?>
            <div class="game-card bg-[#0d1117] rounded-lg p-4 flex items-center gap-4">
                <div class="w-1 h-12 rounded flex-shrink-0" 
                     style="background: <?= htmlspecialchars($post['category_color'] ?? '#374151') ?>"></div>
                <div class="flex-1 min-w-0">
                    <a href="<?= BASE_URL ?>/index.php?url=post/show/<?= $post['id'] ?>"
                       class="font-bold text-white hover:text-cyan-400 transition-colors text-sm block truncate">
                        <?= htmlspecialchars($post['title']) ?>
                    </a>
                    <div class="flex gap-3 mt-1 text-xs text-slate-600">
                        <span><?= htmlspecialchars($post['category_name'] ?? 'Bez kategorie') ?></span>
                        <span><?= date('d.m.Y', strtotime($post['created_at'])) ?></span>
                        <span>💬 <?= (int)$post['comment_count'] ?></span>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>/index.php?url=post/show/<?= $post['id'] ?>" 
                   class="btn-cyber px-3 py-1 rounded text-xs flex-shrink-0">ČÍST</a>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>