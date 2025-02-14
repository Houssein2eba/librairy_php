

<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("location: http://localhost/librairy/login.php");
    exit();
}
$id = $_SESSION['id'];

include __DIR__ . '/layout/header.php';
?>

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

<?php 
include __DIR__ . '/layout/footer.php';
?>
