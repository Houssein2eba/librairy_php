<?php
session_start();
require 'db.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['Name']);
    $description = $_POST['Description'];

    // Check if the category already exists
    $check_stmt = $conn->prepare("SELECT * FROM Category WHERE Name = ?");
    $check_stmt->bind_param("s", $name);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Redirect with an error message if the category already exists
        header("Location: admin_dashboard.php?error=Category already exists");
        exit();
    } else {
        // Insert new category
        $stmt = $conn->prepare("INSERT INTO Category (Name, Description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);

        if ($stmt->execute()) {
            header("Location: admin_dashboard.php?message=Category added successfully");
            exit();
        } else {
            echo "<script>alert('Error adding category');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Add a New Category</h2>
        <?php
        if (isset($_GET['error'])) {
            echo "<div class='alert alert-danger'>" . htmlspecialchars($_GET['error']) . "</div>";
        }
        ?>
        <form action="addCategory.php" method="POST">
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="Name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="Description" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Category</button>
        </form>
    </div>
</body>
</html>
