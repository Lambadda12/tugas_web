<?php
session_start();
include '../config/db_connect.php';

// Check if admin is already logged in
if(isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

$email = '';
$password = '';
$errors = ['email' => '', 'password' => '', 'login' => ''];

if(isset($_POST['submit'])) {
    // Validate email
    if(empty($_POST['email'])) {
        $errors['email'] = 'Email is required';
    } else {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
    }
    
    // Validate password
    if(empty($_POST['password'])) {
        $errors['password'] = 'Password is required';
    } else {
        $password = mysqli_real_escape_string($conn, $_POST['password']);
    }
    
    // If no validation errors, attempt login
    if(!array_filter($errors)) {
        // For simplicity, we'll use a hardcoded admin account
        // In a real application, you would check against admin users in the database
        if($email === 'admin@example.com' && $password === 'admin123') {
            // Login successful
            $_SESSION['admin_id'] = 1;
            $_SESSION['admin_name'] = 'Admin';
            
            header('Location: index.php');
            exit();
        } else {
            $errors['login'] = 'Invalid email or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Hotel Reservation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Admin Login</h4>
                    </div>
                    <div class="card-body">
                        <?php if($errors['login']): ?>
                            <div class="alert alert-danger">
                                <?php echo $errors['login']; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="login.php" method="POST">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control <?php echo $errors['email'] ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo $email; ?>">
                                <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control <?php echo $errors['password'] ? 'is-invalid' : ''; ?>" id="password" name="password">
                                <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                        <p class="mt-3 text-center">
                            <a href="../index.php">Back to Website</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
