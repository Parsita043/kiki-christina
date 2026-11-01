<?php
$pageTitle = 'kiki christina — official site';
$active = 'home';
$extraScripts = ['https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js', 'assets/home3d.js'];
require __DIR__ . '/includes/header.php';

$u = fn(string $id, int $w = 1600) => "https://images.unsplash.com/photo-$id?q=80&w=$w&auto=format&fit=crop";
$ring = array_map(fn($p) => product_img($p, 800), array_slice(PRODUCTS, 0, 10));
$stack = array_slice(PRODUCTS, 0, 5);
$newIn = array_slice(PRODUCTS, 0, 8);
?>

<!-- intro loader -->
<div class="loader" data-loader>
    <span class="loader-brand">kiki christina</span>
    <span class="loader-count" data-loader-count>0</span>
</div>

<!-- 1. 3D hero: a ring of the collection you scroll through -->
<section class="h3d" data-h3d>
    <div class="h3d-sticky">
        <canvas class="h3d-canvas" data-ring='<?= e(json_encode($ring)) ?>'></canvas>
        <div class="h3d-fallback" aria-hidden="true"><img src="<?= $u('1490481651871-ab68de25d43d', 2000) ?>" alt=""></div>
        <div class="h3d-title" data-h3d-title>
            <p class="eyebrow">autumn / winter 2026</p>
            <h1><span>kiki</span><span>christina</span></h1>
        </div>
        <div class="h3d-tagline" data-h3d-tagline>
            <h2>quiet <em>luxury</em>,<br>made to last.</h2>
            <a href="shop.php" class="btn btn-light">enter the collection</a>
        </div>
        <div class="scroll-hint" data-scroll-hint><span></span>scroll</div>
    </div>
</section>

<!-- 2. velocity marquee -->
<div class="vmarquee" data-vmarquee aria-hidden="true">
    <div class="vmarquee-track" data-vmarquee-track>
        <?php for ($i = 0; $i < 3; $i++): ?>
            <span>handmade in paris</span><span class="o">natural fibres</span><span>bespoke fittings</span><span class="o">small-batch</span>
        <?php endfor; ?>
    </div>
</div>

<!-- 3. pinned 3D card stack that fans out as you scroll -->
<section class="fan" data-fan>
    <div class="fan-sticky">
        <div class="fan-copy">
            <p class="eyebrow">the edit</p>
            <h2>five pieces,<br><em>endless</em> wear.</h2>
            <p class="muted">scroll to unfold the season's signatures.</p>
        </div>
        <div class="fan-stage">
            <?php foreach ($stack as $i => $p): ?>
                <a href="product.php?id=<?= e($p['id']) ?>" class="fan-card" data-fan-card style="--i: <?= $i ?>">
                    <img src="<?= e(product_img($p, 700)) ?>" alt="<?= e($p['name']) ?>" data-fallback="bespoke.jpg">
                    <span class="fan-label"><b><?= e($p['name']) ?></b><i><?= price($p['price']) ?></i></span>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="fan-progress"><span data-fan-bar></span></div>
    </div>
</section>

<!-- 4. new in rail with 3D tilt cards -->
<section class="section">
    <div class="section-head reveal">
        <div><p class="eyebrow">new in</p><h2>just arrived</h2></div>
        <a href="shop.php" class="link">view all pieces</a>
    </div>
    <div class="rail">
        <?php foreach ($newIn as $i => $p) echo product_card($p, $i); ?>
    </div>
</section>

<!-- 5. bespoke with parallax + clip reveal -->
<section class="split split-3d">
    <div class="split-media" data-clip><img src="<?= $u('1470309864661-68328b2cd0a5') ?>" alt="bespoke atelier" loading="lazy" data-parallax="0.18" data-fallback="bespoke.jpg"></div>
    <div class="split-body reveal">
        <p class="eyebrow">the atelier</p>
        <h2>bespoke, from<br>sketch to <em>final stitch</em></h2>
        <p>every bespoke piece begins with a conversation. we take your measurements, choose fabrics together and build the garment by hand over three fittings — so it fits only you.</p>
        <div><a href="bespoke.php" class="btn btn-dark">book an appointment</a></div>
    </div>
</section>

<!-- 6. big statement with scroll-scrubbed words -->
<section class="statement" data-statement>
    <p><?php foreach (explode(' ', 'we make fewer things, better — clothes meant to be worn for years, repaired, and passed on.') as $w) echo "<span>$w</span> "; ?></p>
</section>

<!-- 7. categories with 3D hover -->
<section class="section" style="padding-top: 0;">
    <div class="section-head reveal"><div><p class="eyebrow">shop by</p><h2>category</h2></div></div>
    <div class="cats">
        <a href="shop.php?cat=outerwear" class="cat-tile tilt reveal"><img src="<?= $u('1608234808654-2a8875faa7fd', 900) ?>" alt="" loading="lazy" data-fallback="bespoke.jpg"><span>outerwear</span></a>
        <a href="shop.php?cat=dresses" class="cat-tile tilt reveal"><img src="<?= $u('1566174053879-31528523f8ae', 900) ?>" alt="" loading="lazy" data-fallback="bespoke.jpg"><span>dresses</span></a>
        <a href="shop.php?cat=essentials" class="cat-tile tilt reveal"><img src="item2.jpg" alt="" loading="lazy"><span>essentials</span></a>
    </div>
</section>

<section class="values">
    <div class="reveal"><p class="eyebrow">01</p><h3>made in paris</h3><p>cut and sewn in our own atelier in small runs, never mass-produced.</p></div>
    <div class="reveal"><p class="eyebrow">02</p><h3>natural fibres</h3><p>wool, silk, linen and organic cotton chosen to soften and last for years.</p></div>
    <div class="reveal"><p class="eyebrow">03</p><h3>easy returns</h3><p>complimentary shipping over 300 € and free returns within 14 days.</p></div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
