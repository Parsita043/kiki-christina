<?php
require_once __DIR__ . '/includes/products.php';
$p = find_product($_GET['id'] ?? '');
if (!$p) { header('Location: shop.php'); exit; }

$pageTitle = $p['name'] . ' — kiki christina';
$active = 'shop.php';
require __DIR__ . '/includes/header.php';

$bagData = ['id' => $p['id'], 'name' => $p['name'], 'price' => $p['price'], 'img' => product_img($p, 300)];
$related = array_values(array_filter(PRODUCTS, fn($x) => $x['cat'] === $p['cat'] && $x['id'] !== $p['id']));
if (count($related) < 4) {
    $related = array_merge($related, array_values(array_filter(PRODUCTS, fn($x) => $x['cat'] !== $p['cat'])));
}
$related = array_slice($related, 0, 4);
?>

<section class="pdp" data-product="<?= e(json_encode($bagData)) ?>">
    <div class="pdp-media">
        <img src="<?= e(product_img($p, 1400)) ?>" alt="<?= e($p['name']) ?>" data-fallback="bespoke.jpg">
    </div>

    <div class="pdp-info">
        <p class="crumbs"><a href="shop.php">collection</a> / <a href="shop.php?cat=<?= e($p['cat']) ?>"><?= e($p['cat']) ?></a></p>
        <h1><?= e($p['name']) ?></h1>
        <p class="pdp-price"><?= price($p['price']) ?></p>
        <p class="pdp-desc"><?= e($p['desc']) ?></p>

        <div>
            <div class="size-head"><span>select size (eu)</span><a href="sizeguide.php" class="link" style="font-size:11px">size guide</a></div>
            <div class="sizes">
                <?php foreach (SIZES as $s): ?><button class="size" type="button"><?= $s ?></button><?php endforeach; ?>
            </div>
            <p class="size-msg" aria-live="polite"></p>
        </div>

        <button class="btn btn-dark btn-block" data-add>add to bag</button>

        <div class="perks"><span>✓ free shipping over 300 €</span><span>✓ 14-day returns</span><span>✓ made in paris</span></div>

        <div class="acc">
            <details open><summary>details & care</summary><p>dry clean or gentle hand wash. store folded or on a wide hanger. made in our paris atelier.</p></details>
            <details><summary>shipping & returns</summary><p>complimentary standard shipping on orders over 300 €. returns accepted within 14 days of delivery, in original condition.</p></details>
            <details><summary>bespoke alterations</summary><p>every piece can be adjusted to your measurements. <a href="bespoke.php" class="link">book a fitting</a></p></details>
        </div>
    </div>
</section>

<section class="section" style="padding-top: 40px;">
    <div class="section-head reveal"><h2>you may also like</h2></div>
    <div class="grid">
        <?php foreach ($related as $i => $r) echo product_card($r, $i); ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
