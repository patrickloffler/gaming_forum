<?php require_once '../app/views/layout/header.php'; ?>

<?php 
$platforms = ['PC','PlayStation','Xbox','Nintendo','Mobilní','Retro','Ostatní'];
$existingImages = json_decode($post['images'] ?? '[]', true) ?: [];
?>

<div class="container mx-auto px-4 py-8 max-w-2xl">

    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="text-xs orbitron text-slate-600 tracking-widest mb-1">
                ID: <span class="text-slate-500">#<?= $post['id'] ?></span>
                · Vytvořeno: <span class="text-slate-500"><?= date('d.m.Y', strtotime($post['created_at'])) ?></span>
            </div>
            <h2 class="orbitron text-xl font-bold tracking-widest">
                <span class="neon-cyan">UPRAVIT</span> <span class="text-slate-400">PŘÍSPĚVEK</span>
            </h2>
        </div>
        <a href="<?= BASE_URL ?>/index.php?url=post/show/<?= $post['id'] ?>" 
           class="text-slate-600 hover:text-cyan-400 text-xs orbitron tracking-wider transition-colors">← ZPĚT</a>
    </div>

    <div class="bg-[#0d1117] border border-[#1f2937] rounded-lg p-6">
        <form action="<?= BASE_URL ?>/index.php?url=post/update/<?= $post['id'] ?>" method="post" enctype="multipart/form-data">
            
            <div class="space-y-5">

                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1.5 tracking-widest uppercase">
                        Název příspěvku <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" required maxlength="255"
                           value="<?= htmlspecialchars($post['title']) ?>"
                           class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-4 py-2.5 text-slate-200 text-sm transition-colors">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs orbitron text-slate-500 mb-1.5 tracking-widest uppercase">
                            Kategorie <span class="text-rose-500">*</span>
                        </label>
                        <select name="category_id" required
                                class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-4 py-2.5 text-slate-200 text-sm transition-colors appearance-none cursor-pointer">
                            <option value="">-- Vyber kategorii --</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $post['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs orbitron text-slate-500 mb-1.5 tracking-widest uppercase">Platforma</label>
                        <select name="platform"
                                class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-4 py-2.5 text-slate-200 text-sm transition-colors appearance-none cursor-pointer">
                            <?php foreach ($platforms as $p): ?>
                            <option value="<?= $p ?>" <?= $post['platform'] === $p ? 'selected' : '' ?>><?= $p ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1.5 tracking-widest uppercase">Název hry</label>
                    <input type="text" name="game_name" maxlength="150"
                           value="<?= htmlspecialchars($post['game_name'] ?? '') ?>"
                           class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-4 py-2.5 text-slate-200 text-sm transition-colors">
                </div>

                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1.5 tracking-widest uppercase">
                        Obsah <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="content" rows="7" required
                              class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-4 py-2.5 text-slate-200 text-sm resize-none transition-colors"><?= htmlspecialchars($post['content']) ?></textarea>
                </div>

                <!-- Stávající obrázky -->
                <?php if (!empty($existingImages)): ?>
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-2 tracking-widest uppercase">Stávající obrázky</label>
                    <div class="flex gap-2 flex-wrap">
                        <?php foreach ($existingImages as $img): ?>
                        <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($img) ?>" 
                             alt="" class="h-16 w-auto rounded border border-slate-700 object-cover">
                        <?php endforeach; ?>
                    </div>
                    <p class="text-xs text-slate-700 mt-1">Nahráním nových obrázků níže se stávající nahradí.</p>
                </div>
                <?php endif; ?>

                <!-- Nové obrázky -->
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1.5 tracking-widest uppercase">
                        <?= empty($existingImages) ? 'Obrázky' : 'Nové obrázky (nahradí stávající)' ?>
                    </label>
                    <label for="images" class="flex flex-col items-center justify-center w-full h-20 border border-dashed border-[#1f2937] rounded cursor-pointer hover:border-cyan-400 transition-colors bg-[#080b0f]">
                        <span id="file-title" class="text-xs orbitron text-slate-600 tracking-widest">KLIKNI PRO VÝBĚR SOUBORŮ</span>
                        <span id="file-info" class="text-xs text-slate-700 mt-1">JPG, PNG, WebP</span>
                        <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-cyber btn-success w-full py-3 rounded font-black uppercase tracking-widest">
                        ULOŽIT ZMĚNY
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
const fi = document.getElementById('images');
const ft = document.getElementById('file-title');
const info = document.getElementById('file-info');
fi.addEventListener('change', function() {
    const f = this.files;
    if (!f.length) { ft.textContent = 'KLIKNI PRO VÝBĚR SOUBORŮ'; ft.style.color=''; info.textContent='JPG, PNG, WebP'; }
    else if (f.length===1) { ft.textContent='✔ SOUBOR PŘIPRAVEN'; ft.style.color='var(--neon-green)'; info.textContent=f[0].name; }
    else { ft.textContent='✔ '+f.length+' SOUBORŮ'; ft.style.color='var(--neon-green)'; info.textContent='Vybráno souborů: '+f.length; }
});
</script>

<?php require_once '../app/views/layout/footer.php'; ?>