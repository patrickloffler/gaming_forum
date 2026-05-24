<?php require_once '../app/views/layout/header.php'; ?>

<div class="container mx-auto px-4 py-8 max-w-xl">

    <div class="flex items-center justify-between mb-6">
        <h2 class="orbitron text-xl font-bold tracking-widest">
            <span class="neon-cyan">UPRAVIT</span> <span class="text-slate-400">KOMENTÁŘ</span>
        </h2>
        <a href="<?= BASE_URL ?>/index.php?url=post/show/<?= $comment['post_id'] ?>#comments" 
           class="text-slate-600 hover:text-cyan-400 text-xs orbitron tracking-wider transition-colors">← ZPĚT</a>
    </div>

    <div class="bg-[#0d1117] border border-[#1f2937] rounded-lg p-6">
        <div class="text-xs orbitron text-slate-600 tracking-widest mb-4">
            ID: <span class="text-slate-500">#<?= $comment['id'] ?></span>
            · <?= date('d.m.Y H:i', strtotime($comment['created_at'])) ?>
        </div>
        
        <form action="<?= BASE_URL ?>/index.php?url=comment/update/<?= $comment['id'] ?>" method="post">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1.5 tracking-widest uppercase">
                        Obsah komentáře <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="content" rows="5" required
                              class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-4 py-2.5 text-slate-200 text-sm resize-none transition-colors"><?= htmlspecialchars($comment['content']) ?></textarea>
                </div>
                <button type="submit" class="btn-cyber btn-success w-full py-3 rounded font-black uppercase tracking-widest">
                    ULOŽIT KOMENTÁŘ
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>