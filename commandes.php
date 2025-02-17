<?php
session_start();
include __DIR__ . '/db.php';
include __DIR__ . '/layout/header.php';
// التحقق مما إذا كان العميل مسجلاً الدخول
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$clientId = $_SESSION['id'];

// التحقق من الاتصال بقاعدة البيانات
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// جلب الطلبات الخاصة بالعميل الحالي
$stmt = $conn->prepare("SELECT * FROM commande WHERE ClientId = ? ORDER BY CommandeDate DESC");
$stmt->bind_param("i", $clientId);

// تنفيذ الاستعلام والتحقق من نجاحه
if (!$stmt->execute()) {
    die("Error in SQL query: " . $stmt->error);
}

$result = $stmt->get_result();

?>
<div class="d-flex flex-column min-vh-100">
<div class="container mt-5">
    <h2 class="mb-4">Mes Commandes</h2>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Prix</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['CommandeId']) ?></td>
                        <td><?= htmlspecialchars($row['CommandeDate']) ?></td>
                        <td><?= number_format($row['Prix'], 2) ?> MRU</td>
                        <td><?= htmlspecialchars($row['Status']) ?></td>
                        <td> 
                        <a href="http://localhost/librairy/delete_commande.php?id=<?= $row['CommandeId'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">Vous n'avez aucune commande pour le moment.</div>
    <?php endif; ?>
</div>



<?php
$stmt->close();
$conn->close();

include __DIR__ . '/layout/footer.php';
?>
