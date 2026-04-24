<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — ' : '' ?>FastPlay</title>
    <meta name="description" content="FastPlay — Fútbol amateur organizado para todos, en cualquier lugar.">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        fp: {
                            bg:   '#060d09',
                            card: '#0d1810',
                        }
                    },
                    backgroundImage: {
                        'grid-dark': "linear-gradient(rgba(255,255,255,0.03) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.03) 1px,transparent 1px)",
                    },
                    backgroundSize: {
                        'grid': '60px 60px',
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;0,14..32,900;1,14..32,400&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }

        .gradient-text {
            background: linear-gradient(135deg, #4ade80 0%, #16a34a 60%, #facc15 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .gradient-text-gold {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .glass {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.08);
        }
        .glass-green {
            background: rgba(22,163,74,0.08);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(22,163,74,0.2);
        }
        .glow-green  { box-shadow: 0 0 40px rgba(22,163,74,0.35); }
        .glow-sm     { box-shadow: 0 0 20px rgba(22,163,74,0.2);  }

        @keyframes fadeUp {
            from { opacity:0; transform:translateY(20px); }
            to   { opacity:1; transform:translateY(0);    }
        }
        @keyframes pulse-dot {
            0%,100% { opacity:1; transform:scale(1);   }
            50%      { opacity:.5; transform:scale(1.4); }
        }
        .fade-up    { animation: fadeUp .55s ease both; }
        .fade-up-1  { animation: fadeUp .55s  .1s ease both; }
        .fade-up-2  { animation: fadeUp .55s  .2s ease both; }
        .fade-up-3  { animation: fadeUp .55s  .3s ease both; }
        .fade-up-4  { animation: fadeUp .55s  .4s ease both; }
        .fade-up-5  { animation: fadeUp .55s  .5s ease both; }
        .pulse-dot  { animation: pulse-dot 2s ease-in-out infinite; }

        .btn-primary {
            display:inline-flex; align-items:center; gap:.5rem;
            background:#16a34a; color:#fff; font-weight:700;
            padding:.85rem 2rem; border-radius:9999px;
            transition:background .2s, transform .15s, box-shadow .2s;
        }
        .btn-primary:hover {
            background:#15803d;
            transform:translateY(-2px);
            box-shadow:0 0 32px rgba(22,163,74,.4);
        }
        .btn-ghost {
            display:inline-flex; align-items:center; gap:.5rem;
            background:rgba(255,255,255,.06); color:#fff; font-weight:600;
            padding:.85rem 2rem; border-radius:9999px; border:1px solid rgba(255,255,255,.12);
            transition:background .2s, transform .15s;
        }
        .btn-ghost:hover { background:rgba(255,255,255,.1); transform:translateY(-2px); }

        .input-dark {
            width:100%; background:rgba(255,255,255,.05); color:#fff;
            border:1px solid rgba(255,255,255,.1); border-radius:.75rem;
            padding:.85rem 1.1rem; font-size:.95rem; transition:border .2s, box-shadow .2s;
            outline:none;
        }
        .input-dark::placeholder { color: rgba(255,255,255,.3); }
        .input-dark:focus {
            border-color:#16a34a;
            box-shadow:0 0 0 3px rgba(22,163,74,.15);
        }

        .nav-link {
            color:rgba(255,255,255,.55); font-size:.875rem; font-weight:500;
            transition:color .15s; position:relative; padding-bottom:2px;
        }
        .nav-link:hover, .nav-link.active { color:#fff; }
        .nav-link.active::after {
            content:''; position:absolute; bottom:0; left:0; right:0;
            height:2px; background:#16a34a; border-radius:9999px;
        }

        /* Scrollbar */
        ::-webkit-scrollbar       { width:6px; }
        ::-webkit-scrollbar-track { background:#060d09; }
        ::-webkit-scrollbar-thumb { background:#1a3a25; border-radius:3px; }
    </style>
</head>
<body class="bg-[#060d09] text-white min-h-screen flex flex-col antialiased">
<?php
// Flash message helper
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<?php if ($flash): ?>
<div id="flash-msg" class="fixed top-20 left-1/2 -translate-x-1/2 z-[100] px-6 py-3 rounded-xl font-semibold text-sm shadow-2xl
    <?= $flash['type'] === 'success' ? 'bg-green-600 text-white' : ($flash['type'] === 'error' ? 'bg-red-600 text-white' : 'bg-yellow-500 text-black') ?>">
    <?= htmlspecialchars($flash['msg']) ?>
</div>
<script>setTimeout(()=>{ const el=document.getElementById('flash-msg'); if(el){el.style.opacity='0'; el.style.transition='opacity .4s'; setTimeout(()=>el.remove(),400);} }, 3500);</script>
<?php endif; ?>
