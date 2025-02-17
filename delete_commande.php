<?php
session_start();
include __DIR__ . '/db.php';

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

// تشغيل المعاملات لضمان الاتساق
$conn->begin_transaction();

try {
    // حذف المنتجات المرتبطة بهذا الطلب
    $stmt1 = $conn->prepare("DELETE FROM produits_commandes WHERE CommandeId = ?");
    $stmt1->bind_param("i", $commandeId);
    $stmt1->execute();
    $stmt1->close();

    // حذف الطلب نفسه
    $stmt2 = $conn->prepare("DELETE FROM commande WHERE CommandeId = ?");
    $stmt2->bind_param("i", $commandeId);
    $stmt2->execute();
    $stmt2->close();

    // تأكيد الحذف
    $conn->commit();
    $message = "Commande et ses produits supprimés avec succès.";
} catch (Exception $e) {
    // في حالة الخطأ، يتم التراجع عن الحذف
    $conn->rollback();
    $message = "Erreur lors de la suppression: " . $e->getMessage();
}

// إغلاق الاتصال وإعادة التوجيه مع رسالة تأكيد
$conn->close();
header("Location: http://localhost/librairy/index.php?message=" . urlencode($message));
exit();
?>
