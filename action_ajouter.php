<?php
require 'connexion.php';

if (isset($_POST['nouveau_nom'])) {
    $insert = $pdo->prepare("INSERT INTO ingredients (nom, quantite) VALUES (:nom, :qte)");
    $insert->execute([
        'nom' => $_POST['nouveau_nom'], 
        'qte' => $_POST['nouvelle_qte']
    ]);
}
header('Location: stock.php?msg=ajout_ok');
exit;
?>