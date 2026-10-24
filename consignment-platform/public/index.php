<?php
$pageTitle = 'Home';
require __DIR__ . '/../src/views/layouts/header.php';
?>

<section class="max-w-5xl mx-auto px-6 pt-24 pb-20 text-center animate-fade-in-up">
  <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/50 border border-elephant-200 backdrop-blur-md mb-8 shadow-sm">
    <span class="w-2 h-2 rounded-full bg-sage-500 animate-pulse"></span>
    <span class="text-xs font-bold tracking-wider text-elephant-600 uppercase">A slower consignment marketplace</span>
  </div>
  
  <h1 class="text-6xl md:text-8xl leading-[1.05] max-w-4xl mx-auto text-elephant-900 font-display">
    Give good things <em class="italic text-sage-600 relative inline-block">
      another breath.
      <svg class="absolute -bottom-2 left-0 w-full h-3 text-sage-300/50" viewBox="0 0 100 10" preserveAspectRatio="none"><path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="2" fill="none"/></svg>
    </em>
  </h1>
  
  <p class="mt-8 max-w-xl mx-auto text-lg text-elephant-500 font-light leading-relaxed">
    We take the rush out of reselling and logistics. Submit your pieces, track your goods across the globe, and connect with a community that values the journey.
  </p>

  <div class="mt-12 flex flex-wrap items-center justify-center gap-4 animate-fade-in-up" style="animation-delay: 0.2s;">
    <a href="/dashboard/consignor.php" class="btn-breath">Go to Dashboard</a>
    <a href="/items/browse.php" class="btn-ghost">Browse the collection</a>
  </div>

  <div class="mt-20 max-w-xl mx-auto animate-fade-in-up" style="animation-delay: 0.4s;">
    <div class="card-breath p-2 relative overflow-hidden group">
      <div class="absolute -inset-24 bg-gradient-to-r from-sage-200/0 via-sage-200/30 to-sage-200/0 opacity-0 group-hover:opacity-100 blur-3xl transition-opacity duration-1000 -z-10"></div>
      
      <form action="/tracking/result.php" method="GET" class="flex flex-col sm:flex-row gap-2 relative">
        <div class="relative flex-1">
          <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-elephant-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
          </div>
          <input type="text" name="code" required placeholder="Enter tracking code (e.g. ELE-A1B2C3D4)" class="input-breath pl-12 py-4 text-base uppercase placeholder:normal-case w-full">
        </div>
        <button type="submit" class="btn-sage py-4 px-8 whitespace-nowrap shadow-lg">Track Package</button>
      </form>
    </div>
  </div>
</section>

<section class="max-w-6xl mx-auto px-6 py-20 grid md:grid-cols-3 gap-6 animate-fade-in-up" style="animation-delay: 0.6s;">
  <div class="card-breath p-8 group">
    <div class="w-12 h-12 rounded-2xl bg-sage-100 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500">
      <svg class="w-6 h-6 text-sage-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
    </div>
    <h2 class="text-2xl mb-3 text-elephant-900">Consign</h2>
    <p class="text-elephant-500 text-sm leading-relaxed">Submit your pieces with photos and details. No rush — we review everything slowly and carefully.</p>
  </div>
  
  <div class="card-breath p-8 group">
    <div class="w-12 h-12 rounded-2xl bg-amber-soft/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500">
      <svg class="w-6 h-6 text-amber-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    </div>
    <h2 class="text-2xl mb-3 text-elephant-900">Track</h2>
    <p class="text-elephant-500 text-sm leading-relaxed">Follow your goods across the globe. View real-time maps, port updates, and photos of your package.</p>
  </div>
  
  <div class="card-breath p-8 group">
    <div class="w-12 h-12 rounded-2xl bg-elephant-800/5 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500">
      <svg class="w-6 h-6 text-elephant-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
    </div>
    <h2 class="text-2xl mb-3 text-elephant-900">Secure</h2>
    <p class="text-elephant-500 text-sm leading-relaxed">Get alerts at every step. If something needs your attention, we'll let you know instantly.</p>
  </div>
</section>

<?php require __DIR__ . '/../src/views/layouts/footer.php'; ?>