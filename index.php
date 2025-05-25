<?php
    session_start();
    include("config/commandes.php");
    $Produits=afficher();
    
    // Initialiser le panier s'il n'existe pas
    if(!isset($_SESSION['panier'])){
        $_SESSION['panier'] = array();
    }
    
    // Gérer l'ajout au panier
    if(isset($_POST['ajouter_panier'])){
        $produit_id = $_POST['produit_id'];
        $produit_nom = $_POST['produit_nom'];
        $produit_prix = $_POST['produit_prix'];
        $produit_image = $_POST['produit_image'];
        
        // Vérifier si le produit existe déjà dans le panier
        $produit_existe = false;
        foreach($_SESSION['panier'] as &$item){
            if($item['id'] == $produit_id){
                $item['quantite']++;
                $produit_existe = true;
                break;
            }
        }
        
        // Si le produit n'existe pas, l'ajouter
        if(!$produit_existe){
            $_SESSION['panier'][] = array(
                'id' => $produit_id,
                'nom' => $produit_nom,
                'prix' => $produit_prix,
                'image' => $produit_image,
                'quantite' => 1
            );
        }
    }
    
    // Calculer le nombre total d'articles dans le panier
    $total_articles = 0;
    foreach($_SESSION['panier'] as $item){
        $total_articles += $item['quantite'];
    }
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
    <head>
        <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content=""><meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Astro v5.7.10">
        <title>MonoShop - Boutique en ligne</title>
        <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/album/">
        <script src="/docs/5.3/assets/js/color-modes.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
        <link rel="apple-touch-icon" href="/docs/5.3/assets/img/favicons/apple-touch-icon.png"sizes="180x180">
        <link rel="icon" href="/docs/5.3/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
        <link rel="icon" href="/docs/5.3/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
        <link rel="manifest" href="/docs/5.3/assets/img/favicons/manifest.json">
        <link rel="mask-icon" href="/docs/5.3/assets/img/favicons/safari-pinned-tab.svg" color="#712cf9">
        <link rel="icon" href="/docs/5.3/assets/img/favicons/favicon.ico">

        <meta name="theme-color" content="#712cf9">
        <style>
            .bd-placeholder-img{
                font-size:1.125rem;text-anchor:middle;
                -webkit-user-select:none;
                -moz-user-select:none;
                user-select:none}
            @media (min-width: 768px){
                .bd-placeholder-img-lg{font-size:3.5rem}
                }
                .b-example-divider{
                    width:100%;
                    height:3rem;
                    background-color:#0000001a;border:solid rgba(0,0,0,.15);
                    border-width:1px 0;box-shadow:inset 0 .5em 1.5em #0000001a,inset 0 .125em .5em #00000026}
                    .b-example-vr{flex-shrink:0;width:1.5rem;height:100vh}
                    .bi{vertical-align:-.125em;fill:currentColor}
                    .nav-scroller{position:relative;z-index:2;height:2.75rem;overflow-y:hidden}
                    .nav-scroller .nav{
                        display:flex;
                        flex-wrap:nowrap;
                        padding-bottom:1rem;
                        margin-top:-1px;
                        overflow-x:auto;
                        text-align:center;
                        white-space:nowrap;
                        -webkit-overflow-scrolling:touch
                        }
                        .btn-bd-primary{--bd-violet-bg: #712cf9;
                        --bd-violet-rgb: 112.520718, 44.062154, 249.437846;
                        --bs-btn-font-weight: 600;
                        --bs-btn-color: var(--bs-white);
                        --bs-btn-bg: var(--bd-violet-bg);
                        --bs-btn-border-color: var(--bd-violet-bg);
                        --bs-btn-hover-color: var(--bs-white);--bs-btn-hover-bg: #6528e0;
                        --bs-btn-hover-border-color: #6528e0;
                        --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);--bs-btn-active-color: var(--bs-btn-hover-color);
                        --bs-btn-active-bg: #5a23c8;--bs-btn-active-border-color: #5a23c8}
                        .bd-mode-toggle{z-index:1500}
                        .bd-mode-toggle .bi{width:1em;height:1em}
                        .bd-mode-toggle .dropdown-menu .active .bi{display:block!important}
                        
                        .cart-badge {
                            position: absolute;
                            top: -8px;
                            right: -8px;
                            background-color: #dc3545;
                            color: white;
                            border-radius: 50%;
                            width: 20px;
                            height: 20px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 12px;
                            font-weight: bold;
                        }
                        
                        .cart-icon {
                            position: relative;
                            cursor: pointer;
                        }

                        /* Styles pour un design moderne */
                        body {
                            background-color: #f8f9fa; /* Couleur de fond légère pour le corps de la page */
                            font-family: 'Arial', sans-serif; /* Exemple de police plus moderne */
                        }

                        .album.py-5 {
                            padding-top: 3rem !important; /* Ajuster l'espacement en haut */
                            padding-bottom: 3rem !important; /* Ajuster l'espacement en bas */
                        }

                        .container {
                            max-width: 1200px; /* Limiter la largeur maximale du contenu pour une meilleure lisibilité */
                        }

                        .row-cols-1.row-cols-sm-2.row-cols-md-3.g-3 {
                            --bs-gutter-x: 1.5rem; /* Espacement horizontal entre les colonnes */
                            --bs-gutter-y: 1.5rem; /* Espacement vertical entre les lignes */
                        }

                        .card {
                            border-radius: 10px; /* Coins arrondis pour les cartes */
                            overflow: hidden; /* Empêche le contenu de dépasser les coins arrondis */
                            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out; /* Transition pour l'effet de survol */
                            border: none; /* Supprimer la bordure par défaut */
                        }

                        .card:hover {
                            transform: translateY(-5px); /* Soulève légèrement la carte au survol */
                            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15); /* Ombre plus prononcée au survol */
                        }

                        .card-img-top {
                            border-top-left-radius: 10px;
                            border-top-right-radius: 10px;
                        }

                        .card-body {
                            padding: 1.5rem; /* Augmenter l'espace intérieur de la carte */
                        }

                        .card-title {
                            font-size: 1.25rem; /* Taille de police plus grande pour le titre */
                            font-weight: bold; /* Rendre le titre en gras */
                            margin-bottom: 0.5rem; /* Marge sous le titre */
                        }

                        .card-text {
                            font-size: 0.95rem; /* Taille de police légèrement plus petite pour la description */
                            margin-bottom: 1rem; /* Marge sous la description */
                            color: #555; /* Couleur de texte légèrement plus douce */
                        }

                        .d-flex.justify-content-between.align-items-center {
                            margin-top: 1rem; /* Ajouter de l'espace au-dessus du bouton et du prix */
                        }

                        .btn-outline-primary {
                            border-color: #007bff;
                            color: #007bff;
                        }

                        .btn-outline-primary:hover {
                            background-color: #007bff;
                            color: white;
                        }

                        .text-success.fw-bold {
                            font-size: 1.1rem; /* Taille de police légèrement plus grande pour le prix */
                        }
        </style>
    </head>
    <body> 
        <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
            <symbol id="check2" viewBox="0 0 16 16">
                 <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z">
                 </path>
            </symbol>
            <symbol id="circle-half" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"></path> 
            </symbol>
            <symbol id="moon-stars-fill" viewBox="0 0 16 16">
                <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"></path> 
                <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"></path>
            </symbol> 
            <symbol id="sun-fill" viewBox="0 0 16 16">
                <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"></path> 
            </symbol>
            <symbol id="cart" viewBox="0 0 16 16">
                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </symbol>
        </svg>
        
        <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
            <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)"> 
                <svg class="bi my-1 theme-icon-active" aria-hidden="true"><use href="#circle-half"></use></svg> 
                <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
                <li> 
                    <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                        <svg class="bi me-2 opacity-50" aria-hidden="true">
                        <use href="#sun-fill"></use>
                        </svg>
                        Light
                        <svg class="bi ms-auto d-none" aria-hidden="true"><use href="#check2"></use>
                        </svg>
                 </button>
                </li>
                <li>
                    <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                        <svg class="bi me-2 opacity-50" aria-hidden="true">
                            <use href="#moon-stars-fill"></use>
                        </svg>
                    Dark
                        <svg class="bi ms-auto d-none" aria-hidden="true"><use href="#check2"></use>
                        </svg>
                    </button> 
                </li> 
                <li>
                    <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true"> 
                        <svg class="bi me-2 opacity-50" aria-hidden="true"><use href="#circle-half"></use>
                        </svg>
                        Auto
                        <svg class="bi ms-auto d-none" aria-hidden="true"><use href="#check2"></use>
                        </svg> 
                    </button> 
                </li> 
            </ul> 
        </div> 
        
        <header data-bs-theme="dark"> 
            <div class="collapse text-bg-dark" id="navbarHeader">
                <div class="container">
                    <div class="row"> 
                        <div class="col-sm-8 col-md-7 py-4"> 
                            <h4>À propos</h4>
                             <p class="text-body-secondary">Bienvenue sur MonoShop, votre boutique en ligne de confiance. Découvrez notre large sélection de produits de qualité à des prix compétitifs.
                             </p>
                        </div> 
                        <div class="col-sm-4 offset-md-1 py-4"> 
                            <h4>Menu</h4> 
                            <ul class="list-unstyled">
                                 <li><a href="formulaire/index.php" class="text-white">Connexion Admin</a></li>
                                 <li><a href="panier.php" class="text-white">Voir le panier</a></li> 
                            </ul> 
                        </div> 
                    </div> 
                </div> 
            </div> 
            <div class="navbar navbar-dark bg-dark shadow-sm"> 
                <div class="container"> 
                    <a href="#" class="navbar-brand d-flex align-items-center">   
                        <img src="monoshop.png" alt="MonoShop Logo" style="height: 30px; margin-right: 10px;">
                        <strong>MonoShop</strong> 
                    </a>
                    
                    <div class="d-flex align-items-center">
                        <a href="panier.php" class="cart-icon text-white me-3">
                            <svg width="24" height="24" fill="currentColor">
                                <use href="#cart"></use>
                            </svg>
                            <?php if($total_articles > 0): ?>
                                <span class="cart-badge"><?= $total_articles ?></span>
                            <?php endif; ?>
                        </a>
                        
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation"> 
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                </div> 
            </div> 
        </header> 
        
        <main> 
            <div class="album py-5 bg-body-tertiary"> 
                <div class="container"> 
                    <h2 class="pb-2 border-bottom">Nos Produits</h2>
                    <p class="lead mb-4">Découvrez notre sélection des derniers articles ajoutés.</p>
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3"> 
                    <?php foreach($Produits as $produit) { ?>
                        <div class="col"> 
                            <div class="card shadow-sm"> 
                                <img src="<?= $produit->image ?>" alt="<?= $produit->nom ?>" class="card-img-top" style="height: 220px; object-fit: cover;">
                                <div class="card-body"> 
                                    <h5 class="card-title"><?= $produit->nom ?></h5>
                                    <p class="card-text"><?= substr($produit->description,0,100) ?>...</p> 
                                    <div class="d-flex justify-content-between align-items-center">
                                        <form method="post" class="d-inline">
                                            <input type="hidden" name="produit_id" value="<?= $produit->id ?>">
                                            <input type="hidden" name="produit_nom" value="<?= $produit->nom ?>">
                                            <input type="hidden" name="produit_prix" value="<?= $produit->prix ?>">
                                            <input type="hidden" name="produit_image" value="<?= $produit->image ?>">
                                            <button type="submit" name="ajouter_panier" class="btn btn-sm btn-outline-primary">
                                                <svg width="16" height="16" fill="currentColor">
                                                    <use href="#cart"></use>
                                                </svg>
                                                Ajouter au panier
                                            </button>
                                        </form>
                                         <small class="text-success fw-bold"><?= $produit->prix ?>€</small> 
                                    </div>
                                </div> 
                            </div> 
                        </div> 
                    <?php } ?>
                     </div> 
                </div> 
            </div> 
        </main> 
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    </body> 
</html>