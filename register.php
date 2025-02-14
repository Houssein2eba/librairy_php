<?php
session_start();
include __DIR__ . '/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $nom = trim($_POST['nom']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $adresse = trim($_POST['adresse']);
    $dateNaissance = trim($_POST['date_naissance']);
    $genre = trim($_POST['genre']);
    $category = "user"; // افتراضيًا، المستخدم الجديد ليس مديرًا

    // التحقق من إدخال البيانات
    if (empty($nom) || empty($password) || empty($confirmPassword) || empty($adresse) || empty($dateNaissance) || empty($genre)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // التحقق من عدم وجود اسم المستخدم مسبقًا
        $stmt = $conn->prepare("SELECT ClientId FROM client WHERE Nom = ?");
        if (!$stmt) {
            die("Error in SQL statement (check user exists): " . $conn->error);
        }
        $stmt->bind_param("s", $nom);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username already exists. Choose another.";
            $stmt->close();
        } else {
            $stmt->close();

            // تشفير كلمة المرور
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // إدراج البيانات في جدول `client`
            $stmt = $conn->prepare("INSERT INTO client (Nom, Address, DateNaissance, Genre) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                die("Error in SQL statement (client insert): " . $conn->error);
            }
            $stmt->bind_param("ssss", $nom, $adresse, $dateNaissance, $genre);
            $stmt->execute();
            $clientId = $stmt->insert_id;
            $stmt->close();

            // إدراج البيانات في جدول `login` مع `category`
            $stmt = $conn->prepare("INSERT INTO login (password, ClientId, category) VALUES (?, ?, ?)");
            if (!$stmt) {
                die("Error in SQL statement (login insert): " . $conn->error);
            }
            $stmt->bind_param("sis", $hashedPassword, $clientId, $category);
            $stmt->execute();
            $stmt->close();

            // بدء الجلسة
            session_regenerate_id(true);
            $_SESSION['id'] = $clientId;
            $_SESSION['category'] = $category;

            // إعادة توجيه المستخدم بناءً على `category`
            if ($category === 'Admin') {
                header("Location: http://localhost/librairy/admin_dashboard.php");
            } else {
                header("Location: http://localhost/librairy/index.php");
            }
            exit();
        }
    }
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h3>Register</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)) { ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php } ?>
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="nom">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>
                            <div class="form-group">
                                <label for="adresse">Adresse</label>
                                <input type="text" class="form-control" id="adresse" name="adresse" required>
                            </div>
                            <div class="form-group">
                                <label for="date_naissance">Date de Naissance</label>
                                <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                            </div>
                            <div class="form-group">
                                <label for="genre">Genre</label>
                                <select class="form-control" id="genre" name="genre" required>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                    <option value="O">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary btn-block">Register</button>
                        </form>
                        <div class="mt-3 text-center">
                            <a  href="login.php">Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
