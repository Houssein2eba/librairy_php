<?php
session_start();
include __DIR__ . '/db.php';

if (!isset($_SESSION['id'])) {
    header("Location: http://localhost/librairy/login.php");
    exit();
}

$ClientId = $_SESSION['id'];



$stmt = $conn->prepare("
    SELECT pa.Id,p.NomLivre, p.Auteur, p.Prix, p.Image 
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


// حساب المجموع الكلي للسلة
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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="http://localhost/librairy/index.php">Book Store</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="http://localhost/librairy/index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="http://localhost/librairy/panier.php">Shop</a></li>
              
            </ul>
        </div>
    </nav>
<div class="container mt-5">
<?php 


if (isset($_GET["message"])) { ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_GET["message"]); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php 
$_GET["message"]=null;
} ?>
    <h2 class="mb-4">Votre Panier</h2>

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
                    <td><?php echo htmlspecialchars($row['NomLivre']); ?></td>
                    <td><?php echo htmlspecialchars($row['Auteur']); ?></td>
                    <td><?php echo number_format($row['Prix'], 2); ?> MRU</td>
                    <td><img src="<?php echo htmlspecialchars($row['Image']); ?>" width="80" height="80" class="img-thumbnail"></td>
                    <td>
                        <a href="http://localhost/librairy/remove_from_panier.php?id=<?php echo $row['Id']; ?>" class="btn btn-danger btn-sm"> Supprimer</a>
                    </td>
                </tr>
                
            <?php endwhile; ?>

        </tbody>
    </table>

    <div class="text-end">
        <h4>Total: <span class="text-success"><?php echo number_format($total, 2); ?> MRU</span></h4>
    </div>

    <a href="http://localhost/librairy/confirme.php" class="btn btn-primary mt-3">Confirmer vos achats</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
