<?php
session_start();
if(!isset($_SESSION['id'])) {
    header("location: http://localhost/librairy/login.php");
    exit();
}
include __DIR__ . '/db.php';
$idClient = $_SESSION['id'];
$idProduit = $_GET['idProduit'];

//insert into panier
$stmt=$conn->prepare("INSERT INTO panier (ClientId,ProduitId) VALUES (?,?)");
if (!$stmt) {
    die("Error in SQL statement (panier insert): " . $conn->error);
}

$stmt->bind_param("ii", $idClient, $idProduit);
$stmt->execute();
$stmt->close();

header("Location: http://localhost/librairy/index.php?message=Produit ajouté au panier");
exit();



?>