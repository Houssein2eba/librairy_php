<?php
session_start();
include __DIR__ . '/db.php';
include __DIR__ . '/layout/header.php';
// التحقق مما إذا كان المستخدم مسجلاً
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// التحقق من وجود معرف الطلب في الرابط
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin_dashboard.php?message=ID%20invalide.");
    exit();
}

$commandeId = intval($_GET['id']);

// جلب بيانات الطلب والعميل المرتبط به
$query = "
    SELECT c.CommandeId, c.CommandeDate, c.Prix, c.Status,
           cl.ClientId, cl.Nom, cl.Address, cl.DateNaissance, cl.Genre
    FROM commande c
    INNER JOIN client cl ON c.ClientId = cl.ClientId
    WHERE c.CommandeId = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $commandeId);
$stmt->execute();
$commande = $stmt->get_result()->fetch_assoc();
$stmt->close();

// جلب المنتجات المرتبطة بهذا الطلب
$query = "
    SELECT p.ProduitId, p.NomLivre, p.Auteur, p.Image, p.Description, p.Prix
    FROM produits p
    INNER JOIN produits_commandes pc ON p.ProduitId = pc.ProduitId
    WHERE pc.CommandeId = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $commandeId);
$stmt->execute();
$produits = $stmt->get_result();
$stmt->close();
?>



<div class="container mt-5">
    <h2 class="mb-4">Détails de la Commande #<?= htmlspecialchars($commande['CommandeId']) ?></h2>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white">Informations du Client</div>
        <div class="card-body">
            <p><strong>Nom:</strong> <?= htmlspecialchars($commande['Nom']) ?></p>
            <p><strong>Adresse:</strong> <?= htmlspecialchars($commande['Address']) ?></p>
            <p><strong>Date de Naissance:</strong> <?= htmlspecialchars($commande['DateNaissance']) ?></p>
            <p><strong>Genre:</strong> <?= htmlspecialchars($commande['Genre']) ?></p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white">Informations sur la Commande</div>
        <div class="card-body">
            <p><strong>Date:</strong> <?= htmlspecialchars($commande['CommandeDate']) ?></p>
            <p><strong>Prix Total:</strong> <?= number_format($commande['Prix'], 2) ?> MRU</p>
            <p><strong>Statut:</strong> <?= htmlspecialchars($commande['Status']) ?></p>
        </div>
    </div>

    <h3 class="mb-3">Produits Commandés</h3>
    <?php if ($produits->num_rows > 0): ?>
        <div class="row">
            <?php while ($produit = $produits->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <img src="<?= htmlspecialchars($produit['Image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($produit['NomLivre']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($produit['NomLivre']) ?></h5>
                            <p class="card-text"><strong>Auteur:</strong> <?= htmlspecialchars($produit['Auteur']) ?></p>
                            <p class="card-text"><?= htmlspecialchars($produit['Description']) ?></p>
                            <p class="card-text"><strong>Prix:</strong> <?= number_format($produit['Prix'], 2) ?> MRU</p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Aucun produit associé à cette commande.</div>
    <?php endif; ?>
</div>



<?php
$conn->close();
include __DIR__ . '/layout/footer.php';
?>
