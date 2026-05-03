<?php
require 'connexion.php';

if (isset($_POST['nom_client'])) {
    $commande = $pdo->prepare("INSERT INTO commandes_resto (nom_client, email_client, rib, detail_commande) VALUES (:nom, :email, :rib, :detail)");
    $commande->execute([
        'nom'    => $_POST['nom_client'],
        'email'  => $_POST['email_client'],
        'rib'    => $_POST['rib'],
        'detail' => $_POST['detail_commande']
    ]);
}
header('Location: stock.php?msg=cmd_ok');
exit;
?>