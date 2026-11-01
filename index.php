<?php
$pageTitle = 'kiki christina — official site';
$active = 'home';
require __DIR__ . '/includes/header.php';
$newIn = array_slice(PRODUCTS, 0, 8);
$u = fn(string $id, int $w = 1600) => "https://images.unsplash.com/photo-$id?q=80&w=$w&auto=format&fit=crop";
?>

<section class="hero">
    <img src="<?= $u('1490481651871-ab68de25d43d', 2200) ?>" alt="" data-fallback="bespoke.jpg">
    <div class="hero-inner">
        <div>
            <p class="eyebrow">autumn / winter 2026</p>
            <h1>quiet<br><em>luxury,</em> made<br>to last</h1>
        </div>
        <div class="hero-cta">
            <a href="shop.php" class="btn btn-light">shop the collection</a>
            <a href="bespoke.php" class="btn btn-light">bespoke</a>
        </div>
    </div>
</section>

<div class="marquee" aria-hidden="true">
    <div class="marquee-track">
        <?php for ($i = 0; $i < 2; $i++): ?>
            <span>handmade in paris</span><span>small-batch production</span><span>natural fibres</span><span>bespoke fittings</span><span>free returns within 14 days</span>
        <?php endfor; ?>
    </div>
</div>

<section class="section">
    <div class="section-head reveal">
        <div>
            <p class="eyebrow">new in</p>
            <h2>the edit</h2>
        </div>
        <a href="shop.php" class="link">view all pieces</a>
    </div>
    <div class="rail">
        <?php foreach ($newIn as $i => $p) echo product_card($p, $i); ?>
    </div>
</section>

<section class="split">
    <div class="split-media"><img src="<?= $u('1470309864661-68328b2cd0a5') ?>" alt="bespoke atelier" loading="lazy" data-fallback="bespoke.jpg"></div>
    <div class="split-body reveal">
        <p class="eyebrow">the atelier</p>
        <h2>bespoke, from<br>sketch to <em>final stitch</em></h2>
        <p>every bespoke piece begins with a conversation. we take your measurements, choose fabrics together and build the garment by hand over three fittings — so it fits only you.</p>
        <div><a href="bespoke.php" class="btn btn-dark">book an appointment</a></div>
    </div>
</section>

<section class="section">
    <div class="section-head reveal">
        <div>
            <p class="eyebrow">shop by</p>
            <h2>category</h2>
        </div>
    </div>
    <div class="cats">
        <a href="shop.php?cat=outerwear" class="cat-tile reveal"><img src="<?= $u('1608234808654-2a8875faa7fd', 900) ?>" alt="" loading="lazy" data-fallback="bespoke.jpg"><span>outerwear</span></a>
        <a href="shop.php?cat=dresses" class="cat-tile reveal"><img src="<?= $u('1566174053879-31528523f8ae', 900) ?>" alt="" loading="lazy" data-fallback="bespoke.jpg"><span>dresses</span></a>
        <a href="shop.php?cat=essentials" class="cat-tile reveal"><img src="item2.jpg" alt="" loading="lazy"><span>essentials</span></a>
    </div>
</section>

<section class="values">
    <div class="reveal"><p class="eyebrow">01</p><h3>made in paris</h3><p>cut and sewn in our own atelier in small runs, never mass-produced.</p></div>
    <div class="reveal"><p class="eyebrow">02</p><h3>natural fibres</h3><p>wool, silk, linen and organic cotton chosen to soften and last for years.</p></div>
    <div class="reveal"><p class="eyebrow">03</p><h3>easy returns</h3><p>complimentary shipping over 300 € and free returns within 14 days.</p></div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
