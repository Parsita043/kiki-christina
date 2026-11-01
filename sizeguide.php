<?php
$pageTitle = 'size guide — kiki christina';
$active = 'sizeguide.php';
require __DIR__ . '/includes/header.php';
$rows = [
    ['34', '2', '6', '80-83', '62-65', '86-89'],
    ['36', '4', '8', '84-87', '66-69', '90-93'],
    ['38', '6', '10', '88-91', '70-73', '94-97'],
    ['40', '8', '12', '92-95', '74-77', '98-101'],
    ['42', '10', '14', '96-99', '78-81', '102-105'],
    ['44', '12', '16', '100-104', '82-86', '106-110'],
];
?>

<section class="page-head">
    <p class="eyebrow">find your fit</p>
    <h1>size guide</h1>
    <p>body measurements. if you're between sizes, we recommend sizing up.</p>
</section>

<section class="wrap" style="max-width: 1000px; margin: 0 auto; padding-bottom: clamp(64px, 9vw, 120px);">
    <div style="display:flex; justify-content:flex-end; margin-bottom:16px;">
        <div class="unit-toggle"><button class="is-active" data-unit="cm">cm</button><button data-unit="in">inches</button></div>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>eu</th><th>us</th><th>uk</th><th>bust</th><th>waist</th><th>hips</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
                <tr><td><?= $r[0] ?></td><td><?= $r[1] ?></td><td><?= $r[2] ?></td>
                    <?php foreach ([3, 4, 5] as $k): ?><td data-cm="<?= $r[$k] ?>"><?= str_replace('-', '–', $r[$k]) ?></td><?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="steps" style="grid-template-columns: repeat(3, 1fr); margin-top: 64px;">
        <div class="step"><h3>bust</h3><p>measure around the fullest part of your chest, keeping the tape level.</p></div>
        <div class="step"><h3>waist</h3><p>measure around the narrowest part of your natural waistline.</p></div>
        <div class="step"><h3>hips</h3><p>stand with feet together and measure around the fullest part of your hips.</p></div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
