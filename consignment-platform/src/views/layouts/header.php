<?php
/** @var string $pageTitle */
$pageTitle = $pageTitle ?? 'Elephant Consignment';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> — Elephant Consignment</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/output.css">
</head>
<body class="min-h-screen flex flex-col">

  <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-500">
    <div class="absolute inset-0 bg-elephant-50/80 backdrop-blur-lg border-b border-white/20 shadow-[0_4px_30px_rgba(0,0,0,0.03)]"></div>
    <nav class="relative max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
      <a href="/index.php" class="flex items-center gap-3 group z-50">
        <span class="w-10 h-10 rounded-full bg-elephant-800 flex items-center justify-center shadow-lg transition-transform duration-700 group-hover:scale-110 group-hover:rotate-12">
          <span class="block w-4 h-4 rounded-full bg-elephant-50"></span>
        </span>
        <span class="font-display text-2xl text-elephant-900 tracking-tight font-semibold">Elephant</span>
      </a>
      
      <ul class="hidden md:flex items-center gap-8 text-sm font-medium text-elephant-600">
        <li><a href="/items/browse.php" class="relative hover:text-elephant-900 transition-colors after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-elephant-800 after:transition-all hover:after:w-full">Browse</a></li>
        
        <?php if (!empty($_SESSION['user_id'])): ?>
            <li><a href="/dashboard/<?= $_SESSION['role'] ?>.php" class="relative hover:text-elephant-900 transition-colors after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-elephant-800 after:transition-all hover:after:w-full">Dashboard</a></li>
            <li><a href="/auth/logout.php" class="btn-ghost px-5 py-2">Sign out</a></li>
        <?php else: ?>
            <li><a href="/auth/login.php" class="btn-breath px-6 py-2.5">Sign in</a></li>
        <?php endif; ?>
      </ul>
      
      <button id="mobile-menu-btn" class="md:hidden text-elephant-800 p-2 z-50 relative focus:outline-none" aria-label="Toggle Menu">
        <svg class="w-7 h-7 icon-menu" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        <svg class="w-7 h-7 icon-close hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </nav>
  </header>

  <!-- Full-Screen Mobile Menu Overlay (Classy Glass) -->
  <div id="mobile-menu" class="fixed inset-0 bg-elephant-50/90 backdrop-blur-xl z-40 hidden flex-col items-center justify-center gap-8 opacity-0 transition-opacity duration-500 ease-out">
    <ul class="flex flex-col items-center gap-8 text-2xl font-display font-semibold text-elephant-800">
      <li><a href="/items/browse.php" class="hover:text-sage-600 transition-colors">Browse</a></li>
      <?php if (!empty($_SESSION['user_id'])): ?>
          <li><a href="/dashboard/<?= $_SESSION['role'] ?>.php" class="hover:text-sage-600 transition-colors">Dashboard</a></li>
          <li><a href="/auth/logout.php" class="text-red-500 hover:text-red-600 transition-colors">Sign out</a></li>
      <?php else: ?>
          <li><a href="/auth/login.php" class="btn-breath px-10 py-4 text-lg">Sign in</a></li>
      <?php endif; ?>
    </ul>
  </div>

  <main class="flex-1 pt-24 min-h-screen">