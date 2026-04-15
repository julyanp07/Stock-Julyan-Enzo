<?php
require 'connexion.php';

$message_succes = ""; 

// --- FACTEUR 1 : TRAITEMENT DE LA COMMANDE ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nom_client'])) {
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
        $message_succes = "❌ Erreur lors de l'envoi : " . $e->getMessage();
    }
}

// --- FACTEUR 2 : MISE À JOUR DU STOCK À LA MAIN ---
// On écoute si on a cliqué sur le bouton "btn_maj_stock"
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_maj_stock'])) {
    try {
        // On prépare la requête de modification
        $maj_stock = $pdo->prepare("UPDATE ingredients SET quantite = :nouvelle_qte WHERE id = :id_ingredient");
        
        // $_POST['quantites'] est un tableau magique qui contient l'ID et la nouvelle quantité tapée
        foreach ($_POST['quantites'] as $id => $qte) {
            $maj_stock->execute([
                'nouvelle_qte' => $qte,
                'id_ingredient' => $id
            ]);
        }
        $message_succes = "🔄 Stock mis à jour avec succès !";
    } catch (Exception $e) {
        $message_succes = "❌ Erreur de mise à jour : " . $e->getMessage();
    }
}

// --- LECTURE : Récupération des ingrédients pour l'affichage ---
$requete = $pdo->query("SELECT * FROM ingredients");
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
            <h1>UN SITE MASTOCK POUR LES ENTREPRISES D'AVENIR</h1>
            <p>NE SOYEZ PLUS JAMAIS A COURT DE STOCK</p>
        </div>

        <?php if ($message_succes !== ""): ?>
            <div style="background-color: white; color: green; padding: 15px; border-radius: 20px; margin-bottom: 20px; font-weight: bold; border: 2px solid green;">
                <?php echo $message_succes; ?>
            </div>
        <?php endif; ?>

        <div class="containers-wrapper">
            
            <section class="white-card">
                <div class="list-header">
                    <span>Ingredient</span>
                    <span>Stock</span>
                </div>
                
                <form method="POST" action="stock.php" style="display: flex; flex-direction: column; height: 100%;">
                    <div class="ingredients-table" style="flex-grow: 1;">
                        <?php if (count($ingredients) > 0): ?>
                            <?php foreach ($ingredients as $item): ?>
                            <div class="ingredient-row">
                                <label><?php echo htmlspecialchars($item['nom']); ?></label>
                                <input type="number" name="quantites[<?php echo $item['id']; ?>]" value="<?php echo $item['quantite']; ?>" required>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="color: black;">Aucun ingrédient en base.</p>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" name="btn_maj_stock" class="btn-commander" style="margin-top: 30px;">Mettre à jour le stock</button>
                </form>
            </section>

            <section class="white-card">
                <form class="order-form" method="POST" action="stock.php">
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