<?php session_start(); ?>
<!DOCTYPE html>
<html>

    <head>
        <title>Connexion</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600&family=Montserrat:wght@700&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="../style/style.css">
        <script src="../script/script.js"></script>
    </head>

    <body>
        <div class="centerPage">
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="connection-area">
                <form method="post">
                    <h1>CONNEXION</h1>
                    <div class="label_input">
                        <label for="login">Login</label>
                        <input type="text" name="login" id="login">
                    </div>
                    <div class="label_input">
                        <label for="password">Mot de passe</label>
                        <div class="password">
                            <input type="password" name="password" id="password" required>
                            <button class="eye" type="button" onclick="showPassword(1, 0)"><img src="../images/view.png" id="eye_icon"/></button>
                        </div>
                    </div>
                    <input type="submit" value="Se connecter">
                </form>
            </div>
        </div>
    </body>

    <?php
    require_once('../script_php/cnx.php');

    // Initialisation du nombre d'essais incorrects
    if (!isset($_SESSION["login_attempts"])) {
    	$_SESSION["login_attempts"] = 0;
	}

    if (isset($_POST["login"]) && isset($_POST["password"])) {
        $login = mysqli_real_escape_string($mysqli, $_POST["login"]);
        $password = mysqli_real_escape_string($mysqli, $_POST["password"]);
        $password = hash("sha256", $password);

        if ($mysqli->connect_error) {
            die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
        }

        // Requête SQL pour vérifier les informations de connexion
        $stmt = $mysqli->prepare("SELECT * FROM utilisateur WHERE login = ?;");
        $stmt->bind_param('s', $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if ($password == $row["motDePasse"]) {
                $_SESSION["user_id"] = $row["idUtilisateur"];
                $_SESSION["user_type"] = $row["typeUser"];

                header("Location: ../../index.php");
                
                exit;
            } else {
            $_SESSION["login_attempts"]++;

            if ($_SESSION["login_attempts"] == 2) {
                echo "<script>alert('Ceci est votre dernier essai.')</script>";
            } elseif ($_SESSION["login_attempts"] >= 3) {
                echo "<script>alert('Mot de passe ou Nom d\'utilisateur incorrect. C'est votre dernier essai.')</script>";
            } else {
                echo "<script>alert('Mot de passe ou Nom d\'utilisateur incorrect.')</script>";
            }
        }
    } else {
        echo "<script>alert(' Nom d\'utilisateur incorrect.')</script>";
    }
}
?>
</html>
