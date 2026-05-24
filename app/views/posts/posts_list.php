<?php require_once '../app/views/layout/header.php'; ?>

<div class="container mx-auto px-4 py-8">

    <!-- Kategorie filtr -->
    <div class="flex flex-wrap gap-2 mb-6">
<a href="<?= BASE_URL ?>/index.php" 
   class="border rounded px-4 py-1.5 text-sm font-semibold tracking-wide transition-colors <?= !$activeCategory ? 'border-cyan-400 text-cyan-400' : 'border-slate-600 text-slate-400 hover:border-slate-400 hover:text-slate-300' ?>">
    VŠE
        </a>
        <?php foreach ($categories as $cat): ?>
<a href="<?= BASE_URL ?>/index.php?cat=<?= $cat['id'] ?>"
   class="border rounded px-4 py-1.5 text-sm font-semibold tracking-wide transition-colors"
   style="<?= (isset($activeCategory) && $activeCategory && $activeCategory['id'] == $cat['id']) 
       ? "border-color:{$cat['color']};color:{$cat['color']};text-shadow:0 0 8px {$cat['color']};" 
       : 'border-color:#4b5563;color:#94a3b8;' ?>">
    <?= htmlspecialchars($cat['name']) ?>
</a>
</a>
        <?php endforeach; ?>
    </div>

    <!-- Nadpis sekce -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="orbitron text-xl font-bold tracking-widest">
            <?php if ($activeCategory): ?>
                <span style="color: <?= $activeCategory['color'] ?>; text-shadow: 0 0 10px <?= $activeCategory['color'] ?>">
                    <?= htmlspecialchars($activeCategory['name']) ?>
                </span>
            <?php else: ?>
                <span class="neon-cyan">NOVÉ</span> <span class="text-slate-400">PŘÍSPĚVKY</span>
            <?php endif; ?>
        </h2>
        <span class="text-slate-600 text-sm orbitron"><?= count($posts) ?> příspěvků</span>
    </div>

    <!-- Příspěvky -->
    <?php if (empty($posts)): ?>
        <div class="game-card rounded-lg p-16 text-center bg-[#0d1117]">
            <p class="orbitron text-slate-600 text-sm tracking-widest">// ŽÁDNÉ PŘÍSPĚVKY //</p>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>/index.php?url=post/create" class="btn-cyber inline-block mt-4 px-6 py-2 rounded">
                    PŘIDAT PRVNÍ PŘÍSPĚVEK
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($posts as $post): ?>
                <?php 
                    $images = json_decode($post['images'] ?? '[]', true) ?: [];
                    $displayName = !empty($post['nickname']) ? $post['nickname'] : $post['username'];
                ?>
                <article class="game-card rounded-lg bg-[#0d1117] overflow-hidden">
                    <div class="flex">
                        <!-- Boční pruh kategorie -->
                        <div class="w-1 flex-shrink-0" style="background: <?= htmlspecialchars($post['category_color'] ?? '#374151') ?>;"></div>
                        
                        <div class="flex-1 p-5">
                            <div class="flex flex-wrap items-start gap-3">
                                <!-- Thumbnail -->
                                <?php if (!empty($images[0])): ?>
                                <div class="w-20 h-20 flex-shrink-0 rounded overflow-hidden border border-slate-700">
                                    <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($images[0]) ?>" 
                                         alt="cover" class="w-full h-full object-cover">
                                </div>
                                <?php endif; ?>

                                <div class="flex-1 min-w-0">
                                    <!-- Meta badges -->
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <?php if ($post['category_name']): ?>
                                        <span class="platform-badge border py-0.5" 
                                              style="border-color:<?= $post['category_color'] ?>;color:<?= $post['category_color'] ?>">
                                            <?= htmlspecialchars($post['category_name']) ?>
                                        </span>
                                        <?php endif; ?>
                                        <span class="platform-badge border border-slate-700 text-slate-500 py-0.5">
                                            <?= htmlspecialchars($post['platform']) ?>
                                        </span>
                                        <?php if ($post['game_name']): ?>
                                        <span class="text-xs text-slate-500 italic">🎮 <?= htmlspecialchars($post['game_name']) ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Titulek -->
                                    <h3 class="font-bold text-white text-lg leading-tight mb-1 hover:text-cyan-400 transition-colors">
                                        <a href="<?= BASE_URL ?>/index.php?url=post/show/<?= $post['id'] ?>">
                                            <?= htmlspecialchars($post['title']) ?>
                                        </a>
                                    </h3>

                                    <!-- Preview textu -->
                                    <p class="text-slate-500 text-sm line-clamp-2">
                                        <?= htmlspecialchars(mb_strimwidth($post['content'], 0, 160, '…')) ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Footer karty -->
                            <div class="flex items-center justify-between mt-4 pt-3 border-t border-[#1f2937]">
                                <div class="flex items-center gap-4 text-xs text-slate-600">
                                    <a href="<?= BASE_URL ?>/index.php?url=user/profile/<?= $post['created_by'] ?>" 
                                       class="hover:text-cyan-400 transition-colors font-semibold">
                                        <?= htmlspecialchars($displayName) ?>
                                    </a>
                                    <span><?= date('d.m.Y H:i', strtotime($post['created_at'])) ?></span>
                                    <span class="flex items-center gap-1">
                                        💬 <?= (int)$post['comment_count'] ?>
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="<?= BASE_URL ?>/index.php?url=post/show/<?= $post['id'] ?>" 
                                       class="btn-cyber px-3 py-1 rounded text-xs">ČÍST</a>
                                    
                                    <?php if (isset($_SESSION['user_id']) && 
                                              ($post['created_by'] === $_SESSION['user_id'] || 
                                               in_array($_SESSION['user_role'] ?? '', ['admin','moderator']))): ?>
                                        <a href="<?= BASE_URL ?>/index.php?url=post/edit/<?= $post['id'] ?>" 
                                           class="btn-cyber btn-success px-3 py-1 rounded text-xs">UPRAVIT</a>
                                        <a href="<?= BASE_URL ?>/index.php?url=post/delete/<?= $post['id'] ?>" 
                                           onclick="return confirm('Opravdu smazat příspěvek?')"
                                           class="btn-cyber btn-danger px-3 py-1 rounded text-xs">SMAZAT</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>