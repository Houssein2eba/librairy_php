<?php
session_start();
include __DIR__ . '/db.php';

// التحقق مما إذا كان المستخدم مسجل الدخول
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// التحقق من تمرير القيم المطلوبة عبر GET
if (isset($_GET['id']) && isset($_GET['status'])) {
    $commandeId = intval($_GET['id']);
    $newStatus = $_GET['status'];

    // تأمين الإدخال
    $allowedStatuses = ['Confirmée'];
    if (!in_array($newStatus, $allowedStatuses)) {
        die("Statut non valide!");
    }
  $newStatus='expédiée';
    // تحديث الحالة في قاعدة البيانات
    $stmt = $conn->prepare("UPDATE commande SET status = ? WHERE CommandeId = ?");
    if ($stmt) {
        $stmt->bind_param("si", $newStatus, $commandeId);
        if ($stmt->execute()) {
            header("Location: admin_dashboard.php?message=Statut mis à jour");
            exit();
        } else {
            die("Erreur lors de la mise à jour du statut.");
        }
    } else {
        die("Erreur de requête SQL.");
    }
} else {
    die("Données invalides.");
}


?>
