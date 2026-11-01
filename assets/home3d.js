// kiki christina — home page 3D + scroll experience
// 1) intro loader  2) Three.js ring of the collection, driven by scroll + mouse
// 3) pinned 3D card fan  4) velocity marquee  5) parallax / clip / word reveals
(() => {
    const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
    const clamp = (v, a = 0, b = 1) => Math.min(b, Math.max(a, v));
    const lerp = (a, b, t) => a + (b - a) * t;
    const ease = t => t < .5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;   // easeInOutCubic
    const range = (v, a, b) => clamp((v - a) / (b - a));                          // map v in [a,b] -> [0,1]
    const sectionProgress = el => {
        const r = el.getBoundingClientRect();
        return clamp(-r.top / Math.max(1, r.height - innerHeight));
    };

    // ---------------- loader ----------------
    const loader = document.querySelector('[data-loader]');
    const countEl = document.querySelector('[data-loader-count]');
    let loadTarget = 0, loadShown = 0, loaderDone = false;
    const started = performance.now();
    function finishLoader() {
        if (loaderDone) return; loaderDone = true;
        loadTarget = 100;
        setTimeout(() => { document.body.classList.add('is-loaded'); loader?.remove(); }, 650);
    }
    setTimeout(finishLoader, 3200); // never block longer than this
    (function tickLoader() {
        loadShown += (loadTarget - loadShown) * .12;
        if (countEl) countEl.textContent = Math.round(loadShown);
        if (!loaderDone || loadShown < 99.5) requestAnimationFrame(tickLoader);
    })();

    // ---------------- 3D ring ----------------
    const hero = document.querySelector('[data-h3d]');
    const canvas = hero?.querySelector('canvas');
    const titleEl = document.querySelector('[data-h3d-title]');
    const taglineEl = document.querySelector('[data-h3d-tagline]');
    const hintEl = document.querySelector('[data-scroll-hint]');
    let three = null;

    function webglOK() {
        try { const c = document.createElement('canvas'); return !!(window.WebGLRenderingContext && (c.getContext('webgl') || c.getContext('experimental-webgl'))); }
        catch { return false; }
    }

    if (canvas && window.THREE && webglOK()) {
        const T = window.THREE;
        const urls = JSON.parse(canvas.dataset.ring || '[]');
        const BG = 0x15120f;
        const renderer = new T.WebGLRenderer({ canvas, antialias: true, alpha: false });
        renderer.setPixelRatio(Math.min(devicePixelRatio, 2));
        renderer.outputEncoding = T.sRGBEncoding;
        renderer.setClearColor(BG, 1);

        const scene = new T.Scene();
        scene.fog = new T.Fog(BG, 9, 21);
        const camera = new T.PerspectiveCamera(35, 1, .1, 100);

        const ring = new T.Group();
        scene.add(ring);
        const N = urls.length || 10, R = 6, slot = Math.PI * 2 / N, arc = slot * .8;
        const H = (R * arc) / .75; // keep a 3:4 portrait shape
        const loaderTex = new T.TextureLoader();
        loaderTex.setCrossOrigin('anonymous');
        let loaded = 0;
        const fallbacks = ['item1.jpg', 'item2.jpg', 'item3.jpg', 'bespoke.jpg'];

        function fitCover(tex) {
            const img = tex.image, a = img.width / img.height, seg = .75;
            tex.repeat.set(1, 1); tex.offset.set(0, 0);
            if (a > seg) { tex.repeat.x = seg / a; tex.offset.x = (1 - tex.repeat.x) / 2; }
            else { tex.repeat.y = a / seg; tex.offset.y = (1 - tex.repeat.y) / 2; }
        }

        urls.forEach((url, i) => {
            const geo = new T.CylinderGeometry(R, R, H, 24, 1, true, i * slot, arc);
            const mat = new T.MeshBasicMaterial({ color: 0x2a2520, side: T.DoubleSide, transparent: true, opacity: 0 });
            const mesh = new T.Mesh(geo, mat);
            mesh.userData.delay = i * .06;
            ring.add(mesh);
            const done = tex => {
                tex.encoding = T.sRGBEncoding; tex.anisotropy = 8; fitCover(tex);
                mat.map = tex; mat.color.set(0xffffff); mat.needsUpdate = true;
                loaded++; loadTarget = Math.round(loaded / N * 100);
                if (loaded === N) finishLoader();
            };
            const fail = () => loaderTex.load(fallbacks[i % fallbacks.length], done, undefined, () => { loaded++; loadTarget = Math.round(loaded / N * 100); if (loaded === N) finishLoader(); });
            loaderTex.load(url, done, undefined, fail);
        });

        // floating dust
        const P = 420, pos = new Float32Array(P * 3);
        for (let i = 0; i < P; i++) {
            pos[i * 3] = (Math.random() - .5) * 26; pos[i * 3 + 1] = (Math.random() - .5) * 14; pos[i * 3 + 2] = (Math.random() - .5) * 26;
        }
        const pgeo = new T.BufferGeometry(); pgeo.setAttribute('position', new T.BufferAttribute(pos, 3));
        const dust = new T.Points(pgeo, new T.PointsMaterial({ color: 0xd8c3a5, size: .045, transparent: true, opacity: .7, depthWrite: false }));
        scene.add(dust);

        function resize() {
            const w = canvas.clientWidth, h = canvas.clientHeight;
            renderer.setSize(w, h, false);
            camera.aspect = w / h;
            camera.fov = w < 700 ? 52 : 35;
            camera.updateProjectionMatrix();
        }
        addEventListener('resize', resize); resize();

        const mouse = { x: 0, y: 0, sx: 0, sy: 0 };
        addEventListener('pointermove', e => { mouse.x = e.clientX / innerWidth - .5; mouse.y = e.clientY / innerHeight - .5; });

        let visible = true;
        new IntersectionObserver(([en]) => visible = en.isIntersecting).observe(hero);
        const t0 = performance.now();
        three = {
            frame(p, now) {
                if (!visible) return;
                const t = (now - t0) / 1000;
                mouse.sx += (mouse.x - mouse.sx) * .05; mouse.sy += (mouse.y - mouse.sy) * .05;
                const e = ease(p);
                ring.rotation.y = (reduce ? 0 : t * .06) + e * Math.PI * 1.6 + mouse.sx * .35;
                ring.rotation.x = lerp(.16, -.05, e) + mouse.sy * .12;
                ring.rotation.z = lerp(-.06, 0, e);
                ring.position.y = lerp(-.2, .1, e);
                const far = camera.aspect < 1 ? 1.75 : 1;
                scene.fog.near = 9 * far; scene.fog.far = 21 * far;
                camera.position.set(mouse.sx * 1.2, lerp(1.4, .2, e) - mouse.sy * .6, lerp(17, 12.2, e) * far);
                camera.lookAt(0, 0, 0);
                ring.children.forEach(m => { m.material.opacity = Math.min(1, m.material.opacity + (m.material.map ? .035 : 0)); });
                dust.rotation.y = t * .015; dust.position.y = Math.sin(t * .3) * .2;
                renderer.render(scene, camera);
            }
        };
    } else {
        hero?.classList.add('no-webgl');
        finishLoader();
    }

    // ---------------- fan of cards ----------------
    const fan = document.querySelector('[data-fan]');
    const fanCards = [...document.querySelectorAll('[data-fan-card]')];
    const fanBar = document.querySelector('[data-fan-bar]');
    function fanFrame() {
        if (!fan) return;
        const q = reduce ? 1 : sectionProgress(fan);
        const a = ease(range(q, .05, .5)), b = ease(range(q, .55, .95));
        const w = fanCards[0]?.offsetWidth || 260, vw = innerWidth;
        const narrow = vw < 700;
        const sc = narrow ? 1 : lerp(1, Math.min(.86, (vw - 80) / (w * fanCards.length * 1.06)), b);
        const rowGap = narrow ? w * .42 : w * sc * 1.06;
        fanCards.forEach((card, i) => {
            const c = i - (fanCards.length - 1) / 2;
            // pile -> arc
            const x1 = lerp(c * 6, c * Math.min(150, vw * .12), a);
            const y1 = lerp(-i * 8, Math.abs(c) * 34, a);
            const rz1 = lerp(c * 4 - 2, c * 13, a);
            const z1 = lerp(-i * 30, -Math.abs(c) * 40, a);
            // arc -> row
            const x = lerp(x1, c * rowGap, b), y = lerp(y1, 0, b), rz = lerp(rz1, 0, b);
            const ry = lerp(0, c * (narrow ? -38 : -10), b), z = lerp(z1, -Math.abs(c) * (narrow ? 140 : 60), b);
            card.style.transform = `translate3d(${x}px, ${y}px, ${z}px) rotateZ(${rz}deg) rotateY(${ry}deg) scale(${sc})`;
            card.style.zIndex = 10 - Math.round(Math.abs(c));
            card.classList.toggle('show-label', b > .85 && (!narrow || c === 0));
        });
        if (fanBar) fanBar.style.transform = `scaleX(${q})`;
    }

    // ---------------- marquee, parallax, clip, statement ----------------
    const mTrack = document.querySelector('[data-vmarquee-track]');
    let mX = 0, lastY = scrollY, vel = 0;
    const parallax = [...document.querySelectorAll('[data-parallax]')];
    const clips = [...document.querySelectorAll('[data-clip]')];
    const statement = document.querySelector('[data-statement]');
    const words = statement ? [...statement.querySelectorAll('span')] : [];

    function scrollFx() {
        const y = scrollY;
        vel += ((y - lastY) - vel) * .15; lastY = y;
        if (mTrack) {
            mX -= (reduce ? 0 : .6) + Math.abs(vel) * .35;
            const w = mTrack.scrollWidth / 3;
            if (-mX > w) mX += w;
            mTrack.style.transform = `translate3d(${mX}px,0,0) skewX(${clamp(-vel * .25, -12, 12)}deg)`;
        }
        parallax.forEach(img => {
            const r = img.parentElement.getBoundingClientRect();
            const off = (r.top + r.height / 2 - innerHeight / 2) * parseFloat(img.dataset.parallax);
            img.style.transform = `translate3d(0, ${off}px, 0) scale(1.18)`;
        });
        clips.forEach(el => {
            const r = el.getBoundingClientRect();
            const p = ease(range(innerHeight - r.top, 0, innerHeight * .8));
            el.style.clipPath = `inset(${lerp(14, 0, p)}% ${lerp(10, 0, p)}% ${lerp(14, 0, p)}% ${lerp(10, 0, p)}% round ${lerp(24, 0, p)}px)`;
        });
        if (statement) {
            const r = statement.getBoundingClientRect();
            const p = range(innerHeight * .85 - r.top, 0, r.height * .9);
            words.forEach((w, i) => w.style.opacity = clamp(.12 + (p * words.length - i) * .9, .12, 1));
        }
    }

    // ---------------- hero overlay text ----------------
    function heroText(p) {
        if (!hero) return;
        const t1 = range(p, 0, .35), t2 = range(p, .42, .72);
        if (titleEl) {
            titleEl.style.opacity = 1 - t1;
            titleEl.style.transform = `translate3d(-50%, calc(-50% - ${t1 * 60}px), 0) scale(${1 + t1 * .15})`;
            titleEl.style.letterSpacing = `${t1 * .25}em`;
        }
        if (taglineEl) {
            taglineEl.style.opacity = t2;
            taglineEl.style.transform = `translate3d(0, ${(1 - t2) * 40}px, 0)`;
            taglineEl.style.pointerEvents = t2 > .5 ? 'auto' : 'none';
        }
        hintEl?.classList.toggle('hide', p > .02);
        document.body.classList.toggle('on-dark', hero.getBoundingClientRect().bottom > 80);
    }

    // ---------------- main loop ----------------
    function loop(now) {
        const p = hero ? (reduce ? .6 : sectionProgress(hero)) : 0;
        heroText(p);
        three?.frame(p, now);
        fanFrame();
        scrollFx();
        requestAnimationFrame(loop);
    }
    requestAnimationFrame(loop);
})();
