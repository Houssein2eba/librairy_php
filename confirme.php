<?php
session_start();
include __DIR__ . '/db.php';

if (!isset($_SESSION['id'])) {
    header("Location: http://localhost/librairy/login.php");
    exit();
}

$ClientId = $_SESSION['id'];
$dateCommande = date("Y-m-d H:i:s"); // تاريخ الطلب

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
$totalStmt->close();

if ($total == 0) {
    die("Votre panier est vide !");
}

// إدراج الطلب في جدول commandes
$insertCommande = $conn->prepare("
    INSERT INTO commande (CommandeDate, Prix, ClientId) 
    VALUES (?, ?, ?)
");
if (!$insertCommande) {
    die("Erreur SQL (Insertion commande): " . $conn->error);
}

$insertCommande->bind_param("sdi", $dateCommande, $total, $ClientId);
$insertCommande->execute();
$CommandeId = $insertCommande->insert_id; // الحصول على ID الطلب الجديد
$insertCommande->close();

// إدراج كل منتج من السلة إلى produits_commandes
$getProducts = $conn->prepare("
    SELECT ProduitId FROM panier WHERE ClientId = ?
");
if (!$getProducts) {
    die("Erreur SQL (Récupération produits du panier): " . $conn->error);
}

$getProducts->bind_param("i", $ClientId);
$getProducts->execute();
$productsResult = $getProducts->get_result();

$insertProduitCommande = $conn->prepare("
    INSERT INTO produits_commandes (ProduitId, CommandeId) 
    VALUES (?, ?)
");
if (!$insertProduitCommande) {
    die("Erreur SQL (Insertion produits_commandes): " . $conn->error);
}

while ($row = $productsResult->fetch_assoc()) {
    $insertProduitCommande->bind_param("ii", $row['ProduitId'], $CommandeId);
    $insertProduitCommande->execute();
}

$insertProduitCommande->close();
$getProducts->close();

// مسح السلة بعد تأكيد الطلب
$truncatePanier = $conn->prepare("DELETE FROM panier WHERE ClientId = ?");
if (!$truncatePanier) {
    die("Erreur SQL (Vider panier): " . $conn->error);
}

$truncatePanier->bind_param("i", $ClientId);
$truncatePanier->execute();
$truncatePanier->close();

$conn->close();

// توجيه المستخدم إلى صفحة النجاح
header("Location: http://localhost/librairy/index.php?message=Commande confirmée !");
exit();
?>
