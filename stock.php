<?php
// 1. Connexion à la base de données
require 'connexion.php';

$message_succes = ""; 

// --- FACTEUR 1 : PASSER UNE COMMANDE ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_commander'])) {
    try {
        $insertion = $pdo->prepare("INSERT INTO commandes_resto (nom_client, email_client, rib, detail_commande) VALUES (:nom, :email, :rib, :detail)");
        $insertion->execute([
            'nom'    => $_POST['nom_client'],
            'email'  => $_POST['email_client'],
            'rib'    => $_POST['rib'],
            'detail' => $_POST['detail_commande']
        ]);
        $message_succes = "✅ Commande enregistrée avec succès !";
    } catch (Exception $e) {
        $message_succes = "❌ Erreur lors de la commande : " . $e->getMessage();
    }
}

// --- FACTEUR 2 : MODIFIER LE STOCK EXISTANT ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_maj_stock'])) {
    try {
        $maj_stock = $pdo->prepare("UPDATE ingredients SET nom = :nouveau_nom, quantite = :nouvelle_qte WHERE id = :id");
        if (!empty($_POST['stocks'])) {
            foreach ($_POST['stocks'] as $id => $donnees) {
                $maj_stock->execute([
                    'nouveau_nom'  => $donnees['nom'],
                    'nouvelle_qte' => $donnees['qte'],
                    'id'           => $id
                ]);
            }
        }
        $message_succes = "🔄 Stock mis à jour avec succès !";
    } catch (Exception $e) {
        $message_succes = "❌ Erreur de mise à jour : " . $e->getMessage();
    }
}

// --- FACTEUR 3 : AJOUTER UN NOUVEL INGRÉDIENT ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_ajouter_ingredient'])) {
    try {
        $insert_nouveau = $pdo->prepare("INSERT INTO ingredients (nom, quantite) VALUES (:nom, :qte)");
        $insert_nouveau->execute([
            'nom' => $_POST['nouveau_nom'], 
            'qte' => $_POST['nouvelle_qte']
        ]);
        $message_succes = "➕ Nouvel ingrédient ajouté !";
    } catch (Exception $e) {
        $message_succes = "❌ Erreur d'ajout : " . $e->getMessage();
    }
}

// --- FACTEUR 4 : SUPPRIMER UN INGRÉDIENT ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_supprimer'])) {
    try {
        $suppression = $pdo->prepare("DELETE FROM ingredients WHERE id = :id");
        $suppression->execute(['id' => $_POST['id_ingredient']]);
        $message_succes = "🗑️ Ingrédient supprimé avec succès !";
    } catch (Exception $e) {
        $message_succes = "❌ Erreur de suppression : " . $e->getMessage();
    }
}

// --- LECTURE : Récupération de tous les ingrédients pour l'affichage ---
$requete = $pdo->query("SELECT * FROM ingredients ORDER BY id ASC");
$ingredients = $requete->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Sup2Stock - Gestion de Stock</title>
</head>
<body>

    <header class="navbar">
        <div class="logo">
            <img src="img/Logo S2S.webp" alt="Sup2stock Logo">
        </div>
        <div class="loggin">
            <span class="user-badge">Bonjour, M.Graven</span>
        </div>
        <button onclick="window.location.href='index.php'">Retour à l'accueil</button>
    </header>
     
    <main class="hero-section">
        
        <div class="hero-titles">
            <h1>GESTION COMPLÈTE DU STOCK</h1>
            <p>AJOUTEZ, MODIFIEZ ET SUPPRIMEZ VOS PRODUITS EN DIRECT</p>
        </div>

        <?php if ($message_succes !== ""): ?>
            <div style="background-color: white; color: green; padding: 15px; border-radius: 20px; margin-bottom: 20px; font-weight: bold; border: 2px solid green; text-align: center;">
                <?php echo $message_succes; ?>
            </div>
        <?php endif; ?>

        <div class="containers-wrapper">
            
            <section class="white-card">
                <div class="list-header">
                    <span>Ingrédient</span>
                    <span>Quantité</span>
                </div>
                
                <form method="POST" action="stock.php">
                    <div class="ingredients-table">
                        <?php if (count($ingredients) > 0): ?>
                            <?php foreach ($ingredients as $item): ?>
                            <div class="ingredient-row" style="margin-bottom: 10px; display: flex; gap: 10px; align-items: center;">
                                
                                <input type="text" name="stocks[<?php echo $item['id']; ?>][nom]" value="<?php echo htmlspecialchars($item['nom']); ?>" style="flex: 2; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                <input type="number" name="stocks[<?php echo $item['id']; ?>][qte]" value="<?php echo $item['quantite']; ?>" style="width: 80px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; text-align: center;">
                                
                                <button type="submit" name="btn_supprimer" formaction="stock.php" formmethod="POST" value="<?php echo $item['id']; ?>" style="background: none; border: none; cursor: pointer; font-size: 1.2rem;" onclick="document.getElementById('id_suppr').value = this.value; return confirm('Supprimer définitivement cet ingrédient ?');">🗑️</button>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="color: black;">Aucun ingrédient dans le stock.</p>
                        <?php endif; ?>
                    </div>
                    
                    <input type="hidden" name="id_ingredient" id="id_suppr" value="">
                    
                    <button type="submit" name="btn_maj_stock" class="btn-commander" style="background-color: #2e3b9e; color: white; width: 100%; margin-top: 15px;">💾 Sauvegarder les modifications</button>
                </form>

                <hr style="margin: 30px 0; border: 0; border-top: 2px solid #eee;">

                <h3 style="color: black; margin-bottom: 15px;">➕ Ajouter une nouvelle ligne</h3>
                <form method="POST" action="stock.php" style="display: flex; gap: 10px;">
                    <input type="text" name="nouveau_nom" placeholder="Nom de l'ingrédient" required style="flex: 2; padding: 10px; border-radius: 10px; border: 1px solid #ccc;">
                    <input type="number" name="nouvelle_qte" placeholder="Qté" required style="flex: 1; padding: 10px; border-radius: 10px; border: 1px solid #ccc; text-align: center;">
                    <button type="submit" name="btn_ajouter_ingredient" style="background: green; color: white; border: none; border-radius: 10px; padding: 0 20px; font-weight: bold; cursor: pointer;">OK</button>
                </form>
            </section>

            <section class="white-card">
                <form class="order-form" method="POST" action="stock.php">
                    <h3 style="color: black; margin-bottom: 15px;">📝 Nouvelle Commande</h3>
                    <input type="text" name="nom_client" placeholder="Nom Prénom :" required>
                    <input type="email" name="email_client" placeholder="Adresse email :" required>
                    <input type="text" name="rib" placeholder="Coordonnées bancaires :" required>
                    <textarea name="detail_commande" placeholder="Détail de la commande (ex: 2 Burgers, 1 Salade):" required></textarea>
                    
                    <button type="submit" name="btn_commander" class="btn-commander">Commander</button>
                </form>
            </section>

        </div>
    </main>

</body>
</html>