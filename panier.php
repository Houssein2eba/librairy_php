<?php
session_start();
include __DIR__ . '/db.php';

if (!isset($_SESSION['id'])) {
    header("Location: http://localhost/librairy/login.php");
    exit();
}

$ClientId = $_SESSION['id'];

$stmt = $conn->prepare("
    SELECT pa.Id, p.NomLivre, p.Auteur, p.Prix, p.Image 
    FROM panier pa
    JOIN produits p ON pa.ProduitId = p.ProduitId
    WHERE pa.ClientId = ?
");
if (!$stmt) {
    die("Erreur SQL: " . $conn->error);
}

$stmt->bind_param("i", $ClientId);
$stmt->execute();
$result = $stmt->get_result();

// Calculate total cart price
$totalStmt = $conn->prepare("
    SELECT SUM(p.Prix) AS total
    FROM panier pa
    JOIN produits p ON pa.ProduitId = p.ProduitId
    WHERE pa.ClientId = ?
");
if (!$totalStmt) {
    die("Erreur SQL (Total panier): " . $conn->error);
}

$totalStmt->bind_param("i", $ClientId);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalRow = $totalResult->fetch_assoc();
$total = $totalRow['total'] ?? 0;
?>

<?php include __DIR__ . '/layout/header.php'; ?>

<!-- Page wrapper to push footer down -->
<div class="d-flex flex-column min-vh-100">

    <!-- Main content -->
    <div class="container mt-5 flex-grow-1">
        <?php if (isset($_GET["message"])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_GET["message"]); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <h2 class="mb-4">Votre Panier</h2>

        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Produit</th>
                        <th>Auteur</th>
                        <th>Prix</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['NomLivre']); ?></td>
                            <td><?= htmlspecialchars($row['Auteur']); ?></td>
                            <td><?= number_format($row['Prix'], 2); ?> MRU</td>
                            <td><img src="<?= htmlspecialchars($row['Image']); ?>" width="80" height="80" class="img-thumbnail"></td>
                            <td>
                                <a href="http://localhost/librairy/remove_from_panier.php?id=<?= $row['Id']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="text-end">
                <h4>Total: <span class="text-success"><?= number_format($total, 2); ?> MRU</span></h4>
            </div>

            <a href="http://localhost/librairy/confirme.php" class="btn btn-primary mt-3">Confirmer vos achats</a>

        <?php else: ?>
            <div class="alert alert-warning text-center">
                Votre panier est vide. <a href="http://localhost/librairy/index.php" class="btn btn-outline-primary btn-sm">Explorer les produits</a>
            </div>
        <?php endif; ?>
    </div>

    <?php
    $stmt->close();
    $conn->close();
    ?>

    <!-- Include footer -->
    <?php include __DIR__ . '/layout/footer.php'; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
