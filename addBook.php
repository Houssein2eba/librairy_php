<?php
session_start();
require 'db.php'; 
include __DIR__ . '/layout/header.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomLivre = trim($_POST['NomLivre']);
    $auteur = trim($_POST['Auteur']);
    $description = $_POST['Description'];
    $prix = $_POST['Prix'];
    $categoryId = $_POST['CategoryId'];
    $image = trim($_POST['Image']); // Image is a URL

    // Check if the book already exists (same title & author)
    $check_stmt = $conn->prepare("SELECT * FROM produits WHERE NomLivre = ? AND Auteur = ?");
    $check_stmt->bind_param("ss", $nomLivre, $auteur);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Redirect with an error message if the book already exists
        header("Location: admin_dashboard.php?error=Book already exists");
        exit();
    } else {
        // Insert book into database
        $stmt = $conn->prepare("INSERT INTO produits (NomLivre, Auteur, Image, Description, Prix, CategoryId) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdi", $nomLivre, $auteur, $image, $description, $prix, $categoryId);
        
        if ($stmt->execute()) {
            header("Location: admin_dashboard.php?message=Book added successfully");
            exit();
        } else {
            echo "<script>alert('Error adding book');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Add a New Book</h2>
        <?php
        if (isset($_GET['error'])) {
            echo "<div class='alert alert-danger'>" . htmlspecialchars($_GET['error']) . "</div>";
        }
        ?>
        <form action="addBook.php" method="POST">
            <div class="form-group">
                <label>Book Name</label>
                <input type="text" name="NomLivre" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Author</label>
                <input type="text" name="Auteur" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Image URL</label>
                <input type="url" name="Image" class="form-control" required placeholder="https://example.com/image.jpg">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="Description" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="number" step="0.01" name="Prix" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="CategoryId" class="form-control" required>
                    <?php
                    $result = $conn->query("SELECT * FROM Category");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['CategoryId'] . "'>" . $row['Name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Book</button>
        </form>
    </div>
</body>
</html>
