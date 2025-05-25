<?php
session_start();

// Initialiser le panier s'il n'existe pas
if(!isset($_SESSION['panier'])){
    $_SESSION['panier'] = array();
}

// Gérer les actions du panier
if(isset($_POST['action'])){
    $action = $_POST['action'];
    $produit_id = $_POST['produit_id'];
    
    switch($action){
        case 'augmenter':
            foreach($_SESSION['panier'] as &$item){
                if($item['id'] == $produit_id){
                    $item['quantite']++;
                    break;
                }
            }
            break;
            
        case 'diminuer':
            foreach($_SESSION['panier'] as &$item){
                if($item['id'] == $produit_id){
                    if($item['quantite'] > 1){
                        $item['quantite']--;
                    }
                    break;
                }
            }
            break;
            
        case 'supprimer':
            foreach($_SESSION['panier'] as $key => $item){
                if($item['id'] == $produit_id){
                    unset($_SESSION['panier'][$key]);
                    $_SESSION['panier'] = array_values($_SESSION['panier']); // Réindexer le tableau
                    break;
                }
            }
            break;
            
        case 'vider':
            $_SESSION['panier'] = array();
            break;
    }
}

// Calculer le total
$total_prix = 0;
$total_articles = 0;
foreach($_SESSION['panier'] as $item){
    $total_prix += $item['prix'] * $item['quantite'];
    $total_articles += $item['quantite'];
}

// Gérer la commande avec adresse de livraison
if(isset($_POST['commander']) && isset($_POST['nom']) && isset($_POST['adresse']) && isset($_POST['ville'])){
    if(!empty($_SESSION['panier'])){
        // Récupérer les informations de livraison
        $nom_client = trim($_POST['nom']);
        $adresse_client = trim($_POST['adresse']);
        $ville_client = trim($_POST['ville']);
        $telephone_client = trim($_POST['telephone']);
        
        // Validation basique
        if(!empty($nom_client) && !empty($adresse_client) && !empty($ville_client)){
            $message_succes = "Commande passée avec succès !<br>";
            $message_succes .= "<strong>Total : " . number_format($total_prix * 1.2, 2) . "€</strong><br>";
            $message_succes .= "Livraison à : " . htmlspecialchars($nom_client) . "<br>";
            $message_succes .= htmlspecialchars($adresse_client) . "<br>";
            $message_succes .= htmlspecialchars($ville_client);
            if(!empty($telephone_client)){
                $message_succes .= "<br>Tél : " . htmlspecialchars($telephone_client);
            }
            
            $_SESSION['panier'] = array(); // Vider le panier après commande
            $total_prix = 0;
            $total_articles = 0;
        } else {
            $message_erreur = "Veuillez remplir tous les champs obligatoires (nom, adresse, ville).";
        }
    }
}

// Variable pour afficher le formulaire de livraison
$afficher_formulaire = isset($_POST['proceder_commande']) && !empty($_SESSION['panier']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier - MonoShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <style>
        .cart-item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .quantity-btn {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dee2e6;
            background: white;
            color: #6c757d;
            transition: all 0.2s;
        }
        .quantity-btn:hover {
            background: #f8f9fa;
            border-color: #adb5bd;
        }
        .quantity-display {
            font-weight: bold;
            min-width: 30px;
            text-align: center;
        }
        .cart-summary {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 20px;
        }
        .cart-item-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .cart-item-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        .total-section {
            border-top: 2px solid #dee2e6;
            padding-top: 15px;
            margin-top: 15px;
        }
        .btn-commander {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            font-weight: bold;
            padding: 12px 30px;
            border-radius: 25px;
            transition: all 0.3s;
        }
        .btn-commander:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }
        .empty-cart-icon {
            font-size: 4rem;
            color: #6c757d;
        }
        .delivery-form {
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 2px solid #e9ecef;
        }
        .form-control:focus {
            border-color: #20c997;
            box-shadow: 0 0 0 0.2rem rgba(32, 201, 151, 0.25);
        }
        .step-indicator {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
        }
        .step {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-weight: bold;
            margin-right: 15px;
        }
        .step.active {
            background: #28a745;
            color: white;
        }
        .step.inactive {
            background: #e9ecef;
            color: #6c757d;
        }
    </style>
</head>
<body class="bg-light">
    <header class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a href="index.php" class="navbar-brand d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="me-2" viewBox="0 0 24 24">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                    <circle cx="12" cy="13" r="4"></circle>
                </svg>
                <strong>MonoShop</strong>
            </a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">
                    <svg width="20" height="20" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </svg>
                    Mon Panier
                </span>
                <a href="index.php" class="btn btn-outline-light btn-sm">
                    ← Continuer les achats
                </a>
            </div>
        </div>
    </header>

    <main class="container my-5">
        <?php if(isset($message_succes)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <svg width="24" height="24" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.061L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                    <div>
                        <strong>Félicitations !</strong><br>
                        <?= $message_succes ?>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(isset($message_erreur)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <svg width="24" height="24" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </svg>
                    <strong>Erreur :</strong> <?= $message_erreur ?>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(!empty($_SESSION['panier'])): ?>
            <div class="step-indicator justify-content-center mb-4">
                <div class="step <?= !$afficher_formulaire ? 'active' : 'inactive' ?>">1</div>
                <span class="me-3 <?= !$afficher_formulaire ? 'text-dark fw-bold' : 'text-muted' ?>">Panier</span>
                
                <svg width="20" height="20" fill="currentColor" class="me-3 text-muted" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/>
                </svg>
                
                <div class="step <?= $afficher_formulaire ? 'active' : 'inactive' ?>">2</div>
                <span class="<?= $afficher_formulaire ? 'text-dark fw-bold' : 'text-muted' ?>">Livraison</span>
            </div>
        <?php endif; ?>

        <?php if($afficher_formulaire): ?>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="delivery-form">
                        <h3 class="mb-4">
                            <svg width="24" height="24" fill="currentColor" class="me-2 text-primary" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                            </svg>
                            Informations de livraison
                        </h3>
                        
                        <form method="post">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nom" class="form-label">Nom complet *</label>
                                    <input type="text" class="form-control" id="nom" name="nom" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="Optionnel">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="adresse" class="form-label">Adresse complète *</label>
                                <textarea class="form-control" id="adresse" name="adresse" rows="3" required placeholder="Numéro, rue, appartement..."></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="ville" class="form-label">Ville *</label>
                                    <input type="text" class="form-control" id="ville" name="ville" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="code_postal" class="form-label">Code postal</label>
                                    <input type="text" class="form-control" id="code_postal" name="code_postal" placeholder="Optionnel">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <a href="panier.php" class="btn btn-outline-secondary btn-lg w-100">
                                        ← Retour au panier
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" name="commander" class="btn btn-success btn-lg w-100">
                                        <svg width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                            <path d="M8.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L2.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093L8.95 4.992a.252.252 0 0 1 .02-.022zm-.92 5.14.92.92a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 1 0-1.091-1.028L9.477 9.417l-.485-.486-.943 1.179z"/>
                                        </svg>
                                        Confirmer la commande (<?= number_format($total_prix * 1.2, 2) ?>€)
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="row">
                <div class="col-lg-8">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">Mon Panier</h2>
                        <span class="badge bg-primary fs-6"><?= $total_articles ?> article<?= $total_articles > 1 ? 's' : '' ?></span>
                    </div>
                    
                    <?php if(empty($_SESSION['panier'])): ?>
                        <div class="text-center py-5">
                            <div class="empty-cart-icon mb-4">🛒</div>
                            <h4 class="text-muted">Votre panier est vide</h4>
                            <p class="text-muted mb-4">Découvrez nos produits exceptionnels et ajoutez-les à votre panier</p>
                            <a href="index.php" class="btn btn-primary btn-lg">
                                <svg width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                                </svg>
                                Découvrir nos produits
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="mb-3">
                            <form method="post" class="d-inline">
                                <input type="hidden" name="action" value="vider">
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir vider votre panier ?')">
                                    <svg width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                    </svg>
                                    Vider le panier
                                </button>
                            </form>
                        </div>

                        <?php foreach($_SESSION['panier'] as $item): ?>
                            <div class="card mb-3 cart-item-card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-2 text-center">
                                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['nom']) ?>" class="cart-item-image">
                                        </div>
                                        <div class="col-md-4">
                                            <h5 class="card-title mb-1"><?= htmlspecialchars($item['nom']) ?></h5>
                                            <p class="text-success fw-bold mb-0"><?= number_format($item['prix'], 2) ?>€ / unité</p>
                                            <small class="text-muted">Total: <?= number_format($item['prix'] * $item['quantite'], 2) ?>€</small>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="quantity-controls">
                                                <form method="post" class="d-inline">
                                                    <input type="hidden" name="action" value="diminuer">
                                                    <input type="hidden" name="produit_id" value="<?= $item['id'] ?>">
                                                    <button type="submit" class="quantity-btn">−</button>
                                                </form>
                                                <span class="quantity-display"><?= $item['quantite'] ?></span>
                                                <form method="post" class="d-inline">
                                                    <input type="hidden" name="action" value="augmenter">
                                                    <input type="hidden" name="produit_id" value="<?= $item['id'] ?>">
                                                    <button type="submit" class="quantity-btn">+</button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-end">
                                            <div class="fw-bold text-primary mb-2">
                                                <?= number_format($item['prix'] * $item['quantite'], 2) ?>€
                                            </div>
                                            <form method="post" class="d-inline">
                                                <input type="hidden" name="action" value="supprimer">
                                                <input type="hidden" name="produit_id" value="<?= $item['id'] ?>">
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer cet article du panier ?')">
                                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                    </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="col-lg-4">
                    <div class="cart-summary">
                        <h4 class="mb-3">
                            <svg width="24" height="24" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                            Résumé de commande
                        </h4>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Articles (<?= $total_articles ?>):</span>
                            <span><?= number_format($total_prix, 2) ?>€</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Livraison:</span>
                            <span class="text-success">Gratuite</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>TVA (20%):</span>
                            <span><?= number_format($total_prix * 0.2, 2) ?>€</span>
                        </div>
                        
                        <div class="total-section">
                            <div class="d-flex justify-content-between mb-3">
                                <strong class="fs-5">Total TTC:</strong>
                                <strong class="fs-5 text-primary"><?= number_format($total_prix * 1.2, 2) ?>€</strong>
                            </div>
                            
                            <?php if(!empty($_SESSION['panier'])): ?>
                                <form method="post">
                                    <input type="hidden" name="proceder_commande" value="1">
                                    <button type="submit" class="btn btn-commander w-100">
                                        <svg width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                                        </svg>
                                        Procéder à la commande
                                    </button>
                                </form>
                            <?php else: ?>
                                <button type="button" class="btn btn-commander w-100" disabled>Votre panier est vide</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <footer class="bg-dark text-white-50 py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2025 MonoShop. Tous droits réservés.</p>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="#" class="text-white-50">Confidentialité</a></li>
                <li class="list-inline-item"><a href="#" class="text-white-50">Conditions d'utilisation</a></li>
                <li class="list-inline-item"><a href="#" class="text-white-50">Contact</a></li>
            </ul>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>