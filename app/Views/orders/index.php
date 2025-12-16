<div class="container">
    <h2 class="mb-4">Mes Commandes</h2>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">
            Vous n'avez pas encore passé de commande.
        </div>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Commande</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= $order['id'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                        <td>
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
                            <span class="badge <?= $badgeClass ?>">
                                <?= htmlspecialchars($label) ?>
                            </span>
                        </td>
                        <td style="font-weight: bold;"><?= number_format($order['total_amount'], 2) ?> €</td>
                        <td>
                            <a href="/orders/<?= $order['id'] ?>" class="btn btn-primary btn-sm">Voir détails</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>