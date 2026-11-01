<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/products.php';

$error = '';
$tab = 'signin';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (isset($_POST['register'])) {
        $tab = 'register';
        $fullname = trim($_POST['name'] ?? '');
        if ($fullname === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'please enter your name and a valid email.';
        } elseif (strlen($password) < 8) {
            $error = 'password must be at least 8 characters.';
        } else {
            try {
                $stmt = $conn->prepare('INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)');
                $stmt->execute([$fullname, $email, password_hash($password, PASSWORD_DEFAULT)]);
                session_regenerate_id(true);
                $_SESSION['user_email'] = $email;
                $_SESSION['user_name'] = $fullname;
                header('Location: account.php');
                exit();
            } catch (PDOException $e) {
                $error = 'this email is already registered.';
            }
        }
    }

    if (isset($_POST['login'])) {
        $stmt = $conn->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['fullname'];
            header('Location: account.php');
            exit();
        }
        $error = 'invalid email or password.';
    }
}

$pageTitle = 'account — kiki christina';
$active = 'account.php';
require __DIR__ . '/includes/header.php';
?>

<section class="auth">
    <div class="auth-media"><img src="https://images.unsplash.com/photo-1608234808654-2a8875faa7fd?q=80&w=1400&auto=format&fit=crop" alt="" data-fallback="bespoke.jpg"></div>

    <div class="auth-body">
    <?php if (isset($_SESSION['user_email'])): ?>
        <p class="eyebrow">my account</p>
        <h1 class="serif" style="font-size: clamp(40px, 5vw, 64px); line-height: 1.05; margin: 14px 0 18px;">welcome back<?= isset($_SESSION['user_name']) ? ',<br>' . e(explode(' ', $_SESSION['user_name'])[0]) : '' ?></h1>
        <p class="muted" style="margin-bottom: 36px;">signed in as <?= e($_SESSION['user_email']) ?></p>
        <div class="acc" style="margin-bottom: 36px;">
            <details open><summary>orders</summary><p>you haven't placed any orders yet.</p></details>
            <details><summary>bespoke appointments</summary><p>no upcoming appointments. <a href="bespoke.php" class="link">book a fitting</a></p></details>
        </div>
        <div style="display:flex; gap:14px; flex-wrap:wrap;">
            <a href="shop.php" class="btn btn-dark">continue shopping</a>
            <a href="logout.php" class="btn">sign out</a>
        </div>
    <?php else: ?>
        <div class="auth-tabs">
            <button type="button" data-tab="signin" class="<?= $tab === 'signin' ? 'is-active' : '' ?>">sign in</button>
            <button type="button" data-tab="register" class="<?= $tab === 'register' ? 'is-active' : '' ?>">create account</button>
        </div>

        <?php if ($error): ?><p class="form-note error" style="margin-bottom: 20px;"><?= e($error) ?></p><?php endif; ?>

        <div class="auth-panel" id="signin" <?= $tab !== 'signin' ? 'hidden' : '' ?>>
            <form method="POST" class="form">
                <div class="field"><label for="l-email">email</label><input id="l-email" type="email" name="email" required autocomplete="email"></div>
                <div class="field"><label for="l-pass">password</label><input id="l-pass" type="password" name="password" required autocomplete="current-password"></div>
                <button type="submit" name="login" class="btn btn-dark" style="margin-top: 14px;">sign in</button>
            </form>
        </div>

        <div class="auth-panel" id="register" <?= $tab !== 'register' ? 'hidden' : '' ?>>
            <form method="POST" class="form">
                <div class="field"><label for="r-name">full name</label><input id="r-name" type="text" name="name" required autocomplete="name"></div>
                <div class="field"><label for="r-email">email</label><input id="r-email" type="email" name="email" required autocomplete="email"></div>
                <div class="field"><label for="r-pass">password (min. 8 characters)</label><input id="r-pass" type="password" name="password" minlength="8" required autocomplete="new-password"></div>
                <button type="submit" name="register" class="btn btn-dark" style="margin-top: 14px;">create account</button>
            </form>
        </div>
        <p class="muted small" style="margin-top: 28px;">by continuing you agree to our terms and privacy policy.</p>
    <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
