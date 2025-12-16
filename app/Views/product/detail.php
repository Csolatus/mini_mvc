<div style="max-width: 960px; margin: 0 auto; padding: 20px;">
    <a href="/products" style="color: #007bff; text-decoration: none;">← Retour à la liste</a>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 20px;">
        <div style="text-align: center;">
            <?php if (!empty($product['image_url'])): ?>
                <img src="<?= htmlspecialchars($product['image_url']) ?>"
                     alt="<?= htmlspecialchars($product['nom']) ?>"
                     style="max-width: 100%; max-height: 360px; object-fit: contain; border: 1px solid #eee; border-radius: 6px;"
                     onerror="this.style.display='none'">
            <?php else: ?>
                <div style="border: 1px dashed #ccc; padding: 40px; color: #777;">
                    Pas d'image disponible
                </div>
            <?php endif; ?>
        </div>

        <div>
            <h1 style="margin-top: 0;"><?= htmlspecialchars($product['nom']) ?></h1>
            <?php if (!empty($product['description'])): ?>
                <p style="color: #555; line-height: 1.6;"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            <?php endif; ?>

            <div style="font-size: 28px; font-weight: bold; color: #007bff; margin: 12px 0;">
                <?= number_format((float)$product['prix'], 2, ',', ' ') ?> €
            </div>

            <div style="margin: 8px 0; color: #666;">
                Stock : <?= (int)$product['stock'] ?>
            </div>

            <form method="post" action="/cart/add" style="margin-top: 16px; display: flex; gap: 10px; align-items: center;">
                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                <label for="qty" style="color: #555;">Quantité</label>
                <input id="qty" name="quantity" type="number" min="1" value="1" style="width: 80px; padding: 6px;">
                <button type="submit" style="padding: 10px 16px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Ajouter au panier
                </button>
            </form>
        </div>
    </div>
</div>