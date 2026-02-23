<?php
session_start();
require 'logic/db.php';
require 'logic/auth.php';

if (checkAuth($pdo)) {
    header('Location: main.php');
    exit;
}

$error   = '';
$success = false;

if (isset($_GET['password_changed'])) $success = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_password = $_POST['password'] ?? '';
    $remember       = isset($_POST['remember']);

    $stmt = $pdo->prepare("SELECT setting_value FROM config WHERE setting_name = 'admin_password'");
    $stmt->execute();
    $config = $stmt->fetch();

    if ($config && password_verify($input_password, $config['setting_value'])) {
        $_SESSION['logged_in'] = true;

        if ($remember) {
            try {
                $token      = bin2hex(random_bytes(32));
                $token_hash = password_hash($token, PASSWORD_DEFAULT);
                $stmt_token = $pdo->prepare("INSERT INTO auth_tokens (token_hash, created_at) VALUES (?, NOW())");
                $stmt_token->execute([$token_hash]);
                setcookie('auth_token', $token, time() + (3600 * 24 * 30), "/", "", false, true);
            } catch (Exception $e) { /* continue without remember */ }
        }

        header('Location: main.php');
        exit;
    } else {
        $error = 'Невірний пароль!';
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід — Система куратора</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='18' fill='%232e6b57'/><text y='72' x='50' text-anchor='middle' font-size='60' font-family='sans-serif'>🎓</text></svg>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --accent:      #2e6b57;
            --accent-dark: #1f4e3d;
            --accent-lt:   #e4f0eb;
            --border:      #dedad3;
            --text:        #181715;
            --text-2:      #5a5750;
            --text-3:      #9a9690;
            --surface:     #ffffff;
            --bg:          #f0ede8;
            --warn:        #b83232;
            --warn-lt:     #fdf0f0;
            --warn-border: #e8b4b4;
            --ok:          #1e7a4a;
            --ok-lt:       #edf8f2;
            --ok-border:   #a8d9bc;
            --radius:      10px;
            --radius-sm:   6px;
        }

        html, body {
            height: 100%;
            font-family: 'DM Sans', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1.5rem;
        }

        /* ── Card ── */
        .login-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,.07), 0 8px 32px rgba(0,0,0,.07);
            width: 100%;
            max-width: 380px;
            overflow: hidden;
        }

        /* ── Card header ── */
        .login-header {
            background: var(--accent);
            padding: 2rem 2rem 1.75rem;
            text-align: center;
        }
        .login-logo {
            width: 52px; height: 52px;
            background: rgba(255,255,255,.15);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: #fff;
        }
        .login-header h1 {
            font-size: 1.15rem;
            font-weight: 600;
            color: #fff;
            letter-spacing: -.01em;
        }
        .login-header p {
            font-size: .8rem;
            color: rgba(255,255,255,.65);
            margin: .2rem 0 0;
        }

        /* ── Card body ── */
        .login-body { padding: 1.75rem 2rem 2rem; }

        /* ── Alerts ── */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: .6rem;
            padding: .7rem .9rem;
            border-radius: var(--radius-sm);
            font-size: .845rem;
            margin-bottom: 1.1rem;
            line-height: 1.4;
        }
        .alert i { font-size: .9rem; flex-shrink: 0; margin-top: .05rem; }
        .alert-error  { background: var(--warn-lt); color: var(--warn); border: 1px solid var(--warn-border); }
        .alert-success { background: var(--ok-lt); color: var(--ok); border: 1px solid var(--ok-border); }

        /* ── Form fields ── */
        .field { margin-bottom: 1.1rem; }
        .field label {
            display: block;
            font-size: .82rem;
            font-weight: 500;
            color: var(--text-2);
            margin-bottom: .38rem;
        }

        .input-wrap {
            position: relative;
        }
        .input-wrap i {
            position: absolute;
            left: .78rem; top: 50%;
            transform: translateY(-50%);
            color: var(--text-3);
            font-size: .82rem;
            pointer-events: none;
        }
        .input-wrap input {
            width: 100%;
            padding: .55rem .75rem .55rem 2.3rem;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-family: 'DM Sans', sans-serif;
            font-size: .9rem;
            color: var(--text);
            background: var(--surface);
            transition: border-color .15s, box-shadow .15s;
            appearance: none;
        }
        .input-wrap input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-lt);
        }
        .input-wrap input::placeholder { color: var(--text-3); }

        /* ── Remember ── */
        .remember {
            display: flex;
            align-items: center;
            gap: .45rem;
            margin-bottom: 1.4rem;
            cursor: pointer;
        }
        .remember input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: var(--accent);
            cursor: pointer;
        }
        .remember span {
            font-size: .82rem;
            color: var(--text-2);
            user-select: none;
        }

        /* ── Submit ── */
        .btn-login {
            width: 100%;
            padding: .65rem 1rem;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            font-family: 'DM Sans', sans-serif;
            font-size: .92rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s, transform 80ms;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
        }
        .btn-login:hover  { background: var(--accent-dark); }
        .btn-login:active { transform: scale(.98); }

        /* ── Footer ── */
        .login-footer {
            text-align: center;
            margin-top: 1.75rem;
            font-size: .75rem;
            color: var(--text-3);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Header -->
        <div class="login-header">
            <div class="login-logo">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <h1>Система куратора</h1>
            <p>Введіть пароль для входу</p>
        </div>

        <!-- Body -->
        <div class="login-body">

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    Пароль успішно змінено! Увійдіть з новим паролем.
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="field">
                    <label for="password">Пароль</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="Введіть пароль…"
                               required
                               autofocus>
                    </div>
                </div>

                <label class="remember">
                    <input type="checkbox" name="remember">
                    <span>Запам'ятати мене на 30 днів</span>
                </label>

                <button type="submit" class="btn-login">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Увійти
                </button>
            </form>
        </div>
    </div>

    <div class="login-footer">
        &copy; <?= date('Y') ?> Система куратора
    </div>

</body>
</html>