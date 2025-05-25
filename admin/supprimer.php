<?php
session_start();

// Vérifier si l'admin est connecté
if(!isset($_SESSION['admin_connected']) || $_SESSION['admin_connected'] !== true){
    header("Location: ../formulaire/index.php");
    exit();
}

include("../config/commandes.php");
$Produits=afficher();

if(isset($_POST['valider'])){
    if(!empty($_POST['id'])){
        $id=$_POST['id'];
        try{
            supprimer($id);
            $success_message = "Produit supprimé avec succès!";
            // Recharger la liste des produits
            $Produits=afficher();
        }
        catch(Exception $e){
            $error_message = 'Erreur : '.$e->getMessage();
        }
    }else {
        $error_message = 'Vous devez remplir le champ id';
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
                        <p class="text-body-secondary">Bienvenue <?php echo $_SESSION['admin_email']; ?>! Vous pouvez supprimer vos produits depuis cette page.
                        </p>
                    </div> 
                    <div class="col-sm-4 offset-md-1 py-4"> 
                        <h4>Navigation</h4> 
                        <ul class="list-unstyled">
                            <li><a href="../index.php" class="text-white">Accueil</a></li>
                            <li><a href="./ajouter.php" class="text-white">Ajouter Produit</a></li>
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
                            <label for="id" class="form-label">ID du Produit</label>
                            <input type="number" class="form-control" name="id" id="id" required>
                        </div>
                        <button type="submit" class="btn btn-danger" name="valider">Supprimer le Produit</button>
                    </form>
                </div>
                <br>
                <h4>Liste des produits</h4>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3">
                    <?php foreach($Produits as $produit) { ?>
                        <div class="col"> 
                            <div class="card shadow-sm"> 
                                <img src="<?= $produit->image ?>" alt="Produit" class="card-img-top" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">ID: <?= $produit->id ?></h5>
                                    <p class="card-text"><?= $produit->nom ?></p>
                                    <p class="text-success"><?= $produit->prix ?>$</p>
                                    <form method="post" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                        <input type="hidden" name="id" value="<?= $produit->id ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" name="valider">Supprimer</button>
                                    </form>
                                </div>
                            </div> 
                        </div> 
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>