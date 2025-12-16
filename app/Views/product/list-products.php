<!-- Liste des produits -->
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>Liste des produits</h2>
        <a href="/products/create" class="btn btn-primary">
            ➕ Ajouter un produit
        </a>
    </div>

    <?php if (empty($products)): ?>
        <div class="text-center" style="padding: 40px; background-color: var(--light-bg); border-radius: 4px;">
            <p class="mb-3" style="font-size: 1.1rem; color: var(--secondary-color);">Aucun produit disponible.</p>
            <a href="/products/create" class="btn btn-primary">Créer le premier produit</a>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="card">
                    <!-- Image du produit -->
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

                    <!-- Informations du produit -->
                    <h3 class="card-title">
                        <?= htmlspecialchars($product['nom']) ?>
                    </h3>

                    <?php if (!empty($product['description'])): ?>
                        <p class="card-text">
                            <?= htmlspecialchars($product['description']) ?>
                        </p>
                    <?php endif; ?>

                    <div
                        style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: auto; padding-top: 15px; border-top: 1px solid var(--border-color);">
                        <div>
                            <div class="price-tag">
                                <?= number_format((float) $product['prix'], 2, ',', ' ') ?> €
                            </div>
                            <div class="stock-tag">
                                Stock: <?= htmlspecialchars($product['stock']) ?>
                            </div>
                        </div>
                        <div>
                            <form action="/cart/add" method="POST" style="display: flex; gap: 5px;">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="number" name="quantity" value="1" min="1" class="form-control"
                                    style="width: 60px; padding: 5px;">
                                <button type="submit" class="btn btn-success btn-sm">
                                    Ajouter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="mt-4 text-center">
        <a href="/" class="btn btn-secondary">← Retour à l'accueil</a>
    </div>
</div>