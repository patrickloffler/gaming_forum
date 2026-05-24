<?php require_once '../app/views/layout/header.php'; ?>

<?php $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'; ?>

<div class="flex items-center justify-center py-6 px-4">
<div class="w-full max-w-lg">

    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="text-xs orbitron text-slate-600 tracking-widest mb-1">
                ID: <span class="text-slate-500">#<?= $user['id'] ?></span>
            </div>
            <h2 class="orbitron text-xl font-bold tracking-widest">
                <span class="neon-cyan">EDITACE</span> <span class="text-slate-400">PROFILU</span>
            </h2>
        </div>
        <a href="<?= BASE_URL ?>/index.php?url=user/profile/<?= $user['id'] ?>" 
           class="text-slate-600 hover:text-cyan-400 text-xs orbitron tracking-wider transition-colors">← ZPĚT</a>
    </div>

    <div class="bg-[#0d1117] border border-[#1f2937] rounded-lg p-5">
        <form action="<?= BASE_URL ?>/index.php?url=user/update/<?= $user['id'] ?>" method="post" enctype="multipart/form-data">
            
            <!-- Základní informace -->
            <p class="text-xs orbitron text-cyan-400 tracking-widest uppercase border-b border-[#1f2937] pb-2 mb-4">
                Základní údaje
            </p>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1 tracking-widest uppercase">
                        Uživatelské jméno <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="username" required value="<?= htmlspecialchars($user['username']) ?>"
                           class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-3 py-2 text-slate-200 text-sm transition-colors">
                </div>
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1 tracking-widest uppercase">
                        E-mail <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>"
                           class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-3 py-2 text-slate-200 text-sm transition-colors">
                </div>
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1 tracking-widest uppercase">Jméno</label>
                    <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>"
                           class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-3 py-2 text-slate-200 text-sm transition-colors">
                </div>
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1 tracking-widest uppercase">Příjmení</label>
                    <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>"
                           class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-3 py-2 text-slate-200 text-sm transition-colors">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs orbitron text-slate-500 mb-1 tracking-widest uppercase">Přezdívka</label>
                <input type="text" name="nickname" value="<?= htmlspecialchars($user['nickname'] ?? '') ?>"
                       class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-3 py-2 text-slate-200 text-sm transition-colors">
            </div>

            <div class="mb-4">
                <label class="block text-xs orbitron text-slate-500 mb-1 tracking-widest uppercase">Bio</label>
                <textarea name="bio" rows="3"
                          class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-3 py-2 text-slate-200 text-sm resize-none transition-colors"
                          placeholder="Pár slov o sobě..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
            </div>

            <!-- Avatar -->
            <div class="mb-5">
                <label class="block text-xs orbitron text-slate-500 mb-1.5 tracking-widest uppercase">Profilová fotka</label>
                <?php if ($user['avatar']): ?>
<div class="flex items-center gap-3 mb-2">
    <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($user['avatar']) ?>" class="w-10 h-10 rounded border border-slate-700 object-cover">
    <span class="text-xs text-slate-600">Aktuální avatar</span>
    <a href="<?= BASE_URL ?>/index.php?url=user/removeAvatar/<?= $user['id'] ?>"
       onclick="return confirm('Opravdu odstranit avatar?')"
       class="btn-cyber btn-danger px-3 py-1 rounded text-xs">ODSTRANIT</a>
</div>
                <?php endif; ?>
                <label for="avatar" class="flex items-center gap-3 w-full border border-dashed border-[#1f2937] rounded px-4 py-3 cursor-pointer hover:border-cyan-400 transition-colors bg-[#080b0f]">
                    <span class="text-xs orbitron text-slate-600 tracking-widest">NAHRÁT NOVÝ AVATAR</span>
                    <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden">
                </label>
            </div>

            <!-- Změna hesla -->
            <p class="text-xs orbitron text-slate-500 tracking-widest uppercase border-b border-[#1f2937] pb-2 mb-4">
                Změna hesla <span class="text-slate-700 normal-case font-sans">(nechat prázdné = bez změny)</span>
            </p>
            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1 tracking-widest uppercase">Nové heslo</label>
                    <div class="relative">
                        <input type="password" name="new_password" id="new-pass" minlength="6"
                               class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-3 py-2 pr-9 text-slate-200 text-sm transition-colors"
                               placeholder="min. 6 znaků">
                        <button type="button" onclick="togglePassword('new-pass', this)"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-600 hover:text-cyan-400 transition-colors text-xs">👁</button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1 tracking-widest uppercase">Potvrdit heslo</label>
                    <div class="relative">
                        <input type="password" name="confirm_password" id="confirm-pass"
                               class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-3 py-2 pr-9 text-slate-200 text-sm transition-colors"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePassword('confirm-pass', this)"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-600 hover:text-cyan-400 transition-colors text-xs">👁</button>
                    </div>
                </div>
            </div>

            <!-- Admin: změna role -->
            <?php if ($isAdmin): ?>
            <p class="text-xs orbitron text-yellow-500 tracking-widest uppercase border-b border-[#1f2937] pb-2 mb-4">
                ⚙ Admin: Správa role
            </p>
            <div class="mb-5">
                <label class="block text-xs orbitron text-slate-500 mb-1 tracking-widest uppercase">Role uživatele</label>
                <select name="role" class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-3 py-2 text-slate-200 text-sm transition-colors appearance-none">
                    <option value="user"      <?= $user['role']==='user'      ? 'selected':'' ?>>🎮 Člen</option>
                    <option value="moderator" <?= $user['role']==='moderator' ? 'selected':'' ?>>🛡 Moderátor</option>
                    <option value="admin"     <?= $user['role']==='admin'     ? 'selected':'' ?>>⭐ Admin</option>
                </select>
            </div>
            <?php endif; ?>

            <button type="submit" class="btn-cyber btn-success w-full py-3 rounded font-black uppercase tracking-widest">
                ULOŽIT ZMĚNY
            </button>
        </form>
    </div>
</div>
</div>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') { input.type = 'text'; btn.style.color = 'var(--neon-cyan)'; }
    else { input.type = 'password'; btn.style.color = ''; }
}
</script>

<?php require_once '../app/views/layout/footer.php'; ?>