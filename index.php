
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Store</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <style>
        .card-img-top {
    width: 100%;
    height: 300px; /* أو أي ارتفاع يناسبك */
    object-fit: cover; /* لجعل الصورة تغطي الكارد بالكامل دون تشويه */
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

    </style>
</head>
<body>
<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("location: http://localhost/librairy/login.php");
    exit();
}
$id = $_SESSION['id'];

?>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="http://localhost/librairy/index.php">Book Store</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="http://localhost/librairy/panier.php">Shop</a></li>
                <li class="nav-item"><a class="nav-link" href="http://localhost/librairy/commandes.php">Commandes</a></li>
            </ul>
        </div>
    </nav>
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


    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row">
            <?php
            include __DIR__ . '/db.php';
            $sql = "SELECT * FROM produits";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                    <img src="<?php echo $row['Image']; ?>" class="card-img-top" alt="Book Image" style="width: 300px;height: 400px">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['NomLivre']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($row['Auteur']); ?></p>
                            <p class="card-text font-weight-bold"><?php echo $row['Prix']; ?> $</p>
                            <a href="http://localhost/librairy/card.php?idProduit=<?php echo $row['ProduitId']; ?>" class="btn btn-primary">Add to Cart</a>
                        </div>
                    </div>
                </div>
            <?php
                }
            }
            $conn->close();
            ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-4">
        &copy; <?php echo date("Y"); ?> Book Store. All rights reserved.
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
