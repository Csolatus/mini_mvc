<div class="container">
    <h2 class="mb-4">Votre Panier</h2>

    <?php if (empty($cartItems)): ?>
        <p class="mb-3">Votre panier est vide.</p>
        <a href="/products" class="btn btn-primary">Voir les produits</a>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $id => $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= number_format($item['price'], 2) ?> €</td>
                        <td>
                            <?= $item['quantity'] ?>
                        </td>
                        <td><?= number_format($item['price'] * $item['quantity'], 2) ?> €</td>
                        <td>
                            <form action="/cart/remove" method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Retirer
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right">Total :</td>
                    <td style="font-weight: bold;"><?= number_format($total, 2) ?> €</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <div class="text-right mt-4">
            <a href="/products" class="btn btn-secondary" style="margin-right: 15px;">Continuer mes achats</a>
            <form action="/checkout" method="POST" style="display:inline;">
                <button type="submit" class="btn btn-success">
                    Valider la commande
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>