<?php
session_start();
if (!isset($_SESSION['user_type'])) {
    header('Location: data/pages/signin.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="data/style/style.css">
    <link rel="stylesheet" href="data/style/menu.css">
    <script src="data/script/main.js"></script>

    <title>Document</title>
</head>

<body>
    <div class="page">
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <header>
            <?php
                if (isset($_SESSION['user_type'])) {
                    if ('user_type' == "admin") {
                        echo '
                                <nav>
                                    <ul class="menu-list">
                                        <li class="menu"><a href="data/pages/create_account.php">Créer un compte</a>
                                        
                                        </li>
                                        <li class="menu"><a href="data/pages/delete_account.php">Supprimer un compte</a>
                                        
                                        </li>
                                    
                                    </ul>
                                </nav>
                            ';
                    }
                    
                    if ($_SESSION['user_type'] == "productOwner" || $_SESSION['user_type'] == "client") {
                        echo '
                                <nav>
                                    <ul class="menu-list">
                                        <li class="menu"><a href="data/pages/treasury.php">Trésorerie</a>
                                            <div class="sub-menu-space">
                                            </div>
                                            <ul class="sub-menu">
                                                <li><a href="data/pages/treasury.php">Détails</a></li>
                                                <li><a href="data/pages/treasury.php#myChart">Graphique</a></li>
                                            </ul>
                                        </li>
                                        <li class="menu"><a href="data/pages/remittance.php">Remises</a>
                                            <div class="sub-menu-space">
                                            </div>
                                            <ul class="sub-menu">
                                                <li><a href="data/pages/remittance.php">Détails</a></li>
                                            </ul>
                                        </li>
                                        <li class="menu"><a href="data/pages/unpaid.php">Impayés</a>
                                            <div class="sub-menu-space">
                                            </div>
                                            <ul class="sub-menu">
                                                <li><a href="data/pages/unpaid.php">Détails</a></li>
                                                <li><a href="data/pages/unpaid.php#myChart">Graphique</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </nav>
                            ';
                    }
                }

                if (isset($_SESSION['user_type'])) {

                    echo '<div><form method="post" ><input type="submit" name="logout" value="Déconnexion"></form>';

                    if (isset($_POST['logout'])) {
                        unset($_SESSION['user_type']);
                        session_destroy();
                        header('Location: data/pages/signin.php');
                    }
                }
            ?>
        </header>

        <section>
            <div class="title">
                <h1>Gestion des paiements</h1>
            </div>
            <article>

            </article>
        </section>
    </div>
</body>
<script src="data/script/menu-animation.js"></script>

</html>