<?php
include "config.php";

if(isset($_POST['login'])){

$username=$_POST['username'];
$password=$_POST['password'];

$stmt=$conn->prepare("SELECT * FROM admins WHERE username=?");
$stmt->bind_param("s",$username);
$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows==1){

$user=$result->fetch_assoc();

if(password_verify($password,$user['password'])){

$_SESSION['admin_id']=$user['id'];
$_SESSION['username']=$user['username'];

header("Location: admin/dashboard.php");
exit();

}else{
$error="Invalid password. Please try again.";
}

}else{
$error="No account found with that username.";
}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script>
(function(){
  var t = localStorage.getItem('adbu_theme') || 'light';
  document.documentElement.setAttribute('data-theme', t);
})();
</script>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login — ADBU</title>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300;1,400&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --bg:          #f8f7f4;
    --surface:     #ffffff;
    --surface-2:   #f3f2ef;
    --border:      #e5e3de;
    --border-2:    #d4d1ca;
    --ink:         #18171a;
    --ink-2:       #44424d;
    --ink-3:       #8a8794;
    --accent:      #2d6a4f;
    --accent-2:    #1b4332;
    --accent-pale: #ecfdf5;
    --accent-ring: rgba(16,185,129,0.18);
    --red:         #9f1239;
    --red-pale:    #fff1f2;
    --red-border:  rgba(225,29,72,0.2);
    --radius:      12px;
    --radius-sm:   8px;
  }

  body {
    min-height: 100vh;
    background: var(--bg);
    font-family: 'DM Sans', sans-serif;
    color: var(--ink);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    -webkit-font-smoothing: antialiased;
    position: relative;
    overflow: hidden;
  }

  body::before {
    content: '';
    position: fixed; inset: 0;
    background-image:
      radial-gradient(circle at 20% 20%, rgba(45,106,79,0.06) 0%, transparent 50%),
      radial-gradient(circle at 80% 80%, rgba(45,106,79,0.04) 0%, transparent 50%);
    pointer-events: none;
  }

  body::after {
    content: '';
    position: fixed; inset: 0;
    background-image:
      linear-gradient(var(--border) 1px, transparent 1px),
      linear-gradient(90deg, var(--border) 1px, transparent 1px);
    background-size: 40px 40px;
    opacity: 0.35;
    pointer-events: none;
  }

  .login-shell {
    width: 100%;
    max-width: 420px;
    position: relative;
    z-index: 1;
    animation: riseIn 0.55s cubic-bezier(0.16,1,0.3,1) both;
  }

  @keyframes riseIn {
    from { opacity: 0; transform: translateY(20px) scale(0.98); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
  }

  /* Brand */
  .brand-top {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    margin-bottom: 28px;
    text-align: center;
  }

  .brand-top img {
    height: 54px; width: auto;
    object-fit: contain;
    margin-bottom: 4px;
  }

  .brand-name {
    font-family: 'Outfit', sans-serif;
    font-size: 15px; font-weight: 600;
    color: var(--ink);
    letter-spacing: -0.01em;
  }

  .brand-sub {
    font-size: 12px;
    color: var(--ink-3);
  }

  /* Card */
  .card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow:
      0 1px 2px rgba(0,0,0,0.04),
      0 8px 32px rgba(0,0,0,0.07),
      0 24px 64px rgba(0,0,0,0.04);
    overflow: hidden;
  }

  .card-header {
    padding: 26px 32px 22px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 14px;
  }

  .card-icon {
    width: 44px; height: 44px;
    background: var(--accent-pale);
    border: 1px solid var(--accent-ring);
    border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    color: var(--accent);
  }

  .card-icon svg { width: 20px; height: 20px; }

  .card-header-text h2 {
    font-family: 'Outfit', sans-serif;
    font-size: 18px; font-weight: 700;
    letter-spacing: -0.02em;
    color: var(--ink);
    line-height: 1;
    margin-bottom: 4px;
  }

  .card-header-text p {
    font-size: 13px; color: var(--ink-3);
  }

  .card-body { padding: 28px 32px 32px; }

  /* Error */
  .error-box {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    background: var(--red-pale);
    border: 1px solid var(--red-border);
    border-radius: var(--radius-sm);
    padding: 12px 14px;
    margin-bottom: 24px;
    animation: shake 0.4s cubic-bezier(0.36,0.07,0.19,0.97) both;
  }

  @keyframes shake {
    10%, 90% { transform: translateX(-2px); }
    20%, 80% { transform: translateX(3px); }
    30%, 50%, 70% { transform: translateX(-3px); }
    40%, 60% { transform: translateX(3px); }
  }

  .error-icon { width: 16px; height: 16px; color: var(--red); flex-shrink: 0; margin-top: 1px; }

  .error-box span { font-size: 13.5px; color: var(--red); line-height: 1.45; }

  /* Fields */
  .field { margin-bottom: 18px; }
  .field:last-of-type { margin-bottom: 24px; }

  label {
    display: block;
    font-family: 'Outfit', sans-serif;
    font-size: 12px; font-weight: 600;
    letter-spacing: 0.04em; text-transform: uppercase;
    color: var(--ink-2);
    margin-bottom: 7px;
  }

  .input-wrap { position: relative; }

  .input-icon {
    position: absolute;
    left: 12px; top: 50%; transform: translateY(-50%);
    color: var(--ink-3);
    pointer-events: none;
    width: 16px; height: 16px;
  }

  input[type="text"],
  input[type="password"] {
    width: 100%;
    background: var(--surface-2);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 11px 14px 11px 40px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14.5px;
    color: var(--ink);
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    -webkit-appearance: none;
  }

  input::placeholder { color: var(--ink-3); }

  input:focus {
    border-color: var(--accent);
    background: var(--surface);
    box-shadow: 0 0 0 3px var(--accent-ring);
  }

  /* Submit */
  .btn-submit {
    width: 100%;
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    padding: 13px 24px;
    font-family: 'Outfit', sans-serif;
    font-size: 14px; font-weight: 600;
    letter-spacing: 0.02em;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
    position: relative; overflow: hidden;
  }

  .btn-submit::after {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,0.12) 50%, transparent 70%);
    transform: translateX(-100%);
    transition: transform 0.5s;
  }

  .btn-submit:hover {
    background: var(--accent-2);
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(45,106,79,0.28);
  }

  .btn-submit:hover::after { transform: translateX(100%); }
  .btn-submit:active { transform: translateY(0); box-shadow: none; }
  .btn-submit svg { width: 16px; height: 16px; }

  /* Card footer */
  .card-footer {
    padding: 14px 32px;
    border-top: 1px solid var(--border);
    background: var(--surface-2);
    display: flex; align-items: center; justify-content: center; gap: 6px;
  }

  .card-footer svg { width: 13px; height: 13px; color: var(--ink-3); }
  .card-footer span { font-size: 12px; color: var(--ink-3); }

  /* Page footer */
  .page-footer { text-align: center; margin-top: 20px; }
  .page-footer span {
    font-family: 'JetBrains Mono', monospace;
    font-size: 11px; color: var(--ink-3); letter-spacing: 0.02em;
  }

  @media (max-width: 480px) {
    body { padding: 16px; align-items: flex-start; padding-top: 32px; }
    .login-shell { max-width: 100%; }
    .card-header { padding: 20px 20px 18px; gap: 10px; }
    .card-body { padding: 22px 20px 24px; }
    .card-footer { padding: 12px 20px; }
    .brand-top img { height: 44px; }
    .brand-name { font-size: 13.5px; }
  }

  /* ── DARK MODE ── */
  [data-theme="dark"] {
    --bg:          #0f0f11;
    --surface:     #1a1a1e;
    --surface-2:   #222228;
    --border:      #2e2e36;
    --border-2:    #3a3a44;
    --ink:         #f0eff4;
    --ink-2:       #b8b6c4;
    --ink-3:       #6b6878;
    --accent:      #34d399;
    --accent-2:    #10b981;
    --accent-pale: rgba(52,211,153,0.1);
    --accent-ring: rgba(52,211,153,0.2);
    --red:         #fb7185;
    --red-pale:    rgba(251,113,133,0.1);
    --red-border:  rgba(251,113,133,0.25);
    --amber:       #fbbf24;
    --amber-pale:  rgba(251,191,36,0.1);
    --amber-border:rgba(251,191,36,0.25);
    --blue:        #60a5fa;
    --blue-pale:   rgba(96,165,250,0.1);
  }

  [data-theme="dark"] body::after { opacity: 0.12; }
  [data-theme="dark"] body::before { opacity: 0.5; }
  [data-theme="dark"] .card { box-shadow: 0 1px 2px rgba(0,0,0,0.3), 0 8px 32px rgba(0,0,0,0.4); }
  [data-theme="dark"] .btn-submit { box-shadow: none; }
  [data-theme="dark"] .btn-submit:hover { box-shadow: 0 6px 20px rgba(52,211,153,0.2); }
  [data-theme="dark"] .btn-primary:hover { box-shadow: 0 4px 14px rgba(52,211,153,0.2); }
  [data-theme="dark"] .event-card:hover,
  [data-theme="dark"] .event-panel { box-shadow: 0 6px 24px rgba(0,0,0,0.4); }
  [data-theme="dark"] .cal-grid { box-shadow: 0 1px 4px rgba(0,0,0,0.3); }
  [data-theme="dark"] .site-header { background: rgba(26,26,30,0.92); }
  [data-theme="dark"] .topbar { background: rgba(26,26,30,0.85); }
  [data-theme="dark"] .sidebar { box-shadow: none; border-right-color: var(--border); }
  [data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); }

  /* Theme toggle */
  .theme-toggle {
    width: 36px; height: 36px;
    border: 1px solid var(--border);
    background: var(--surface-2);
    border-radius: var(--radius-sm);
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: var(--ink-3); flex-shrink: 0;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
  }
  .theme-toggle:hover { background: var(--border); color: var(--ink); }
  .theme-toggle svg { width: 16px; height: 16px; }
  .theme-toggle .icon-sun  { display: none; }
  .theme-toggle .icon-moon { display: block; }
  [data-theme="dark"] .theme-toggle .icon-sun  { display: block; }
  [data-theme="dark"] .theme-toggle .icon-moon { display: none; }

</style>
</head>
<body>

<div class="login-shell"><div style="display:flex;justify-content:flex-end;margin-bottom:-20px;position:relative;z-index:2;"><button class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle dark mode"><svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg><svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg></button></div>

  <div class="brand-top">
    <img src="logo.png" alt="ADBU Logo">
    <span class="brand-name">Assam Don Bosco University</span>
    <span class="brand-sub">Academic Administration Portal</span>
  </div>

  <div class="card">

    <div class="card-header">
      <div class="card-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="11" width="18" height="11" rx="2"/>
          <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
      </div>
      <div class="card-header-text">
        <h2>Administrator Sign In</h2>
        <p>Restricted to authorised personnel only</p>
      </div>
    </div>

    <div class="card-body">

      <?php if(isset($error)): ?>
      <div class="error-box" role="alert">
        <svg class="error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/>
          <line x1="12" y1="8" x2="12" y2="12"/>
          <circle cx="12" cy="16" r="0.5" fill="currentColor"/>
        </svg>
        <span><?= htmlspecialchars($error) ?></span>
      </div>
      <?php endif; ?>

      <form method="post" novalidate>

        <div class="field">
          <label for="username">Username</label>
          <div class="input-wrap">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
            <input type="text" id="username" name="username"
              placeholder="Enter your username"
              required autocomplete="username"
              value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
          </div>
        </div>

        <div class="field">
          <label for="password">Password</label>
          <div class="input-wrap">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            <input type="password" id="password" name="password"
              placeholder="Enter your password"
              required autocomplete="current-password">
          </div>
        </div>

        <button type="submit" name="login" class="btn-submit">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
            <polyline points="10 17 15 12 10 7"/>
            <line x1="15" y1="12" x2="3" y2="12"/>
          </svg>
          Sign In
        </button>

      </form>

    </div>

    <div class="card-footer">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
      </svg>
      <span>Secure session &mdash; all access is logged</span>
    </div>

  </div>

  <div class="page-footer">
    <span>ADBU &nbsp;&middot;&nbsp; Academic Registry &nbsp;&middot;&nbsp; <?= date('Y') ?></span>
  </div>

</div>


<script>
function toggleTheme() {
  var cur = document.documentElement.getAttribute('data-theme') || 'light';
  var next = cur === 'dark' ? 'light' : 'dark';
  document.documentElement.setAttribute('data-theme', next);
  localStorage.setItem('adbu_theme', next);
}
// Apply saved theme immediately
(function(){
  var t = localStorage.getItem('adbu_theme') || 'light';
  document.documentElement.setAttribute('data-theme', t);
})();
</script>
</body>
</html>