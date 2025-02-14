<?php
session_start();
include __DIR__ . '/db.php';

if (!isset($_SESSION['id'])) {
    header("Location: http://localhost/librairy/login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Erreur: ID du panier est invalide !");
}

$Id = intval($_GET['id']);

// حذف المنتج من السلة باستخدام Id الخاص به في جدول panier
$stmt = $conn->prepare("DELETE FROM panier WHERE Id=?");
if (!$stmt) {
    die("Erreur SQL (Suppression du panier): " . $conn->error);
}

$stmt->bind_param("i", $Id);
$stmt->execute();
$stmt->close();
$conn->close();

// إعادة التوجيه إلى صفحة السلة بعد الحذف
header("Location: panier.php?message=Produit supprimé !");
exit();
?>
