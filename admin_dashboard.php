<?php
session_start();
include __DIR__ . '/db.php';
include __DIR__ . '/header.php';
// التحقق مما إذا كان المستخدم مسجلاً
if (!isset($_SESSION['id']) || $_SESSION['category'] !== 'Admin') {
    header("Location: http://localhost/librairy/login.php");
    exit();
}

// التحقق من الاتصال بقاعدة البيانات
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// جلب الطلبات التي تحتوي على "cour" في حالة الطلب
$stmt = $conn->prepare("SELECT * FROM commande WHERE Status LIKE ? ORDER BY CommandeDate DESC");
$searchTerm = '%cour%';
$stmt->bind_param("s", $searchTerm);

// تنفيذ الاستعلام والتحقق من نجاحه
if (!$stmt->execute()) {
    die("Error in SQL query: " . $stmt->error);
}

$result = $stmt->get_result();
?>

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

} ?>
    <h2 class="mb-4">Gestion des Commandes</h2>

    <?php if ($result->num_rows > 0): ?>
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
                        <td><?= htmlspecialchars($row['CommandeId']) ?></td>
                        <td><?= htmlspecialchars($row['CommandeDate']) ?></td>
                        <td><?= number_format($row['Prix'], 2) ?> MRU</td>
                        <td><?= htmlspecialchars($row['Status']) ?></td>
                        <td><?= htmlspecialchars($row['ClientId']) ?></td>
                        <td>
                            <a href="update_status.php?id=<?= $row['CommandeId'] ?>&status=Confirmée" class="btn btn-success btn-sm">Confirmer</a>
                            <a href="comande_details.php?id=<?= $row['CommandeId'] ?>" class="btn btn-danger btn-sm" >Details</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">Aucune commande trouvée.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
