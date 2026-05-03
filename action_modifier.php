<?php
require 'connexion.php';

if (isset($_POST['stocks'])) {
    $maj = $pdo->prepare("UPDATE ingredients SET nom = :nom, quantite = :qte WHERE id = :id");
    foreach ($_POST['stocks'] as $id => $donnees) {
        $maj->execute([
            'nom' => $donnees['nom'],
            'qte' => $donnees['qte'],
            'id'  => $id
        ]);
    }
}
header('Location: stock.php?msg=modif_ok');
exit;
?>