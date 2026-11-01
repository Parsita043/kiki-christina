<?php
$pageTitle = 'collection — kiki christina';
$active = 'shop.php';
require __DIR__ . '/includes/header.php';
$cat = $_GET['cat'] ?? 'all';
if ($cat !== 'all' && !in_array($cat, CATEGORIES, true)) $cat = 'all';
?>

<section class="page-head">
    <p class="eyebrow">autumn / winter 2026</p>
    <h1>the collection</h1>
    <p>considered pieces in natural fibres, made in small runs.</p>
</section>

<div class="toolbar">
    <div class="chips">
        <button class="chip <?= $cat === 'all' ? 'is-active' : '' ?>" data-cat="all">all</button>
        <?php foreach (CATEGORIES as $c): ?>
            <button class="chip <?= $cat === $c ? 'is-active' : '' ?>" data-cat="<?= $c ?>"><?= $c ?></button>
        <?php endforeach; ?>
    </div>
    <div style="display:flex; gap:22px; align-items:center;">
        <span class="count" data-count></span>
        <select class="sort" data-sort aria-label="sort">
            <option value="featured">featured</option>
            <option value="low">price: low to high</option>
            <option value="high">price: high to low</option>
        </select>
    </div>
</div>

<section class="wrap" style="padding-bottom: clamp(64px, 9vw, 120px);">
    <div class="grid" data-grid>
        <?php foreach (PRODUCTS as $i => $p) echo product_card($p, $i); ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
