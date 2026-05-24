<?php require_once '../app/views/layout/header.php'; ?>

<?php 
$platforms = ['PC','PlayStation','Xbox','Nintendo','Mobilní','Retro','Ostatní'];
?>

<div class="container mx-auto px-4 py-8 max-w-2xl">

    <div class="flex items-center justify-between mb-6">
        <h2 class="orbitron text-xl font-bold tracking-widest">
            <span class="neon-cyan">NOVÝ</span> <span class="text-slate-400">PŘÍSPĚVEK</span>
        </h2>
        <a href="<?= BASE_URL ?>/index.php" class="text-slate-600 hover:text-cyan-400 text-xs orbitron tracking-wider transition-colors">← ZPĚT</a>
    </div>

    <div class="bg-[#0d1117] border border-[#1f2937] rounded-lg p-6">
        <form action="<?= BASE_URL ?>/index.php?url=post/store" method="post" enctype="multipart/form-data">
            
            <div class="space-y-5">

                <!-- Název -->
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1.5 tracking-widest uppercase">
                        Název příspěvku <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" required maxlength="255"
                           class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-4 py-2.5 text-slate-200 text-sm transition-colors"
                           placeholder="Napiš výstižný titulek...">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Kategorie -->
                    <div>
                        <label class="block text-xs orbitron text-slate-500 mb-1.5 tracking-widest uppercase">
                            Kategorie <span class="text-rose-500">*</span>
                        </label>
                        <select name="category_id" required
                                class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-4 py-2.5 text-slate-200 text-sm transition-colors appearance-none cursor-pointer">
                            <option value="">-- Vyber kategorii --</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Platforma -->
                    <div>
                        <label class="block text-xs orbitron text-slate-500 mb-1.5 tracking-widest uppercase">Platforma</label>
                        <select name="platform"
                                class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-4 py-2.5 text-slate-200 text-sm transition-colors appearance-none cursor-pointer">
                            <?php foreach ($platforms as $p): ?>
                            <option value="<?= $p ?>"><?= $p ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Hra -->
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1.5 tracking-widest uppercase">Název hry</label>
                    <input type="text" name="game_name" maxlength="150"
                           class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-4 py-2.5 text-slate-200 text-sm transition-colors"
                           placeholder="Např: The Witcher 3, Elden Ring...">
                </div>

                <!-- Obsah -->
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1.5 tracking-widest uppercase">
                        Obsah příspěvku <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="content" rows="7" required
                              class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-4 py-2.5 text-slate-200 text-sm resize-none transition-colors"
                              placeholder="Každý názor je vítán, dodržujme ale slušné vyjadřování..."></textarea>
                </div>

                <!-- Obrázky -->
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1.5 tracking-widest uppercase">Obrázky</label>
                    <label for="images" class="flex flex-col items-center justify-center w-full h-20 border border-dashed border-[#1f2937] rounded cursor-pointer hover:border-cyan-400 transition-colors bg-[#080b0f]">
                        <span id="file-title" class="text-xs orbitron text-slate-600 tracking-widest">KLIKNI PRO VÝBĚR SOUBORŮ</span>
                        <span id="file-info" class="text-xs text-slate-700 mt-1">JPG, PNG, WebP — více najednou</span>
                        <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                    </label>
                </div>

                <!-- Submit -->
                <div class="pt-2">
                    <button type="submit" class="btn-cyber w-full py-3 rounded font-black uppercase tracking-widest">
                        PUBLIKOVAT PŘÍSPĚVEK
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
const fileInput = document.getElementById('images');
const fileTitle = document.getElementById('file-title');
const fileInfo  = document.getElementById('file-info');
fileInput.addEventListener('change', function() {
    const files = this.files;
    if (!files.length) {
        fileTitle.textContent = 'KLIKNI PRO VÝBĚR SOUBORŮ';
        fileTitle.style.color = '';
        fileInfo.textContent = 'JPG, PNG, WebP — více najednou';
    } else if (files.length === 1) {
        fileTitle.textContent = '✔ SOUBOR PŘIPRAVEN';
        fileTitle.style.color = 'var(--neon-green)';
        fileInfo.textContent = files[0].name;
    } else {
        fileTitle.textContent = '✔ SOUBORY PŘIPRAVENY';
        fileTitle.style.color = 'var(--neon-green)';
        fileInfo.textContent = 'Vybráno: ' + files.length + ' souborů';
    }
});
</script>

<?php require_once '../app/views/layout/footer.php'; ?>