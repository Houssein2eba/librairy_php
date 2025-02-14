<?php
session_start();
include __DIR__ . '/db.php';

// التحقق مما إذا كان المستخدم مسؤولاً
if (!isset($_SESSION['id']) ) {
    header("Location: login.php");
    exit();
}

// جلب جميع الطلبات
$stmt = $conn->prepare("SELECT * FROM commande ORDER BY CommandeDate DESC");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Gestion des Commandes</h2>
    
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Prix</th>
                <th>Status</th>
                <th>Client ID</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['CommandeId']; ?></td>
                    <td><?php echo $row['CommandeDate']; ?></td>
                    <td><?php echo number_format($row['Prix'], 2); ?> MRU</td>
                    <td><?php echo $row['Status']; ?></td>
                    <td><?php echo $row['ClientId']; ?></td>
                    <td>
                        <a href="update_status.php?id=<?php echo $row['CommandeId']; ?>&status=Confirmée" class="btn btn-success btn-sm">Confirmer</a>
                        <a href="update_status.php?id=<?php echo $row['CommandeId']; ?>&status=Annulée" class="btn btn-warning btn-sm">Annuler</a>
                        <a href="delete_commande.php?id=<?php echo $row['CommandeId']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
