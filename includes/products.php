<?php
// Single product catalogue used by every page.
// `img` is either a local file (assets/...) or an Unsplash photo id.

const PRODUCTS = [
    ['id' => 'suede-bomber',      'name' => 'suede bomber jacket',        'cat' => 'outerwear', 'price' => 690, 'img' => 'u:1591047139829-d91aecb6caea', 'tag' => 'new',
     'desc' => 'cropped bomber in brushed cognac suede with ribbed cuffs and a clean, collarless neckline.'],
    ['id' => 'check-blazer',      'name' => 'check wool blazer',          'cat' => 'outerwear', 'price' => 610, 'img' => 'u:1608234808654-2a8875faa7fd', 'tag' => '',
     'desc' => 'single-breasted blazer in a soft grey check wool, cut long with structured shoulders.'],
    ['id' => 'denim-jacket',      'name' => 'corduroy-collar denim jacket','cat' => 'outerwear', 'price' => 340, 'img' => 'u:1611312449408-fcece27cdbb7', 'tag' => '',
     'desc' => 'rinse-wash denim trucker jacket finished with a camel corduroy collar.'],
    ['id' => 'velvet-gown',       'name' => 'velvet off-shoulder gown',   'cat' => 'dresses',   'price' => 890, 'img' => 'u:1566174053879-31528523f8ae', 'tag' => 'new',
     'desc' => 'bordeaux stretch velvet with a sculpted off-shoulder neckline and floor-length skirt.'],
    ['id' => 'floral-silk-dress', 'name' => 'floral silk dress',          'cat' => 'dresses',   'price' => 520, 'img' => 'u:1496747611176-843222e1e57c', 'tag' => '',
     'desc' => 'fluid silk crêpe printed with an archive floral, cut on the bias for movement.'],
    ['id' => 'linen-maxi',        'name' => 'linen maxi dress',           'cat' => 'dresses',   'price' => 480, 'img' => 'u:1539008835657-9e8e9680c956', 'tag' => '',
     'desc' => 'airy washed linen with a wrap bodice and side slit. made for long summer days.'],
    ['id' => 'denim-shirt-dress', 'name' => 'denim shirt dress',          'cat' => 'dresses',   'price' => 260, 'img' => 'u:1591369822096-ffd140ec948f', 'tag' => '',
     'desc' => 'light-wash cotton denim with a fitted waist, short sleeves and button front.'],
    ['id' => 'crochet-poncho',    'name' => 'crochet knit poncho',        'cat' => 'knitwear',  'price' => 340, 'img' => 'u:1434389677669-e08b4cac3105', 'tag' => '',
     'desc' => 'hand-crochet ecru cotton with a fringed hem. layer over everything.'],
    ['id' => 'blush-trousers',    'name' => 'blush tapered trousers',     'cat' => 'trousers',  'price' => 290, 'img' => 'u:1594633312681-425c7b97ccd1', 'tag' => '',
     'desc' => 'soft drape trousers in powder blush with an elasticated waist and cuffed ankle.'],
    ['id' => 'essential-tee',     'name' => 'essential cotton tee',       'cat' => 'essentials','price' => 65,  'img' => 'item1.jpg', 'tag' => '',
     'desc' => 'the everyday white tee in heavyweight organic cotton with a relaxed fit.'],
    ['id' => 'bordeaux-tee',      'name' => 'bordeaux heavyweight tee',   'cat' => 'essentials','price' => 75,  'img' => 'item2.jpg', 'tag' => '',
     'desc' => 'garment-dyed in deep bordeaux. boxy shoulder, slightly cropped length.'],
    ['id' => 'sand-tee',          'name' => 'sand relaxed tee',           'cat' => 'essentials','price' => 70,  'img' => 'item3.jpg', 'tag' => '',
     'desc' => 'brushed jersey in warm sand with a curved hem and embroidered mark.'],
];

const CATEGORIES = ['outerwear', 'dresses', 'knitwear', 'trousers', 'essentials'];
const SIZES = ['34', '36', '38', '40', '42', '44'];

function product_img(array $p, int $w = 900): string {
    if (str_starts_with($p['img'], 'u:')) {
        return 'https://images.unsplash.com/photo-' . substr($p['img'], 2) . "?q=80&w={$w}&auto=format&fit=crop";
    }
    return $p['img'];
}

function find_product(string $id): ?array {
    foreach (PRODUCTS as $p) if ($p['id'] === $id) return $p;
    return null;
}

function price(int $eur): string {
    return number_format($eur, 0, ',', '.') . ' €';
}

function product_card(array $p, int $order = 0): string {
    $tag = $p['tag'] ? '<span class="card-tag">' . e($p['tag']) . '</span>' : '';
    return '<a class="card reveal" href="product.php?id=' . e($p['id']) . '" data-cat="' . e($p['cat']) . '" data-price="' . $p['price'] . '" data-order="' . $order . '">'
        . '<div class="card-media">' . $tag
        . '<img src="' . e(product_img($p, 700)) . '" alt="' . e($p['name']) . '" loading="lazy" data-fallback="bespoke.jpg">'
        . '<span class="card-quick">view piece</span></div>'
        . '<div class="card-info"><div><p class="card-name">' . e($p['name']) . '</p><p class="card-cat">' . e($p['cat']) . '</p></div>'
        . '<span class="card-price">' . price($p['price']) . '</span></div></a>';
}

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
