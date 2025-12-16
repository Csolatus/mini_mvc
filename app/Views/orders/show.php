<div class="container">
    <div class="mb-4">
        <a href="/orders" class="btn btn-secondary">&larr; Retour à mes commandes</a>
    </div>

    <div class="card">
        <div
            style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 15px;">
            <h2 class="mb-0">Commande #<?= $order['id'] ?></h2>
            <span class="text-secondary"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
        </div>

        <div class="mb-4">
            <?php
            $statusLabels = [
                'pending' => 'En attente',
                'paid' => 'Payé',
                'shipped' => 'Expédié',
                'cancelled' => 'Annulé'
            ];
            $statusClasses = [
                'pending' => 'badge-warning',
                'paid' => 'badge-success',
                'shipped' => 'badge-primary',
                'cancelled' => 'badge-danger'
            ];
            $label = $statusLabels[$order['status']] ?? $order['status'];
            $badgeClass = $statusClasses[$order['status']] ?? 'badge-secondary';
            ?>
            <strong>Statut :</strong>
            <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($label) ?></span>
        </div>

        <h3 class="mb-3">Articles</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th class="text-right">Total ligne</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= number_format($item['price'], 2) ?> €</td>
                        <td><?= $item['quantity'] ?></td>
                        <td class="text-right">
                            <?= number_format($item['price'] * $item['quantity'], 2) ?> €
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right" style="font-weight: bold; font-size: 1.1em;">Total Commande :
                    </td>
                    <td class="text-right" style="font-weight: bold; font-size: 1.2em; color: var(--success-color);">
                        <?= number_format($order['total_amount'], 2) ?> €
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>