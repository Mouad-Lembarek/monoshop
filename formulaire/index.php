<?php
session_start();
include("../config/formulaire.php");

// Vérifier si l'admin est déjà connecté
if(isset($_SESSION['admin_connected']) && $_SESSION['admin_connected'] === true){
    header("Location: ../admin/ajouter.php");
    exit();
}

if(isset($_POST['envoyer'])){
    if(!empty($_POST['email']) &&  !empty($_POST['password'])){
        $passwd = $_POST['password'];
        $email = $_POST['email'];
        $admin = afficher($email,$passwd);
        if($admin){
            // Créer la session
            $_SESSION['admin_connected'] = true;
            $_SESSION['admin_id'] = $admin->id;
            $_SESSION['admin_email'] = $admin->email;
            
            echo "Connexion reussite";
            header("Location: ../admin/ajouter.php");
            exit();
        }else {
            $error_message = "Identifiants Incorrects";
        }
    }else{
        $error_message = "Il faut remplir tous les champs afin de se connecter";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div class="login-container">
        <?php if(isset($error_message)): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <h1 style="text-align: center; color: #333; margin-bottom: 20px;">Login</h1>
        
        <form id="loginForm" action="" method="post">
            <div class="profile-img">
                <img src="../monoshop.png" alt="MonoShop Logo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            </div>
                    
            <div class="input-group">
                <span class="input-icon">✉️</span>
                <input type="email" name="email" id="email" placeholder="Email ID" required>
            </div>
                    
            <div class="input-group">
                <span class="input-icon">🔒</span>
                <input type="password" name="password" id="password" placeholder="Password" required>
            </div>
            <div class="options">
                <div class="remember">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>
                <div class="forgot">
                    <a href="#">Forgot Password?</a>
                </div>
            </div>
            <input type="submit" name="envoyer" class="login-btn" value="LOGIN">
        </form>
    </div>
</body>
</html>