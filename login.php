<?php
declare(strict_types=1);
require __DIR__.'/app/bootstrap.php';
if (!$config['installed']) redirect(base_url('setup.php'));
$error='';
$mode=($_POST['login_mode'] ?? $_GET['mode'] ?? '')==='owner' ? 'owner' : 'delivery';
try {
    if (Auth::user()) redirect(url('dashboard'));
    if ($_SERVER['REQUEST_METHOD']==='POST') {
        check_csrf();
        if ($mode==='owner') Auth::login(text_field($_POST,'username',80,true),(string)($_POST['password'] ?? ''));
        else Auth::loginCode(is_string($_POST['access_code'] ?? null) ? $_POST['access_code'] : '');
        redirect(url($GLOBALS['user']['role']==='OWNER' && $GLOBALS['user']['must_reset'] ? 'profile' : 'dashboard'));
    }
} catch (AppError $e) { $error=$e->getMessage(); http_response_code($e->httpStatus); }
catch (Throwable $e) { error_log((string)$e); http_response_code(503); $error='The station database is unavailable. Please check the server connection.'; }
$secretId=$mode==='owner'?'owner-password':'access-code';
$secretName=$mode==='owner'?'password':'code';
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="theme-color" content="#eef5ec"><title>Sign in &middot; Aqua Rose</title>
<link rel="icon" href="assets/images/logo.svg" type="image/svg+xml">
<link rel="stylesheet" href="assets/css/app.css?v=<?=filemtime(__DIR__.'/assets/css/app.css')?>">
<link rel="stylesheet" href="assets/css/login.css?v=<?=filemtime(__DIR__.'/assets/css/login.css')?>">
<script defer src="assets/js/login.js?v=<?=filemtime(__DIR__.'/assets/js/login.js')?>"></script>
</head>
<body class="animated-login" data-login-page data-login-error="<?=$error?'true':'false'?>" data-state="idle">
<div class="ar-login-shell">
    <section class="login-scene" aria-labelledby="scene-title">
        <a class="login-brand" href="login.php"><img src="assets/images/logo.svg" alt=""><span>AQUA ROSE<small>WATER REFILLING STATION</small></span></a>
        <div class="scene-copy"><span class="scene-eyebrow">A LITTLE FRESHNESS, EVERY DAY</span><h1 id="scene-title">Good water.<br>Even better days.</h1><p>A happy little workspace for the people<br class="desktop-break"> behind every refill.</p></div>
        <?php require __DIR__.'/app/views/login-mascots.php'; ?>
        <div class="scene-footer"><p data-mascot-caption aria-hidden="true">Your refill crew is ready.</p></div>
    </section>
    <main class="login-panel">
        <nav class="signin-switch" aria-label="Sign-in type"><a href="login.php" <?=$mode==='delivery'?'aria-current="page"':''?>>Staff sign-in</a><a href="login.php?mode=owner" <?=$mode==='owner'?'aria-current="page"':''?>>Owner sign-in</a></nav>
        <div class="login-heading"><span class="welcome-mark" aria-hidden="true"><svg viewBox="0 0 32 32"><path d="M16 4C13 10 7 15 7 21a9 9 0 0018 0c0-6-6-11-9-17Z" fill="currentColor"/><path d="M12 22c0 3 2 4 5 4" fill="none" stroke="#d4f0da" stroke-width="2.4" stroke-linecap="round"/><path d="m25 3 1.4 3.6L30 8l-3.6 1.4L25 13l-1.4-3.6L20 8l3.6-1.4Z" fill="#78a47b"/></svg></span><span class="login-kicker"><?=$mode==='owner'?'FOR THE STATION OWNER':'HELLO, TEAM AQUA ROSE'?></span><h2>Welcome back<span>.</span></h2><p><?=$mode==='owner'?'Your station is in good hands. Sign in to continue.':'Enter your 6-digit code. Let&rsquo;s make today a good one.'?></p></div>
        <?php if ($error): ?><div class="notice danger login-error" id="login-error" role="alert"><?=e($error)?></div><?php endif; ?>
        <form method="post" action="login.php<?=$mode==='owner'?'?mode=owner':''?>" class="login-fields" data-login-form>
            <?=csrf_field()?><input type="hidden" name="login_mode" value="<?=e($mode)?>">
            <?php if ($mode==='owner'): ?>
            <label for="owner-username">Username<input id="owner-username" autofocus required name="username" autocomplete="username" maxlength="80" value="<?=e(is_string($_POST['username'] ?? null)?$_POST['username']:'')?>" placeholder="Your username" <?=$error?'aria-invalid="true" aria-describedby="login-error"':''?>></label>
            <div class="secret-field"><label for="owner-password">Password</label><div class="secret-input"><input id="owner-password" required name="password" type="password" autocomplete="current-password" placeholder="Your password" data-login-secret <?=$error?'aria-invalid="true" aria-describedby="login-error"':''?>>
            <?php else: ?>
            <div class="secret-field"><label for="access-code">6-digit access code</label><div class="secret-input"><input id="access-code" class="access-code-input" type="password" name="access_code" inputmode="numeric" pattern="[0-9]{6}" minlength="6" maxlength="6" autocomplete="off" required autofocus placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;" aria-describedby="code-help<?=$error?' login-error':''?>" <?=$error?'aria-invalid="true"':''?> data-login-secret>
            <?php endif; ?>
            <button class="secret-toggle" type="button" data-secret-toggle aria-controls="<?=e($secretId)?>" aria-pressed="false" aria-label="Show <?=e($secretName)?>" title="Show <?=e($secretName)?>" hidden><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/><path class="eye-slash" d="m3 3 18 18"/></svg></button></div></div>
            <?php if ($mode!=='owner'): ?><small id="code-help" class="login-help">Use the code provided by your station owner.</small><?php endif; ?>
            <button class="login-submit" type="submit"><span data-submit-label>Sign in to workspace</span><svg class="submit-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12h16m-6-6 6 6-6 6"/></svg><span class="submit-spinner" aria-hidden="true"></span></button>
            <p class="login-assistance"><?=$mode==='owner'?'Need a password reset? Contact your system administrator.':'Need a code or a reset? Contact your station owner.'?></p>
        </form>
        <div class="login-bottom-note"><svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m10 2 6 3v4c0 4-3 7-6 9-3-2-6-5-6-9V5l6-3Z"/><path d="m7 10 2 2 4-4"/></svg><span>A little care in every refill.</span></div>
    </main>
</div>
<p class="login-page-footer">Aqua Rose &middot; Fresh water. Clear operations.</p>
</body>
</html>
