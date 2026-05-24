<?php require_once '../app/views/layout/header.php'; ?>

<div class="flex items-center justify-center py-6 px-4">
<div class="w-full max-w-lg">

    <div class="text-center mb-6">
        <div class="orbitron text-2xl font-black mb-1">
            <span class="neon-cyan">VYTVOŘIT</span> <span class="text-slate-400">ÚČET</span>
        </div>
        <p class="text-slate-600 text-xs">Vytvoř si účet a přidej se k herní komunitě</p>
    </div>

    <div class="bg-[#0d1117] border border-[#1f2937] rounded-lg p-5">
        <form action="<?= BASE_URL ?>/index.php?url=auth/storeUser" method="post">
            
            <!-- Přihlašovací údaje -->
            <p class="text-xs orbitron text-cyan-400 tracking-widest uppercase border-b border-[#1f2937] pb-2 mb-4">
                Přihlašovací údaje
            </p>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1 tracking-widest uppercase">
                        Uživatelské jméno <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="username" required maxlength="50"
                           class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-3 py-2 text-slate-200 text-sm transition-colors"
                           >
                </div>
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1 tracking-widest uppercase">
                        E-mail <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" required
                           class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-3 py-2 text-slate-200 text-sm transition-colors"
                           >
                </div>
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1 tracking-widest uppercase">
                        Heslo <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="reg-password" required minlength="6"
                               class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-3 py-2 pr-10 text-slate-200 text-sm transition-colors"
                               placeholder="min. 6 znaků">
                        <button type="button" onclick="togglePassword('reg-password', this)"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-600 hover:text-cyan-400 transition-colors text-xs">👁</button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1 tracking-widest uppercase">
                        Potvrzení hesla <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password_confirm" id="reg-password2" required
                               class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-3 py-2 pr-10 text-slate-200 text-sm transition-colors"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePassword('reg-password2', this)"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-600 hover:text-cyan-400 transition-colors text-xs">👁</button>
                    </div>
                </div>
            </div>

            <!-- Osobní údaje -->
            <p class="text-xs orbitron text-slate-500 tracking-widest uppercase border-b border-[#1f2937] pb-2 mb-4">
                Osobní údaje <span class="text-slate-700 normal-case font-sans">(volitelné)</span>
            </p>

            <div class="grid grid-cols-3 gap-4 mb-5">
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1 tracking-widest uppercase">Jméno</label>
                    <input type="text" name="first_name"
                           class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-3 py-2 text-slate-200 text-sm transition-colors">
                </div>
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1 tracking-widest uppercase">Příjmení</label>
                    <input type="text" name="last_name"
                           class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-3 py-2 text-slate-200 text-sm transition-colors">
                </div>
                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1 tracking-widest uppercase">Přezdívka</label>
                    <input type="text" name="nickname" placeholder="Jak tě oslovit?"
                           class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-3 py-2 text-slate-200 text-sm transition-colors placeholder-slate-800">
                </div>
            </div>

            <button type="submit" class="btn-cyber w-full py-3 rounded font-black uppercase tracking-widest">
                VYTVOŘIT ÚČET
            </button>

            <p class="text-center text-slate-700 text-sm mt-4">
                Už máš účet?
                <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="text-cyan-400 hover:text-cyan-300 transition-colors ml-1">Přihlas se</a>
            </p>
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