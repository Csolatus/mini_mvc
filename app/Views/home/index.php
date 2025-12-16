<div class="container">
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 class="mb-1">Mini E-commerce</h1>
            <p class="text-secondary mb-0">Bienvenue sur la boutique.</p>
        </div>
        <nav class="header-nav">
            <a href="/products" class="btn btn-primary">Tous les produits</a>
            <a href="/products/create" class="btn btn-secondary">Créer un produit</a>
        </nav>
    </header>

    <?php if (isset($_GET['order_success'])): ?>
        <div class="alert alert-success">
            ✅ Votre commande a été validée avec succès ! Merci de votre achat.
        </div>
    <?php endif; ?>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <p class="empty-state-text">Aucun produit disponible pour le moment.</p>
            <a href="/products/create" class="btn btn-primary">Ajouter un premier produit</a>
        </div>
    <?php else: ?>
        <h2 class="mb-4">Produits en vedette</h2>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <article class="card">
                    <?php if (!empty($product['image_url'])): ?>
                        <div class="product-image-container">
                            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['nom']) ?>"
                                class="product-image" onerror="this.style.display='none'">
                        </div>
                    <?php else: ?>
                        <div class="product-image-container">
                            <div class="no-image">Pas d'image</div>
                        </div>
                    <?php endif; ?>

                    <h3 class="card-title">
                        <?= htmlspecialchars($product['nom']) ?>
                    </h3>

                    <?php if (!empty($product['description'])): ?>
                        <p class="card-text">
                            <?= htmlspecialchars($product['description']) ?>
                        </p>
                    <?php endif; ?>

                    <div class="card-actions">
                        <div>
                            <div class="price-tag">
                                <?= number_format((float) $product['prix'], 2, ',', ' ') ?> €
                            </div>
                            <div class="stock-tag">
                                Stock: <?= htmlspecialchars($product['stock']) ?>
                            </div>
                        </div>
                        <div class="add-to-cart-section">
                            <form action="/cart/add" method="POST" class="add-to-cart-form">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="number" name="quantity" value="1" min="1" class="form-control"
                                    style="width: 60px; padding: 5px;">
                                <button type="submit" class="btn btn-success btn-sm">
                                    Ajouter
                                </button>
                            </form>
                        </div>
                    </div>

                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>