<?php
// stock.php
require 'connexion.php';

// Lecture de la base de données
$requete = $pdo->query("SELECT * FROM ingredients ORDER BY id ASC");
$ingredients = $requete->fetchAll();

// Récupération des messages de succès depuis l'URL
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
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
        <div class="logo"><img src="img/Logo S2S.webp" alt="Sup2stock Logo"></div>
        <div class="loggin"><span class="user-badge">Bonjour, M.Graven</span></div>
        <button style="padding: 8px 15px; border-radius: 15px; cursor: pointer;" onclick="window.location.href='index.php'">Retour à l'accueil</button>
    </header>
     
    <main class="hero-section">
        <div class="hero-titles">
            <h1>UN SITE MASTOCK POUR LES ENTREPRISES D'AVENIR</h1>
            <p>NE SOYEZ PLUS JAMAIS A COURT DE STOCK</p>
        </div>

        <!-- Affichage des messages de confirmation -->
        <?php 
        if ($msg == 'modif_ok') echo "<div style='color: green; background: white; padding: 10px; border-radius: 10px; font-weight: bold; margin-bottom: 20px;'>🔄 Stock mis à jour !</div>";
        if ($msg == 'ajout_ok') echo "<div style='color: green; background: white; padding: 10px; border-radius: 10px; font-weight: bold; margin-bottom: 20px;'>➕ Produit ajouté !</div>";
        if ($msg == 'suppr_ok') echo "<div style='color: green; background: white; padding: 10px; border-radius: 10px; font-weight: bold; margin-bottom: 20px;'>🗑️ Produit supprimé !</div>";
        if ($msg == 'cmd_ok')   echo "<div style='color: green; background: white; padding: 10px; border-radius: 10px; font-weight: bold; margin-bottom: 20px;'>✅ Commande enregistrée !</div>";
        ?>

        <div class="containers-wrapper">
            <!-- CARTE GAUCHE : LE STOCK -->
            <section class="white-card">
                <div class="list-header">
                    <span>Ingrédient</span><span>Stock</span>
                </div>
                
                <!-- Formulaire qui envoie vers action_modifier.php -->
                <form method="POST" action="action_modifier.php">
                    <div class="ingredients-table">
                        <?php foreach ($ingredients as $item): ?>
                        <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px;">
                            <input type="text" name="stocks[<?php echo $item['id']; ?>][nom]" value="<?php echo htmlspecialchars($item['nom']); ?>" style="flex: 2; padding: 8px; border-radius: 5px; border: 1px solid #ccc; font-weight: bold; font-size: 1.2rem;">
                            <input type="number" name="stocks[<?php echo $item['id']; ?>][qte]" value="<?php echo $item['quantite']; ?>" style="width: 80px; padding: 8px; border-radius: 5px; border: 1px solid #ccc; text-align: center; font-weight: bold; font-size: 1.2rem;">
                            
                            <!-- Bouton poubelle qui force l'envoi vers action_supprimer.php -->
                            <button type="submit" name="id_ingredient" value="<?php echo $item['id']; ?>" formaction="action_supprimer.php" style="background: none; border: none; cursor: pointer; font-size: 1.2rem;" onclick="return confirm('Voulez-vous supprimer cet ingrédient ?');">🗑️</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" class="btn-commander" style="background-color: #2e3b9e; color: white; width: 100%; margin-top: 20px;">Mettre à jour le stock</button>
                </form>

                <hr style="margin: 30px 0; border: 0; border-top: 2px solid #eee;">

                <!-- Formulaire qui envoie vers action_ajouter.php -->
                <h3 style="color: black; margin-bottom: 15px;">➕ Ajouter un ingrédient</h3>
                <form method="POST" action="action_ajouter.php" style="display: flex; gap: 10px;">
                    <input type="text" name="nouveau_nom" placeholder="Ex: Fromage" required style="flex: 2; padding: 10px; border-radius: 10px; border: 1px solid #ccc;">
                    <input type="number" name="nouvelle_qte" placeholder="Qté" required style="flex: 1; padding: 10px; border-radius: 10px; border: 1px solid #ccc;">
                    <button type="submit" style="background: green; color: white; border: none; border-radius: 10px; padding: 0 15px; cursor: pointer; font-weight: bold;">OK</button>
                </form>
            </section>

            <!-- CARTE DROITE : COMMANDER -->
            <section class="white-card">
                <!-- Formulaire qui envoie vers action_commander.php -->
                <form class="order-form" method="POST" action="action_commander.php">
                    <input type="text" name="nom_client" placeholder="Nom Prénom :" required>
                    <input type="email" name="email_client" placeholder="Adresse email :" required>
                    <input type="text" name="rib" placeholder="Coordonnées bancaires :" required>
                    <textarea name="detail_commande" placeholder="Détail de la commande:" required></textarea>
                    <button type="submit" class="btn-commander">Commander</button>
                </form>
            </section>
        </div>
    </main>
</body>
</html>