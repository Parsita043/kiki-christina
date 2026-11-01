</main>

<footer class="footer">
    <div class="footer-top">
        <div class="footer-brand">
            <a href="index.php" class="logo">kiki christina</a>
            <p class="muted">considered womenswear and bespoke tailoring, made in small runs in paris.</p>
        </div>
        <div class="footer-col">
            <h4>shop</h4>
            <a href="shop.php">all pieces</a>
            <a href="shop.php?cat=outerwear">outerwear</a>
            <a href="shop.php?cat=dresses">dresses</a>
            <a href="shop.php?cat=essentials">essentials</a>
        </div>
        <div class="footer-col">
            <h4>house</h4>
            <a href="bespoke.php">bespoke</a>
            <a href="sizeguide.php">size guide</a>
            <a href="account.php">account</a>
        </div>
        <form class="footer-news" data-newsletter>
            <h4>newsletter</h4>
            <p class="muted">new collections and private appointments, first.</p>
            <div class="news-field">
                <input type="email" placeholder="email address" required aria-label="email address">
                <button type="submit">join</button>
            </div>
            <p class="small news-ok" hidden>thank you — you're on the list.</p>
        </form>
    </div>
    <div class="footer-bottom">
        <span>© <?= date('Y') ?> kiki christina · paris</span>
        <span>instagram · whatsapp</span>
    </div>
</footer>

<div class="toast" data-toast></div>
<script src="assets/site.js"></script>
</body>
</html>
