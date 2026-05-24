<?php require_once '../app/views/layout/header.php'; ?>

<div class="flex items-center justify-center min-h-[calc(100vh-12rem)] px-4">
<div class="w-full max-w-sm">

    <div class="text-center mb-8">
        <div class="orbitron text-3xl font-black mb-2">
            <span class="neon-cyan">PŘIHLÁSIT</span><span class="text-slate-400"> SE</span>
        </div>
        <p class="text-slate-600 text-sm">Přihlas se do herního fóra</p>
    </div>

    <div class="bg-[#0d1117] border border-[#1f2937] rounded-lg p-6">
        <form action="<?= BASE_URL ?>/index.php?url=auth/authenticate" method="post">
            <div class="space-y-4">

                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1.5 tracking-widest uppercase">E-mail</label>
                    <input type="email" name="email" required autofocus
                           class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-4 py-2.5 text-slate-200 text-sm transition-colors"
                           placeholder="tvuj@email.cz">
                </div>

                <div>
                    <label class="block text-xs orbitron text-slate-500 mb-1.5 tracking-widest uppercase">Heslo</label>
                    <div class="relative">
                        <input type="password" name="password" id="login-password" required
                               class="w-full bg-[#080b0f] border border-[#1f2937] rounded px-4 py-2.5 pr-12 text-slate-200 text-sm transition-colors"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePassword('login-password', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-600 hover:text-cyan-400 transition-colors text-sm select-none">
                            👁
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-cyber w-full py-3 rounded font-black uppercase tracking-widest">
                        PŘIHLÁSIT SE
                    </button>
                </div>

                <p class="text-center text-slate-700 text-sm border-t border-[#1f2937] pt-4">
                    Nemáš účet?
                    <a href="<?= BASE_URL ?>/index.php?url=auth/register" class="text-cyan-400 hover:text-cyan-300 transition-colors ml-1">Registruj se</a>
                </p>
            </div>
        </form>
    </div>
</div>
</div>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
        btn.style.color = 'var(--neon-cyan)';
    } else {
        input.type = 'password';
        btn.style.color = '';
    }
}
</script>

<?php require_once '../app/views/layout/footer.php'; ?>