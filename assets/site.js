// kiki christina — front-end behaviour (drawers, bag, product page, small UI touches)
(() => {
    const $ = (s, el = document) => el.querySelector(s);
    const $$ = (s, el = document) => [...el.querySelectorAll(s)];
    const BAG_KEY = 'kc_bag_v1';
    const fmt = n => n.toLocaleString('de-DE') + ' €';

    // ---------- storage (safe) ----------
    const load = () => { try { return JSON.parse(localStorage.getItem(BAG_KEY)) || []; } catch { return []; } };
    const save = bag => { try { localStorage.setItem(BAG_KEY, JSON.stringify(bag)); } catch {} };
    let bag = load();

    // ---------- toast ----------
    const toastEl = $('[data-toast]');
    let toastTimer;
    function toast(msg) {
        if (!toastEl) return;
        toastEl.textContent = msg;
        toastEl.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toastEl.classList.remove('show'), 2600);
    }

    // ---------- drawers ----------
    function openDrawer(name) {
        document.body.classList.remove('menu-open', 'bag-open');
        document.body.classList.add(name + '-open');
    }
    function closeDrawers() { document.body.classList.remove('menu-open', 'bag-open'); }
    $$('[data-open]').forEach(b => b.addEventListener('click', () => openDrawer(b.dataset.open)));
    $$('[data-close]').forEach(b => b.addEventListener('click', closeDrawers));
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawers(); });

    // ---------- bag ----------
    function renderBag() {
        const items = $('[data-bag-items]'), foot = $('[data-bag-foot]');
        const count = bag.reduce((n, i) => n + i.qty, 0);
        const total = bag.reduce((n, i) => n + i.qty * i.price, 0);
        $$('[data-bag-count]').forEach(el => el.textContent = count);
        if (!items) return;
        if (!bag.length) {
            items.innerHTML = '<div class="bag-empty"><p>your bag is empty</p><a href="shop.php" class="btn btn-dark">discover the collection</a></div>';
            foot.hidden = true;
            return;
        }
        foot.hidden = false;
        items.innerHTML = bag.map((i, idx) => `
            <div class="bag-item">
                <img src="${i.img}" alt="">
                <div>
                    <h3>${i.name}</h3>
                    <p class="muted small">size ${i.size}</p>
                    <div class="qty">
                        <button data-dec="${idx}" aria-label="decrease">−</button><span>${i.qty}</span><button data-inc="${idx}" aria-label="increase">+</button>
                    </div>
                    <button class="bag-remove" data-rm="${idx}">remove</button>
                </div>
                <span class="small">${fmt(i.price * i.qty)}</span>
            </div>`).join('');
        $('[data-bag-total]').textContent = fmt(total);
    }

    document.addEventListener('click', e => {
        const t = e.target;
        if (t.dataset.inc !== undefined) { bag[t.dataset.inc].qty++; }
        else if (t.dataset.dec !== undefined) { const i = bag[t.dataset.dec]; i.qty > 1 ? i.qty-- : bag.splice(t.dataset.dec, 1); }
        else if (t.dataset.rm !== undefined) { bag.splice(t.dataset.rm, 1); }
        else return;
        save(bag); renderBag();
    });

    function addToBag(item) {
        const same = bag.find(i => i.id === item.id && i.size === item.size);
        same ? same.qty++ : bag.push({ ...item, qty: 1 });
        save(bag); renderBag();
        openDrawer('bag');
    }

    $('[data-checkout]')?.addEventListener('click', () => {
        toast('checkout is a demo — no payment is taken');
    });

    // ---------- product page ----------
    const pdp = $('[data-product]');
    if (pdp) {
        const data = JSON.parse(pdp.dataset.product);
        let size = '';
        const sizes = $('.sizes', pdp), msg = $('.size-msg', pdp);
        $$('.size', pdp).forEach(btn => btn.addEventListener('click', () => {
            $$('.size', pdp).forEach(b => b.classList.remove('is-active'));
            btn.classList.add('is-active');
            size = btn.textContent.trim();
            msg.textContent = '';
        }));
        $('[data-add]', pdp).addEventListener('click', () => {
            if (!size) {
                msg.textContent = 'please choose a size';
                sizes.classList.remove('shake'); void sizes.offsetWidth; sizes.classList.add('shake');
                return;
            }
            addToBag({ ...data, size });
            toast(`${data.name} added to your bag`);
        });
        const media = $('.pdp-media');
        media?.addEventListener('mousemove', e => {
            const r = media.getBoundingClientRect();
            $('img', media).style.transformOrigin = `${(e.clientX - r.left) / r.width * 100}% ${(e.clientY - r.top) / r.height * 100}%`;
        });
        media?.addEventListener('click', () => media.classList.toggle('zoom'));
        media?.addEventListener('mouseleave', () => media.classList.remove('zoom'));
    }

    // ---------- shop filters (no reload) ----------
    const grid = $('[data-grid]');
    if (grid) {
        const cards = $$('.card', grid);
        const countEl = $('[data-count]');
        const apply = () => {
            const cat = $('.chip.is-active')?.dataset.cat || 'all';
            const sort = $('[data-sort]').value;
            let shown = cards.filter(c => cat === 'all' || c.dataset.cat === cat);
            cards.forEach(c => c.hidden = !shown.includes(c));
            const by = { low: (a, b) => a.dataset.price - b.dataset.price, high: (a, b) => b.dataset.price - a.dataset.price, featured: (a, b) => a.dataset.order - b.dataset.order };
            shown.sort(by[sort]).forEach(c => grid.appendChild(c));
            countEl.textContent = shown.length + (shown.length === 1 ? ' piece' : ' pieces');
            const url = new URL(location); cat === 'all' ? url.searchParams.delete('cat') : url.searchParams.set('cat', cat);
            history.replaceState(null, '', url);
        };
        $$('.chip').forEach(ch => ch.addEventListener('click', () => {
            $$('.chip').forEach(c => c.classList.remove('is-active'));
            ch.classList.add('is-active'); apply();
        }));
        $('[data-sort]').addEventListener('change', apply);
        apply();
    }

    // ---------- size guide units ----------
    $$('[data-unit]').forEach(btn => btn.addEventListener('click', () => {
        $$('[data-unit]').forEach(b => b.classList.toggle('is-active', b === btn));
        const inch = btn.dataset.unit === 'in';
        $$('[data-cm]').forEach(td => {
            const [a, b] = td.dataset.cm.split('-').map(Number);
            td.textContent = inch ? `${(a / 2.54).toFixed(1)}–${(b / 2.54).toFixed(1)}` : `${a}–${b}`;
        });
    }));

    // ---------- account tabs ----------
    $$('[data-tab]').forEach(btn => btn.addEventListener('click', () => {
        $$('[data-tab]').forEach(b => b.classList.toggle('is-active', b === btn));
        $$('.auth-panel').forEach(p => p.hidden = p.id !== btn.dataset.tab);
    }));

    // ---------- newsletter + bespoke form (demo) ----------
    $$('[data-newsletter]').forEach(f => f.addEventListener('submit', e => {
        e.preventDefault(); $('.news-ok', f).hidden = false; f.reset();
    }));
    $$('[data-demo-form]').forEach(f => f.addEventListener('submit', e => {
        e.preventDefault(); f.reset(); toast('thank you — our atelier will contact you within 48 hours');
    }));

    // ---------- nav state, reveal on scroll, broken images ----------
    const nav = $('#nav');
    const onScroll = () => nav?.classList.toggle('is-scrolled', scrollY > 40);
    addEventListener('scroll', onScroll, { passive: true }); onScroll();

    const io = 'IntersectionObserver' in window ? new IntersectionObserver(entries => entries.forEach(en => {
        if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); }
    }), { rootMargin: '0px 0px -8% 0px' }) : null;
    $$('.reveal').forEach(el => io ? io.observe(el) : el.classList.add('in'));

    const useFallback = img => {
        if (img.dataset.fallback && img.src.indexOf(img.dataset.fallback) === -1) img.src = img.dataset.fallback;
        else img.classList.add('broken');
    };
    $$('img').forEach(img => {
        img.addEventListener('error', () => useFallback(img));
        if (img.complete && img.naturalWidth === 0 && img.getAttribute('loading') !== 'lazy') useFallback(img);
    });

    // ---------- 3D tilt + glare on cards and tiles (mouse only) ----------
    const finePointer = matchMedia('(hover: hover) and (pointer: fine)').matches;
    const calm = matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (finePointer && !calm) {
        $$('.card-media, .tilt').forEach(el => {
            const glare = document.createElement('span'); glare.className = 'glare'; el.appendChild(glare);
            const host = el.closest('.card') || el;
            host.addEventListener('pointermove', e => {
                const r = el.getBoundingClientRect();
                const x = (e.clientX - r.left) / r.width, y = (e.clientY - r.top) / r.height;
                el.style.transition = 'transform .12s ease-out';
                el.style.transform = `rotateX(${(.5 - y) * 10}deg) rotateY(${(x - .5) * 12}deg) translateZ(10px)`;
                el.style.setProperty('--gx', x * 100 + '%'); el.style.setProperty('--gy', y * 100 + '%');
            });
            host.addEventListener('pointerleave', () => { el.style.transition = ''; el.style.transform = ''; });
        });

        // custom cursor
        const cur = $('.cursor');
        if (cur) {
            let cx = innerWidth / 2, cy = innerHeight / 2, tx = cx, ty = cy;
            addEventListener('pointermove', e => { tx = e.clientX; ty = e.clientY; cur.classList.add('on'); });
            document.addEventListener('pointerleave', () => cur.classList.remove('on'));
            document.addEventListener('pointerover', e => cur.classList.toggle('big', !!e.target.closest('a, button, .card, .fan-card, select, input, .size')));
            (function move() {
                cx += (tx - cx) * .22; cy += (ty - cy) * .22;
                cur.style.transform = `translate3d(${cx}px, ${cy}px, 0)`;
                requestAnimationFrame(move);
            })();
        }
    }

    renderBag();
})();
