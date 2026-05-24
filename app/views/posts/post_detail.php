<?php require_once '../app/views/layout/header.php'; ?>

<?php 
    $images = json_decode($post['images'] ?? '[]', true) ?: [];
    $displayName = !empty($post['nickname']) ? $post['nickname'] : $post['username'];
    $isOwner = isset($_SESSION['user_id']) && $_SESSION['user_id'] === $post['created_by'];
    $isMod   = in_array($_SESSION['user_role'] ?? '', ['admin','moderator']);
?>

<div class="container mx-auto px-4 py-8 max-w-4xl">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 mb-6 text-xs text-slate-600 orbitron tracking-wider">
        <a href="<?= BASE_URL ?>/index.php" class="hover:text-cyan-400 transition-colors">FÓRUM</a>
        <span>›</span>
        <?php if ($post['category_name']): ?>
        <span style="color: <?= htmlspecialchars($post['category_color']) ?>"><?= htmlspecialchars($post['category_name']) ?></span>
        <span>›</span>
        <?php endif; ?>
        <span class="text-slate-500"><?= htmlspecialchars(mb_strimwidth($post['title'], 0, 50, '…')) ?></span>
    </div>

    <!-- Příspěvek -->
    <article class="bg-[#0d1117] border border-[#1f2937] rounded-lg overflow-hidden mb-8"
             style="box-shadow: 0 0 30px rgba(0,0,0,0.5);">
        
        <!-- Header příspěvku -->
        <div class="p-6 border-b border-[#1f2937]">
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <?php if ($post['category_name']): ?>
                <span class="platform-badge border py-0.5" style="border-color:<?= $post['category_color'] ?>;color:<?= $post['category_color'] ?>">
                    <?= htmlspecialchars($post['category_name']) ?>
                </span>
                <?php endif; ?>
                <span class="platform-badge border border-slate-700 text-slate-500 py-0.5"><?= htmlspecialchars($post['platform']) ?></span>
                <?php if ($post['game_name']): ?>
                <span class="text-sm text-slate-500 italic">🎮 <?= htmlspecialchars($post['game_name']) ?></span>
                <?php endif; ?>
            </div>

            <h1 class="orbitron text-2xl font-bold text-white mb-4 leading-tight">
                <?= htmlspecialchars($post['title']) ?>
            </h1>

            <!-- Autor info -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <?php if ($post['avatar']): ?>
                        <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($post['avatar']) ?>" 
                             class="w-10 h-10 rounded border border-slate-700 object-cover" alt="avatar">
                    <?php else: ?>
                        <div class="w-10 h-10 rounded border border-slate-700 bg-[#1f2937] flex items-center justify-center text-cyan-400 orbitron font-bold text-sm">
                            <?= strtoupper(substr($displayName, 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <a href="<?= BASE_URL ?>/index.php?url=user/profile/<?= $post['created_by'] ?>" 
                           class="font-bold text-slate-200 hover:text-cyan-400 transition-colors text-sm">
                            <?= htmlspecialchars($displayName) ?>
                        </a>
                        <p class="text-xs text-slate-600">
                            <?php 
                                $roleLabels = ['admin' => 'Admin', 'moderator' => 'Moderátor', 'user' => 'Člen'];
                                echo $roleLabels[$post['author_role']] ?? 'Člen';
                            ?>
                            · <?= date('d.m.Y H:i', strtotime($post['created_at'])) ?>
                        </p>
                    </div>
                </div>
                
                <?php if ($isOwner || $isMod): ?>
                <div class="flex gap-2">
                    <a href="<?= BASE_URL ?>/index.php?url=post/edit/<?= $post['id'] ?>" 
                       class="btn-cyber btn-success px-4 py-2 rounded text-xs">UPRAVIT</a>
                    <a href="<?= BASE_URL ?>/index.php?url=post/delete/<?= $post['id'] ?>" 
                       onclick="return confirm('Smazat příspěvek?')"
                       class="btn-cyber btn-danger px-4 py-2 rounded text-xs">SMAZAT</a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Obrázky -->
        <?php if (!empty($images)): ?>
        <div class="border-b border-[#1f2937]">
            <div class="flex gap-2 p-4 overflow-x-auto">
                <?php foreach ($images as $img): ?>
                <a href="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($img) ?>" target="_blank" 
                   class="flex-shrink-0">
                    <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($img) ?>" 
                         alt="obrázek" 
                         class="h-48 w-auto rounded border border-slate-700 hover:border-cyan-400 transition-colors object-cover">
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Obsah -->
        <div class="p-6">
            <div class="text-slate-300 leading-relaxed text-base whitespace-pre-wrap">
                <?= nl2br(htmlspecialchars($post['content'])) ?>
            </div>
        </div>
    </article>

    <!-- ===== KOMENTÁŘE ===== -->
    <section id="comments">
        <h2 class="orbitron text-lg font-bold mb-4 tracking-widest">
            <span class="neon-cyan">KOMENTÁŘE</span> 
            <span class="text-slate-600 text-sm">(<?= count($comments) ?>)</span>
        </h2>

        <!-- Formulář pro přidání komentáře -->
        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="bg-[#0d1117] border border-[#1f2937] rounded-lg p-5 mb-6">
            <form action="<?= BASE_URL ?>/index.php?url=comment/store/<?= $post['id'] ?>" method="post">
                <label class="block text-xs orbitron text-slate-500 mb-2 tracking-widest uppercase">Přidat komentář</label>
                <textarea name="content" rows="3" required placeholder="Napiš svůj komentář..."
                          class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-4 py-3 text-slate-200 text-sm resize-none placeholder-slate-700 transition-colors"></textarea>
                <div class="flex justify-end mt-3">
                    <button type="submit" class="btn-cyber px-6 py-2 rounded font-bold">ODESLAT</button>
                </div>
            </form>
        </div>
        <?php else: ?>
        <div class="bg-[#0d1117] border border-[#1f2937] rounded-lg p-5 mb-6 text-center">
            <p class="text-slate-600 text-sm">
                <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="text-cyan-400 hover:underline">Přihlas se</a>
                pro přidání komentáře.
            </p>
        </div>
        <?php endif; ?>

        <!-- Seznam komentářů -->
        <?php if (empty($comments)): ?>
            <p class="text-slate-700 text-sm orbitron tracking-widest text-center py-8">// ZATÍM ŽÁDNÉ KOMENTÁŘE //</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($comments as $comment): ?>
                <?php
                    $cName = !empty($comment['nickname']) ? $comment['nickname'] : $comment['username'];
                    $canEditComment  = isset($_SESSION['user_id']) && 
                                       ($comment['user_id'] === $_SESSION['user_id'] || 
                                        in_array($_SESSION['user_role'] ?? '', ['admin','moderator']));
                ?>
                <div class="bg-[#0d1117] border border-[#1f2937] rounded-lg p-4 hover:border-slate-600 transition-colors">
                    <div class="flex items-start gap-3">
                        <!-- Avatar -->
                        <?php if ($comment['avatar']): ?>
                            <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($comment['avatar']) ?>" 
                                 class="w-8 h-8 rounded border border-slate-700 object-cover flex-shrink-0" alt="">
                        <?php else: ?>
                            <div class="w-8 h-8 rounded border border-slate-700 bg-[#1f2937] flex items-center justify-center text-cyan-400 orbitron font-bold text-xs flex-shrink-0">
                                <?= strtoupper(substr($cName, 0, 1)) ?>
                            </div>
                        <?php endif; ?>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <a href="<?= BASE_URL ?>/index.php?url=user/profile/<?= $comment['user_id'] ?>"
                                   class="font-bold text-sm text-slate-200 hover:text-cyan-400 transition-colors">
                                    <?= htmlspecialchars($cName) ?> 
                                </a>
                                <span class="text-xs text-slate-600"><?= date('d.m.Y H:i', strtotime($comment['created_at'])) ?></span>
                                <?php if ($comment['updated_at'] !== $comment['created_at']): ?>
                                    <span class="text-xs text-slate-700 italic">(upraveno)</span>
                                <?php endif; ?>
                            </div>
                            <p class="text-slate-400 text-sm leading-relaxed whitespace-pre-wrap"><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                        </div>

                        <!-- Akce komentáře -->
                        <?php if ($canEditComment): ?>
                        <div class="flex gap-2 flex-shrink-0">
                            <a href="<?= BASE_URL ?>/index.php?url=comment/edit/<?= $comment['id'] ?>" 
                               class="btn-cyber btn-success px-2 py-1 rounded text-xs">✎</a>
                            <a href="<?= BASE_URL ?>/index.php?url=comment/delete/<?= $comment['id'] ?>"
                               onclick="return confirm('Smazat komentář?')"
                               class="btn-cyber btn-danger px-2 py-1 rounded text-xs">✕</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>