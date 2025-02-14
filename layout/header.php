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
    height: 300px; 
    object-fit: cover; 
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

    </style>
</head>
<body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <?php if($_SESSION['category'] == 'Admin'){?>
        <a class="navbar-brand" href="http://localhost/librairy/admin_dashboard.php">Book Store</a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="http://localhost/librairy/logout.php">logout</a></li>
        </ul>
                <?php }else {?>
        <a class="navbar-brand" href="http://localhost/librairy/index.php">Book Store</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="http://localhost/librairy/index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="http://localhost/librairy/panier.php">Shop</a></li>
                <li class="nav-item"><a class="nav-link" href="http://localhost/librairy/commandes.php">Commandes</a></li>
                <li class="nav-item"><a class="nav-link" href="http://localhost/librairy/logout.php">Logout</a></li>
            </ul>
            
        </div>
            <?php } ?>
    </nav>