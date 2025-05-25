<?php
    include("connexion.php");
    //Ajouter un produit
    function ajouter($nom,$image,$prix,$desc){
        global $access;
        $req = $access->prepare("INSERT INTO produits (image,nom,prix,description) VALUES (?,?,?,?)");
        $req->execute(array($image,$nom,$prix,$desc));
        $req->closeCursor();
    }
    //Afficher les produits
    function afficher(){
        global $access;
        $req=$access->prepare("SELECT * FROM produits ORDER BY id DESC");
        $req->execute();
        $data = $req->fetchALL(PDO::FETCH_OBJ);
        $req->closeCursor();
        return $data;
    }
    //supprimer un produit
    function supprimer($id){
        global $access;
        $req=$access->prepare("DELETE FROM produits WHERE id=?");
        $req->execute(array($id));
        $req->closeCursor();
    }
    //modifier un produit
    function modifier($id,$nom,$image,$prix,$desc){
        global $access;
        $req = $access->prepare("UPDATE produits SET nom=?, image=?, prix=?, description=? WHERE id=?");
        $req->execute(array($nom,$image,$prix,$desc,$id));
        $req->closeCursor();
    }
    //récupérer un produit par son ID
    function getProduitById($id){
        global $access;
        $req=$access->prepare("SELECT * FROM produits WHERE id=?");
        $req->execute(array($id));
        $data = $req->fetch(PDO::FETCH_OBJ);
        $req->closeCursor();
        return $data;
    }
?>