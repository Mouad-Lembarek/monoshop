<?php
session_start();

// Vérifier si l'admin est connecté
if(!isset($_SESSION['admin_connected']) || $_SESSION['admin_connected'] !== true){
    header("Location: ../formulaire/index.php");
    exit();
}

include('../config/commandes.php');

if(isset($_POST['valider'])){
    if(!empty($_POST['image']) and !empty($_POST['nom']) and !empty($_POST['prix']) and !empty($_POST['desc'])){
        $image=$_POST['image'];
        $nom=$_POST['nom'];
        $prix=$_POST['prix'];
        $desc=$_POST['desc'];
        try{
            ajouter($nom,$image,$prix,$desc);
            $success_message = "Produit ajouté avec succès!";
        }
        catch(Exception $e){
            $error_message = 'Erreur : '.$e->getMessage();
        }
    }else {
        $error_message = 'Vous devez remplir tous les champs';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrateur</title>
    <link rel="stylesheet" href="./style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>
<body>
    <header data-bs-theme="dark"> 
        <div class="collapse text-bg-dark" id="navbarHeader">
            <div class="container">
                <div class="row"> 
                    <div class="col-sm-8 col-md-7 py-4"> 
                        <h4>Panel Administrateur</h4>
                        <p class="text-body-secondary">Bienvenue <?php echo $_SESSION['admin_email']; ?>! Vous pouvez gérer vos produits depuis ce panel.
                        </p>
                    </div> 
                    <div class="col-sm-4 offset-md-1 py-4"> 
                        <h4>Navigation</h4> 
                        <ul class="list-unstyled">
                            <li><a href="../index.php" class="text-white">Accueil</a></li>
                            <li><a href="./supprimer.php" class="text-white">Supprimer Produit</a></li>
                            <li><a href="./modifier.php" class="text-white">Modifier Produit</a></li>
                            <li><a href="./logout.php" class="text-white">Déconnexion</a></li>
                        </ul> 
                    </div> 
                </div> 
            </div> 
        </div> 
        <div class="navbar navbar-dark bg-dark shadow-sm"> 
            <div class="container"> 
                <a href="#" class="navbar-brand d-flex align-items-center">   
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="me-2" viewBox="0 0 24 24">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z">
                        </path>
                        <circle cx="12" cy="13" r="4"></circle>
                    </svg> 
                    <strong>MonoShop - Admin</strong> 
                </a> 
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation"> 
                    <span class="navbar-toggler-icon"></span>
                </button> 
            </div> 
        </div> 
    </header> 
    <main>
        <div class="album py-5 bg-body-tertiary"> 
            <div class="container"> 
                <?php if(isset($success_message)): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if(isset($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <form method="post">
                        <div class="mb-3">
                            <label for="image" class="form-label">Lien de l'image</label>
                            <input type="url" class="form-control" name="image" id="image" required>
                        </div>
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom du produit</label>
                            <input type="text" class="form-control" name="nom" id="nom" required>
                        </div>
                        <div class="mb-3">
                            <label for="prix" class="form-label">Prix</label>
                            <input type="number" step="0.01" class="form-control" name="prix" id="prix" required>
                        </div>
                        <div class="mb-3">
                            <label for="desc" class="form-label">Description</label>
                            <textarea class="form-control" name="desc" id="desc" rows="4" required></textarea>
                        </div>
                
                        <button type="submit" class="btn btn-primary" name="valider">Ajouter un produit</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>