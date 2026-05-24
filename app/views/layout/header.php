<!DOCTYPE html>
<html lang="cs" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;600;700;900&family=Rajdhani:wght@300;400;600;700&display=swap" rel="stylesheet">
    <title>GameForum</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' fill='%23080b0f' stroke='%2300f5ff' stroke-width='2'/><text x='50%25' y='50%25' dominant-baseline='central' text-anchor='middle' fill='%2300f5ff' font-size='14' font-weight='900' font-family='monospace'>GF</text></svg>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body class="text-slate-300 min-h-screen flex flex-col relative z-10">

<header class="bg-[#080b0f] border-b border-[#1f2937] shadow-2xl sticky top-0 z-50" style="box-shadow: 0 2px 20px rgba(0,245,255,0.1);">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
        
        <!-- Logo -->
        <a href="<?= BASE_URL ?>/index.php" class="flex items-center gap-3 group">
            <div class="w-8 h-8 border border-cyan-400 flex items-center justify-center" style="box-shadow: 0 0 12px rgba(0,245,255,0.4);">
                <span class="text-cyan-400 font-black orbitron text-xs">GF</span>
            </div>
            <span class="orbitron font-black text-lg tracking-widest hidden sm:block">
                <span class="neon-cyan">GAMEFORUM<span class="text-cyan-400 font-black orbitron text-xs"> - Herní portál pro nadšence</span>
            </span>
        </a>

        <!-- Nav -->
        <nav>
            <ul class="flex items-center gap-4 text-sm">
                <li><a href="<?= BASE_URL ?>/index.php" class="nav-link text-slate-300 hover:text-cyan-400 font-semibold tracking-wide uppercase text-xs">Fórum</a></li>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li>
                        <a href="<?= BASE_URL ?>/index.php?url=post/create" 
                           class="btn-cyber px-4 py-2 rounded font-bold uppercase tracking-widest text-xs">
                            + Nový příspěvek
                        </a>
                    </li>
                    <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
                    <li>
                        <a href="<?= BASE_URL ?>/index.php?url=user/list" class="nav-link text-yellow-400 hover:text-yellow-300 font-semibold text-xs uppercase tracking-wide" style="text-shadow: 0 0 6px #ffd700;">
                             Admin
                        </a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="<?= BASE_URL ?>/index.php?url=user/profile" class="nav-link text-slate-300 hover:text-cyan-400 font-semibold text-xs uppercase tracking-wide">
                            👤 <?= htmlspecialchars($_SESSION['user_name']) ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>/index.php?url=auth/logout" class="text-rose-400 hover:text-rose-300 font-semibold text-xs uppercase tracking-wide transition-colors">
                            Odhlásit
                        </a>
                    </li>
                <?php else: ?>
                    <li><a href="<?= BASE_URL ?>/index.php?url=auth/login" class="nav-link text-slate-300 hover:text-cyan-400 font-semibold text-xs uppercase tracking-wide">Přihlásit</a></li>
                    <li>
                        <a href="<?= BASE_URL ?>/index.php?url=auth/register" 
                           class="btn-cyber px-4 py-2 rounded font-bold uppercase tracking-widest text-xs">
                            Registrace
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<!-- Flash messages -->
<?php if (!empty($_SESSION['messages'])): ?>
<div class="fixed top-20 right-4 z-50 space-y-2 max-w-sm w-full" id="notifications">
    <?php foreach ($_SESSION['messages'] as $type => $msgs): ?>
        <?php
            $styles = [
                'success' => 'border-green-400 bg-[#0d1a0f] text-green-400',
                'error'   => 'border-rose-500 bg-[#1a0d0f] text-rose-400',
                'notice'  => 'border-yellow-400 bg-[#1a180d] text-yellow-400',
            ];
            $style = $styles[$type] ?? 'border-slate-500 bg-slate-900 text-slate-300';
            $icons = ['success' => '✔', 'error' => '✖', 'notice' => '⚠'];
            $icon = $icons[$type] ?? 'ℹ';
        ?>
        <?php foreach ($msgs as $msg): ?>
        <div class="notification border-l-4 <?= $style ?> px-4 py-3 rounded-r shadow-2xl flex items-start gap-3 relative" 
             style="box-shadow: 0 4px 20px rgba(0,0,0,0.5);">
            <span class="text-sm font-black mt-0.5"><?= $icon ?></span>
            <p class="text-sm font-semibold flex-1"><?= htmlspecialchars($msg) ?></p>
            <button onclick="this.closest('.notification').remove()" class="text-current opacity-50 hover:opacity-100 text-lg leading-none ml-2">×</button>
        </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
</div>
<script>
    // Auto-dismiss po 5 sekundách
    setTimeout(() => {
        document.querySelectorAll('.notification').forEach(n => {
            n.style.transition = 'all 0.4s ease';
            n.style.opacity = '0';
            n.style.transform = 'translateX(100%)';
            setTimeout(() => n.remove(), 400);
        });
    }, 5000);
</script>
<?php unset($_SESSION['messages']); ?>
<?php endif; ?>

<main class="flex-1 relative z-10">