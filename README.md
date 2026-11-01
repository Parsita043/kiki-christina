# kiki christina — Fashion E-commerce Website

A minimalist fashion store I built for the brand *kiki christina*, first deployed on Hostinger (PHP + MySQL) and later redesigned.

> Screenshots: _add desktop and mobile screenshots of Home, Collection, Product, Bespoke and Account here._

## Features

- **Home:** full-screen hero, scrolling marquee, "new in" product rail, bespoke editorial section and category tiles
- **Collection:** category filters and price sorting without page reloads, with shareable URLs (`shop.php?cat=dresses`)
- **Product pages:** generated from one catalogue (`product.php?id=...`), with hover-zoom image, size picker with validation, accordions and related products
- **Shopping bag:** slide-out drawer with quantities and subtotal that persists across pages (localStorage)
- **Accounts:** sign up, sign in and sign out with hashed passwords (`password_hash`), PDO prepared statements, session regeneration and output escaping
- **Bespoke and size guide pages:** appointment request form, cm/inch toggle
- Responsive from mobile to desktop, scroll-reveal animations, respects reduced-motion settings

## Tech stack

PHP 8, PDO (MySQL in production, SQLite locally), HTML, CSS, vanilla JavaScript. No frameworks.

## Structure

```
includes/   products.php (catalogue + helpers), header.php, footer.php
assets/     site.css, site.js
*.php       index, shop, product, bespoke, sizeguide, account, logout
```

## Run locally (Windows)

1. Install PHP once: `winget install PHP.PHP.8.3`, then open a new terminal.
2. Double-click `start.bat`. The site opens at http://localhost:8000.

Locally the site uses a SQLite file in `data/` (created automatically). For MySQL hosting, set `DB_HOST`, `DB_NAME`, `DB_USER` and `DB_PASS` as environment variables. No passwords are stored in the code.

---

Built by **Parsa Mehrabi** — [GitHub](https://github.com/Parsita043) · [LinkedIn](https://www.linkedin.com/in/parsa-mehrabi-6298983a4/)
