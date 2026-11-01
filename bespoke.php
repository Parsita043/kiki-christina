<?php
$pageTitle = 'bespoke — kiki christina';
$active = 'bespoke.php';
require __DIR__ . '/includes/header.php';
?>

<section class="split" style="min-height: 70vh;">
    <div class="split-media"><img src="https://images.unsplash.com/photo-1470309864661-68328b2cd0a5?q=80&w=1600&auto=format&fit=crop" alt="atelier" data-fallback="bespoke.jpg"></div>
    <div class="split-body">
        <p class="eyebrow">bespoke service</p>
        <h2>made for you,<br><em>and only you</em></h2>
        <p>a collaborative journey from sketch to final stitch. heritage craftsmanship tailored to your vision, your measurements and the way you move.</p>
        <div><a href="#book" class="btn btn-dark">request an appointment</a></div>
    </div>
</section>

<section class="section">
    <div class="section-head reveal"><div><p class="eyebrow">the process</p><h2>four steps</h2></div></div>
    <div class="steps">
        <div class="step reveal"><h3>consultation</h3><p>we talk through the occasion, your style and references, in the atelier or by video call.</p></div>
        <div class="step reveal"><h3>measurements</h3><p>over 30 measurements are taken and a personal pattern is drafted by hand.</p></div>
        <div class="step reveal"><h3>fabric & fittings</h3><p>choose from our fabric library, then refine the fit across three fittings.</p></div>
        <div class="step reveal"><h3>delivery</h3><p>your finished piece is pressed, wrapped and delivered in 6–8 weeks.</p></div>
    </div>
</section>

<section class="split" id="book" style="min-height: auto;">
    <div class="split-media"><img src="bespoke.jpg" alt="" loading="lazy"></div>
    <div class="split-body">
        <p class="eyebrow">appointments</p>
        <h2>book a fitting</h2>
        <form class="form" data-demo-form style="max-width: 440px;">
            <div class="field"><label for="b-name">full name</label><input id="b-name" required></div>
            <div class="field"><label for="b-email">email</label><input id="b-email" type="email" required></div>
            <div class="field"><label for="b-type">i'm interested in</label>
                <select id="b-type"><option>bespoke garment</option><option>alterations</option><option>bridal & occasion</option></select></div>
            <div class="field"><label for="b-msg">tell us about your idea</label><textarea id="b-msg" rows="3"></textarea></div>
            <button class="btn btn-dark" style="margin-top: 10px;">send request</button>
        </form>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
