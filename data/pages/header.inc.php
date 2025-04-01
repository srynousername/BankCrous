<!DOCTYPE html>
<html>
    <body>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <header>
            <?php 
                if(isset($_SESSION['user_type'])) {
                    if($_SESSION['user_type'] == "admin"){
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
                                    <li class="menu"><a href="treasury.php">Trésorerie</a>
                                        <div class="sub-menu-space">
                                        </div>
                                        <ul class="sub-menu">
                                            <li><a href="treasury.php">Détails</a></li>
                                            <li><a href="treasury.php#myChart">Graphique</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu"><a href="remittance.php">Remises</a>
                                        <div class="sub-menu-space">
                                        </div>
                                        <ul class="sub-menu">
                                            <li><a href="remittance.php">Détails</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu"><a href="unpaid.php">Impayés</a>
                                        <div class="sub-menu-space">
                                        </div>
                                        <ul class="sub-menu">
                                            <li><a href="unpaid.php">Détails</a></li>
                                            <li><a href="unpaid.php#myChart">Graphique</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        ';
                    }
                } else {
                    header('Location: signin.php');
                }

                if(isset($_SESSION['user_type'])) {

                        echo '<div><form method="post" ><input type="submit" name="logout" value="Déconnexion"></form>';

                        if (isset($_POST['logout'])) {
                            unset($_SESSION['user_type']);
                            header('Location: signin.php');
                        }
                }
            ?>
        </header>
    </body>
</html>
<script src="../script/menu-animation.js"></script>