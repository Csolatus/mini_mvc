<!doctype html>
<!-- Définit la langue du document -->
<html lang="fr">
<!-- En-tête du document HTML -->

<head>
    <!-- Déclare l'encodage des caractères -->
    <meta charset="utf-8">
    <!-- Configure le viewport pour les appareils mobiles -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Définit le titre de la page avec échappement -->
    <title><?= isset($title) ? htmlspecialchars($title) : 'App' ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<!-- Corps du document -->

<body>
    <?php
    // Détermine la page active pour la navigation
    $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
    $isHome = ($currentPath === '/');
    $isProducts = ($currentPath === '/products');
    $isProductsCreate = ($currentPath === '/products/create');
    $isUsersCreate = ($currentPath === '/users/create');
    $isLogin = ($currentPath === '/login');
    $currentUser = $_SESSION['user'] ?? null;
    ?>
    <!-- En-tête de la page -->
    <header class="main-header">
        <div class="header-container">
            <!-- Section Gauche : Logo + Menu Principal -->
            <div style="display: flex; align-items: center; gap: 30px;">
                <h1 class="brand-title">
                    <a href="/" class="brand-link">Mini MVC</a>
                </h1>

                <nav>
                    <ul class="nav-list">
                        <li>
                            <a href="/" class="nav-link <?= $isHome ? 'active' : '' ?>">
                                🏠 Accueil
                            </a>
                        </li>
                        <li>
                            <a href="/products" class="nav-link <?= $isProducts ? 'active' : '' ?>">
                                📦 Produits
                            </a>
                        </li>
                        <li>
                            <a href="/products/create" class="nav-link <?= $isProductsCreate ? 'active' : '' ?>">
                                ➕ Ajouter
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Section Droite : Panier + User -->
            <nav>
                <ul class="nav-list">
                    <?php if (!$currentUser): ?>
                        <li>
                            <a href="/cart" class="nav-link" title="Panier">🛒 Panier</a>
                        </li>
                        <li>
                            <a href="/login" class="nav-link <?= $isLogin ? 'active' : '' ?>">
                                🔑 Connexion
                            </a>
                        </li>
                        <li>
                            <a href="/users/create" class="nav-link <?= $isUsersCreate ? 'active' : '' ?>">
                                👤 Inscription
                            </a>
                        </li>
                    <?php else: ?>
                        <li style="color: rgba(255,255,255,0.8); padding: 8px 12px; font-weight: 500;">
                            Bonjour, <?= htmlspecialchars($currentUser['nom'] ?? $currentUser['email']) ?>
                        </li>
                        <li>
                            <a href="/cart" class="nav-link" title="Panier">🛒 Panier</a>
                        </li>
                        <li>
                            <a href="/orders" class="nav-link">📦 Commandes</a>
                        </li>
                        <li>
                            <button id="logout-btn" class="btn btn-danger btn-sm"
                                style="padding: 6px 12px; font-size: 0.9em;">
                                Se déconnecter
                            </button>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <!-- Zone de contenu principal -->
    <main class="container">
        <!-- Insère le contenu rendu de la vue -->
        <?= $content ?>

    </main>
    <!-- Fin du corps de la page -->
</body>
<script>
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', async () => {
            logoutBtn.disabled = true;
            logoutBtn.textContent = 'Déconnexion...';
            try {
                const response = await fetch('/logout', { method: 'POST' });
                if (response.ok) {
                    window.location.href = '/';
                } else {
                    alert('Échec de la déconnexion.');
                    logoutBtn.disabled = false;
                    logoutBtn.textContent = 'Se déconnecter';
                }
            } catch (error) {
                alert('Erreur réseau lors de la déconnexion.');
                logoutBtn.disabled = false;
                logoutBtn.textContent = 'Se déconnecter';
            }
        });
    }
</script>
<!-- Fin du document HTML -->

</html>