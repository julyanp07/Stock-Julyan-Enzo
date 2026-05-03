<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Sup2Stock - Accueil</title>
</head>

<body>
    <header class="navbar">
        <div class="logo">
            <img src="img/Logo S2S.webp" alt="Sup2stock Logo">
        </div>
        <nav class="icons">
            <button data-action="colis">📦</button>
            <button data-action="cloche">🔔</button>
            <button data-action="stat">📊</button>
        </nav>
        <div class="loggin">
            <button onclick="window.location.href='stock.php'">Se connecter / S'inscrire</button>
        </div>
    </header>

    <main class="hero-section" style="background: linear-gradient(rgba(46, 59, 158, 0.6), rgba(46, 59, 158, 0.6)), url('img/FOND S2S.webp') no-repeat center center/cover;">
        <div class="hero-titles" style="background: white; padding: 20px 0; margin-top: 100px;">
            <h1>UN SITE MASTOCK POUR LES ENTREPRISES D'AVENIR</h1>
            <p>NE SOYEZ PLUS JAMAIS A COURT DE STOCK</p>
        </div>
        <a href="stock.php" style="background: white; color: black; text-decoration: none; padding: 15px 40px; border-radius: 10px; font-weight: bold; font-size: 1.5rem; margin-top: auto; margin-bottom: 50px;">ACCEDER A VOTRE STOCKAGE</a>
    </main>
</body>

</html>