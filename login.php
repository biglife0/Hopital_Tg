<?php
require_once '../includes/config.php';

// Rediriger si déjà connecté
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean_input($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_name'] = $user['full_name'];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Nom d\'utilisateur ou mot de passe incorrect.';
            }
        } catch (PDOException $e) {
            $error = 'Erreur de connexion. Veuillez réessayer.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Hôpital Saint-Anténor</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --cream: #F5F0E8;
            --deep: #1A1A2E;
            --navy: #16213E;
            --gold: #C9A84C;
            --gold-light: #E8C97A;
            --red: #8B1A1A;
            --text-light: #8A8A9A;
            --white: #FFFFFF;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            background: var(--deep);
            overflow: hidden;
        }

        /* LEFT PANEL */
        .left-panel {
            width: 55%;
            position: relative;
            background: var(--navy);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background: 
                radial-gradient(ellipse at 20% 50%, rgba(201,168,76,0.08) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(139,26,26,0.12) 0%, transparent 50%);
        }

        .grid-lines {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(rgba(201,168,76,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(201,168,76,0.04) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        .cross-mark {
            position: absolute;
            top: 3rem;
            left: 4rem;
            width: 48px;
            height: 48px;
        }
        .cross-mark::before, .cross-mark::after {
            content: '';
            position: absolute;
            background: var(--gold);
            border-radius: 2px;
        }
        .cross-mark::before { width: 4px; height: 48px; left: 22px; top: 0; }
        .cross-mark::after  { width: 48px; height: 4px; left: 0; top: 18px; }

        .panel-content {
            position: relative;
            z-index: 1;
        }

        .hospital-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 3.2rem;
            font-weight: 300;
            color: var(--white);
            line-height: 1.15;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }

        .hospital-name em {
            font-style: italic;
            color: var(--gold);
        }

        .hospital-subtitle {
            font-size: 0.75rem;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--text-light);
            margin-bottom: 3rem;
        }

        .divider {
            width: 60px;
            height: 1px;
            background: linear-gradient(90deg, var(--gold), transparent);
            margin-bottom: 3rem;
        }

        .quote {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.15rem;
            font-style: italic;
            font-weight: 300;
            color: rgba(255,255,255,0.45);
            line-height: 1.8;
            max-width: 380px;
        }

        .bottom-tag {
            position: absolute;
            bottom: 3rem;
            left: 4rem;
            font-size: 0.68rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: rgba(201,168,76,0.4);
        }

        /* DECORATIVE CIRCLE */
        .deco-circle {
            position: absolute;
            right: -80px;
            top: 50%;
            transform: translateY(-50%);
            width: 320px;
            height: 320px;
            border-radius: 50%;
            border: 1px solid rgba(201,168,76,0.15);
        }
        .deco-circle::after {
            content: '';
            position: absolute;
            inset: 24px;
            border-radius: 50%;
            border: 1px solid rgba(201,168,76,0.08);
        }

        /* RIGHT PANEL */
        .right-panel {
            width: 45%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--cream);
            padding: 3rem;
            position: relative;
        }

        .right-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light), var(--gold));
        }

        .login-box {
            width: 100%;
            max-width: 360px;
        }

        .login-label {
            font-size: 0.7rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }

        .login-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.4rem;
            font-weight: 600;
            color: var(--deep);
            margin-bottom: 0.25rem;
        }

        .login-desc {
            font-size: 0.82rem;
            color: var(--text-light);
            margin-bottom: 2.5rem;
        }

        .error-msg {
            background: rgba(139,26,26,0.08);
            border-left: 3px solid var(--red);
            color: var(--red);
            padding: 0.75rem 1rem;
            font-size: 0.82rem;
            border-radius: 0 6px 6px 0;
            margin-bottom: 1.5rem;
        }

        .field-group {
            margin-bottom: 1.4rem;
        }

        .field-label {
            display: block;
            font-size: 0.72rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--deep);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .field-wrap {
            position: relative;
        }

        .field-wrap i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 0.85rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .field-wrap input {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.75rem;
            border: 1.5px solid #DDD8CF;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            color: var(--deep);
            background: var(--white);
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .field-wrap input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,168,76,0.12);
        }

        .field-wrap input:focus + i,
        .field-wrap:focus-within i {
            color: var(--gold);
        }

        /* Fix icon order in HTML: icon after input for CSS sibling trick */
        .field-wrap input:focus ~ i { color: var(--gold); }

        .btn-login {
            width: 100%;
            padding: 0.95rem;
            background: var(--deep);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 0.5rem;
            position: relative;
            overflow: hidden;
            transition: background 0.25s, transform 0.15s;
        }

        .btn-login::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
            transform: scaleX(0);
            transition: transform 0.3s;
        }

        .btn-login:hover {
            background: #0D0D20;
            transform: translateY(-1px);
        }

        .btn-login:hover::after { transform: scaleX(1); }
        .btn-login:active { transform: translateY(0); }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.8rem;
            color: var(--text-light);
            text-decoration: none;
            transition: color 0.2s;
        }
        .back-link:hover { color: var(--gold); }
        .back-link i { margin-right: 4px; }

        /* ANIMATIONS */
        .left-panel, .right-panel {
            animation: fadeIn 0.6s ease both;
        }
        .right-panel { animation-delay: 0.15s; }

        .login-box > * {
            animation: slideUp 0.5s ease both;
        }
        .login-box > *:nth-child(1) { animation-delay: 0.2s; }
        .login-box > *:nth-child(2) { animation-delay: 0.25s; }
        .login-box > *:nth-child(3) { animation-delay: 0.3s; }
        .login-box > *:nth-child(4) { animation-delay: 0.35s; }
        .login-box > *:nth-child(5) { animation-delay: 0.4s; }
        .login-box > *:nth-child(6) { animation-delay: 0.45s; }

        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .left-panel { width: 100%; padding: 3rem 2rem 2rem; min-height: 220px; }
            .right-panel { width: 100%; padding: 2.5rem 1.5rem; }
            .hospital-name { font-size: 2.2rem; }
            .deco-circle { display: none; }
        }
    </style>
</head>
<body>

    <!-- LEFT PANEL -->
    <div class="left-panel">
        <div class="grid-lines"></div>
        <div class="cross-mark"></div>
        <div class="deco-circle"></div>

        <div class="panel-content">
            <p class="hospital-subtitle">Espace Administration</p>
            <h1 class="hospital-name">Hôpital<br><em>Saint-Anténor</em></h1>
            <div class="divider"></div>
            <p class="quote">« La santé est un état de complet bien-être physique, mental et social. »</p>
        </div>

        <div class="bottom-tag">Système de gestion interne — 2026</div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right-panel">
        <div class="login-box">

            <p class="login-label">Connexion sécurisée</p>
            <h2 class="login-title">Bienvenue</h2>
            <p class="login-desc">Accédez à votre espace administrateur.</p>

            <?php if ($error): ?>
                <div class="error-msg"><i class="fas fa-exclamation-circle" style="margin-right:6px"></i><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="field-group">
                    <label class="field-label" for="username">Nom d'utilisateur</label>
                    <div class="field-wrap">
                        <input type="text" id="username" name="username" placeholder="Tague" required autofocus>
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="password">Mot de passe</label>
                    <div class="field-wrap">
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt" style="margin-right:8px"></i>Se connecter
                </button>
            </form>

            <a href="../index.php" class="back-link">
                <i class="fas fa-arrow-left"></i>Retour au site principal
            </a>

        </div>
    </div>

</body>
</html>