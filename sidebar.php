<!-- ==============================
     CORE SYSTEM — Sidebar Navigation
     ============================== -->
<style>
  :root {
    --sb-bg:        rgba(5, 5, 20, 0.97);
    --sb-border:    rgba(0, 210, 255, 0.25);
    --sb-w:         270px;
    --sb-primary:   #00d2ff;
    --sb-secondary: #9d50bb;
    --sb-accent:    #3aedff;
    --sb-text:      rgba(224, 224, 224, 0.85);
    --sb-hover-bg:  rgba(0, 210, 255, 0.08);
    --sb-active-bg: rgba(0, 210, 255, 0.14);
    --sb-glow:      0 0 18px rgba(0, 210, 255, 0.35);
    --sb-speed:     0.38s;
    --sb-ease:      cubic-bezier(0.4, 0, 0.2, 1);
  }

  #sb-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.55);
    backdrop-filter: blur(3px);
    z-index: 1100;
    opacity: 0;
    transition: opacity var(--sb-speed) var(--sb-ease);
  }
  #sb-overlay.show { display: block; opacity: 1; }

  #sidebar {
    position: fixed;
    top: 0; left: 0;
    height: 100%;
    width: var(--sb-w);
    background: var(--sb-bg);
    border-right: 1px solid var(--sb-border);
    box-shadow: 4px 0 40px rgba(0, 210, 255, 0.08);
    z-index: 1200;
    display: flex;
    flex-direction: column;
    transform: translateX(calc(-1 * var(--sb-w)));
    transition: transform var(--sb-speed) var(--sb-ease);
    overflow: hidden;
  }
  #sidebar.open { transform: translateX(0); }

  #sidebar::before {
    content: '';
    position: absolute;
    inset: 0;
    background: repeating-linear-gradient(
      0deg,
      transparent, transparent 3px,
      rgba(0, 210, 255, 0.012) 3px, rgba(0, 210, 255, 0.012) 4px
    );
    pointer-events: none;
    z-index: 0;
  }

  .sb-header {
    position: relative; z-index: 1;
    padding: 28px 24px 20px;
    border-bottom: 1px solid var(--sb-border);
    flex-shrink: 0;
  }

  .sb-logo-row {
    display: flex; align-items: center; gap: 12px; margin-bottom: 6px;
  }

  .sb-logo-icon {
    width: 36px; height: 36px;
    border: 1.5px solid var(--sb-primary); border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    background: rgba(0,210,255,0.07);
    box-shadow: var(--sb-glow);
    flex-shrink: 0;
  }
  .sb-logo-icon svg { width: 18px; height: 18px; }

  .sb-logo-text {
    font-family: 'Poppins', sans-serif;
    font-size: 15px; font-weight: 700; letter-spacing: 2.5px;
    text-transform: uppercase;
    background: linear-gradient(90deg, var(--sb-primary), var(--sb-secondary));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text; line-height: 1;
  }

  .sb-tagline {
    font-family: 'Poppins', sans-serif;
    font-size: 9.5px; letter-spacing: 1.8px; text-transform: uppercase;
    color: rgba(0, 210, 255, 0.45); padding-left: 48px;
  }

  .sb-status {
    position: relative; z-index: 1;
    margin: 14px 20px 0; padding: 8px 14px;
    background: rgba(0,255,136,0.05); border: 1px solid rgba(0,255,136,0.2);
    border-radius: 8px;
    display: flex; align-items: center; gap: 8px;
    font-family: 'Poppins', monospace; font-size: 10px;
    color: rgba(0,255,136,0.8); letter-spacing: 0.5px; flex-shrink: 0;
  }

  .sb-status-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: #00ff88; box-shadow: 0 0 8px #00ff88;
    animation: sb-pulse 2s ease-in-out infinite; flex-shrink: 0;
  }
  @keyframes sb-pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: 0.5; transform: scale(0.75); }
  }

  .sb-section-label {
    position: relative; z-index: 1;
    font-family: 'Poppins', sans-serif;
    font-size: 9px; font-weight: 600; letter-spacing: 2px;
    text-transform: uppercase; color: rgba(0,210,255,0.35);
    padding: 20px 24px 8px; flex-shrink: 0;
  }

  .sb-nav {
    position: relative; z-index: 1;
    flex: 1; overflow-y: auto; overflow-x: hidden;
    padding: 4px 12px;
    scrollbar-width: thin; scrollbar-color: rgba(0,210,255,0.2) transparent;
  }
  .sb-nav::-webkit-scrollbar { width: 3px; }
  .sb-nav::-webkit-scrollbar-thumb { background: rgba(0,210,255,0.2); border-radius: 2px; }

  .sb-item {
    display: flex; align-items: center; gap: 13px;
    padding: 11px 14px; border-radius: 10px;
    text-decoration: none; color: var(--sb-text);
    font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 500;
    letter-spacing: 0.2px; margin-bottom: 3px;
    border: 1px solid transparent;
    transition: background var(--sb-speed) var(--sb-ease),
                color var(--sb-speed) var(--sb-ease),
                border var(--sb-speed) var(--sb-ease),
                transform 0.2s var(--sb-ease),
                box-shadow var(--sb-speed) var(--sb-ease);
    position: relative; overflow: hidden;
  }

  .sb-item::before {
    content: ''; position: absolute; left: -100%; top: 0;
    width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(0,210,255,0.07), transparent);
    transition: left 0.5s ease; pointer-events: none;
  }
  .sb-item:hover::before { left: 100%; }

  .sb-item:hover {
    background: var(--sb-hover-bg); color: var(--sb-primary);
    border-color: rgba(0,210,255,0.18); transform: translateX(3px);
    box-shadow: 0 2px 16px rgba(0,210,255,0.08);
  }

  .sb-item.active {
    background: var(--sb-active-bg); color: var(--sb-primary);
    border-color: rgba(0,210,255,0.3);
    box-shadow: 0 0 14px rgba(0,210,255,0.12), inset 2px 0 0 var(--sb-primary);
  }

  .sb-icon {
    width: 32px; height: 32px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px;
    background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.07);
    flex-shrink: 0; transition: background 0.3s, border 0.3s, box-shadow 0.3s;
  }
  .sb-item:hover .sb-icon, .sb-item.active .sb-icon {
    background: rgba(0,210,255,0.12); border-color: rgba(0,210,255,0.25);
    box-shadow: 0 0 10px rgba(0,210,255,0.2);
  }

  .sb-item-text { flex: 1; line-height: 1; }

  .sb-arrow {
    width: 16px; height: 16px; opacity: 0;
    transform: translateX(-4px);
    transition: opacity 0.25s, transform 0.25s;
    color: var(--sb-primary); flex-shrink: 0;
  }
  .sb-item:hover .sb-arrow, .sb-item.active .sb-arrow { opacity: 1; transform: translateX(0); }

  .sb-divider {
    position: relative; z-index: 1; height: 1px; margin: 10px 20px;
    background: linear-gradient(90deg, transparent, var(--sb-border), transparent);
    flex-shrink: 0;
  }

  .sb-footer {
    position: relative; z-index: 1;
    padding: 14px 20px; border-top: 1px solid var(--sb-border); flex-shrink: 0;
  }
  .sb-footer-text {
    font-family: 'Poppins', monospace; font-size: 9px; letter-spacing: 1px;
    color: rgba(0,210,255,0.3); text-align: center; text-transform: uppercase;
  }

  /* ---- Hamburger ---- */
  #sb-toggle {
    position: fixed; top: 18px; left: 18px; z-index: 1050;
    width: 44px; height: 44px; border-radius: 10px;
    border: 1px solid rgba(0,210,255,0.3);
    background: rgba(5,5,20,0.9);
    backdrop-filter: blur(12px);
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    flex-direction: column; gap: 5px; padding: 0;
    transition: border-color 0.3s, box-shadow 0.3s, transform 0.2s;
    box-shadow: 0 4px 20px rgba(0,0,0,0.4);
  }
  #sb-toggle:hover {
    border-color: var(--sb-primary);
    box-shadow: 0 0 16px rgba(0,210,255,0.3); transform: scale(1.05);
  }

  .sb-bar {
    width: 20px; height: 2px; background: var(--sb-primary); border-radius: 2px;
    transition: transform var(--sb-speed) var(--sb-ease),
                opacity   var(--sb-speed) var(--sb-ease),
                width     var(--sb-speed) var(--sb-ease);
    box-shadow: 0 0 8px rgba(0,210,255,0.6);
  }
  #sb-toggle.open .sb-bar:nth-child(1) { transform: translateY(7px) rotate(45deg); }
  #sb-toggle.open .sb-bar:nth-child(2) { opacity: 0; width: 0; }
  #sb-toggle.open .sb-bar:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

  @media (min-width: 900px) {
    body.sb-open .container {
      margin-left: calc(var(--sb-w) + 30px);
      transition: margin-left var(--sb-speed) var(--sb-ease);
    }
    body:not(.sb-open) .container {
      margin-left: 0;
      transition: margin-left var(--sb-speed) var(--sb-ease);
    }
    #sb-overlay { display: none !important; }
  }
</style>

<!-- Overlay -->
<div id="sb-overlay" onclick="sbClose()"></div>

<!-- Toggle Button -->
<button id="sb-toggle" onclick="sbToggle()" aria-label="Toggle navigation">
  <span class="sb-bar"></span>
  <span class="sb-bar"></span>
  <span class="sb-bar"></span>
</button>

<!-- Sidebar -->
<nav id="sidebar" aria-label="Main navigation">

  <div class="sb-header">
    <div class="sb-logo-row">
      <div class="sb-logo-icon">
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M12 2L20 7V17L12 22L4 17V7L12 2Z" stroke="#00d2ff" stroke-width="1.5" stroke-linejoin="round"/>
          <path d="M12 8V12M12 12V16M12 12H16M12 12H8" stroke="#9d50bb" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
      </div>
      <span class="sb-logo-text">Core System</span>
    </div>
    <div class="sb-tagline">Academic Information System</div>
  </div>

  <div class="sb-status">
    <span class="sb-status-dot"></span>
    System Online &mdash; All services running
  </div>

  <div class="sb-nav">

    <div class="sb-section-label">Main Menu</div>

    <a href="dashboard.php" class="sb-item <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
      <span class="sb-icon">📊</span>
      <span class="sb-item-text">Analytics Dashboard</span>
      <svg class="sb-arrow" viewBox="0 0 16 16" fill="none">
        <path d="M6 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </a>

    <div class="sb-section-label">Data Management</div>

    <a href="index.php" class="sb-item <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
      <span class="sb-icon">👥</span>
      <span class="sb-item-text">Student Database</span>
      <svg class="sb-arrow" viewBox="0 0 16 16" fill="none">
        <path d="M6 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </a>

    <a href="dosen.php" class="sb-item <?php echo (basename($_SERVER['PHP_SELF']) == 'dosen.php') ? 'active' : ''; ?>">
      <span class="sb-icon">👨‍🏫</span>
      <span class="sb-item-text">Faculty Members</span>
      <svg class="sb-arrow" viewBox="0 0 16 16" fill="none">
        <path d="M6 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </a>

    <a href="matakuliah.php" class="sb-item <?php echo (basename($_SERVER['PHP_SELF']) == 'matakuliah.php') ? 'active' : ''; ?>">
      <span class="sb-icon">📚</span>
      <span class="sb-item-text">Course Catalog</span>
      <svg class="sb-arrow" viewBox="0 0 16 16" fill="none">
        <path d="M6 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </a>

    <a href="nilai.php" class="sb-item <?php echo (basename($_SERVER['PHP_SELF']) == 'nilai.php') ? 'active' : ''; ?>">
      <span class="sb-icon">📝</span>
      <span class="sb-item-text">Grade Management</span>
      <svg class="sb-arrow" viewBox="0 0 16 16" fill="none">
        <path d="M6 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </a>

  </div>

  <div class="sb-footer">
    <div class="sb-footer-text">CORE v1.0 &nbsp;&middot;&nbsp; PHP &amp; MySQL</div>
  </div>

</nav>

<script>
(function () {
  var sidebar = document.getElementById('sidebar');
  var toggle  = document.getElementById('sb-toggle');
  var overlay = document.getElementById('sb-overlay');
  var body    = document.body;
  var isOpen  = false;

  function sbOpen() {
    isOpen = true;
    sidebar.classList.add('open');
    toggle.classList.add('open');
    body.classList.add('sb-open');
    if (window.innerWidth < 900) overlay.classList.add('show');
  }

  function sbClose() {
    isOpen = false;
    sidebar.classList.remove('open');
    toggle.classList.remove('open');
    body.classList.remove('sb-open');
    overlay.classList.remove('show');
  }

  window.sbToggle = function () { isOpen ? sbClose() : sbOpen(); };
  window.sbClose  = sbClose;

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && isOpen) sbClose();
  });

  if (window.innerWidth >= 900) sbOpen();
})();
</script>
