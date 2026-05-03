<?php
require 'connexion.php';

if (isset($_POST['id_ingredient'])) {
    $supprimer = $pdo->prepare("DELETE FROM ingredients WHERE id = :id");
    $supprimer->execute(['id' => $_POST['id_ingredient']]);
}
header('Location: stock.php?msg=suppr_ok');
exit;
?>