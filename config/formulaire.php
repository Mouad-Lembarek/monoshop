<?php
    include("connexion.php");
    //Afficher les administrateurs
    function afficher($email,$password){
        global $access;
        $req=$access->prepare("SELECT * FROM admins where email=? AND password=?");
        $req->execute(array($email,$password));
        $data = $req->fetch(PDO::FETCH_OBJ);
        $req->closeCursor();
        return $data;
    }
?>