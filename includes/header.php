<?php
// Shared <head>, navigation, menu drawer and bag drawer.
// Set $pageTitle and $active before including.
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/products.php';
$pageTitle = $pageTitle ?? 'kiki christina';
$active = $active ?? '';
$signedIn = isset($_SESSION['user_email']);
$navLinks = ['shop.php' => 'collection', 'bespoke.php' => 'bespoke', 'sizeguide.php' => 'size guide'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="kiki christina — considered womenswear and bespoke tailoring from paris.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/site.css">
</head>
<body class="<?= $active === 'home' ? 'is-home' : '' ?>">

<div class="announce">complimentary shipping on orders over 300 € &nbsp;·&nbsp; bespoke appointments now open</div>

<header class="nav" id="nav">
    <div class="nav-left">
        <button class="nav-btn" data-open="menu" aria-label="open menu">
            <span class="burger"><span></span><span></span></span><span class="hide-sm">menu</span>
        </button>
        <nav class="nav-links hide-sm">
            <?php foreach ($navLinks as $href => $label): ?>
                <a href="<?= $href ?>" class="<?= $active === $href ? 'is-active' : '' ?>"><?= $label ?></a>
            <?php endforeach; ?>
        </nav>
    </div>
    <a href="index.php" class="logo">kiki christina</a>
    <div class="nav-right">
        <a href="account.php" class="nav-btn hide-sm"><?= $signedIn ? 'account' : 'sign in' ?></a>
        <button class="nav-btn" data-open="bag">bag <span class="bag-count" data-bag-count>0</span></button>
    </div>
</header>

<div class="scrim" data-close></div>

<aside class="drawer drawer-left" id="drawer-menu" aria-hidden="true">
    <button class="drawer-close" data-close>close</button>
    <nav class="drawer-nav">
        <a href="index.php">home</a>
        <a href="shop.php">collection</a>
        <?php foreach (CATEGORIES as $c): ?>
            <a href="shop.php?cat=<?= $c ?>" class="sub"><?= $c ?></a>
        <?php endforeach; ?>
        <a href="bespoke.php">bespoke</a>
        <a href="sizeguide.php">size guide</a>
    </nav>
    <div class="drawer-foot">
        <?php if ($signedIn): ?>
            <p class="muted">signed in as</p>
            <p><?= e($_SESSION['user_email']) ?></p>
            <a href="logout.php" class="link">sign out</a>
        <?php else: ?>
            <a href="account.php" class="link">sign in / create account</a>
        <?php endif; ?>
    </div>
</aside>

<aside class="drawer drawer-right" id="drawer-bag" aria-hidden="true">
    <div class="drawer-head">
        <h2 class="serif">your bag</h2>
        <button class="drawer-close" data-close>close</button>
    </div>
    <div class="bag-items" data-bag-items></div>
    <div class="bag-foot" data-bag-foot>
        <div class="bag-row"><span>subtotal</span><span data-bag-total>0 €</span></div>
        <p class="muted small">shipping and taxes calculated at checkout.</p>
        <button class="btn btn-dark btn-block" data-checkout>checkout</button>
    </div>
</aside>

<main>
