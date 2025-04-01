<!Doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Création de compte</title>
        <link rel="stylesheet" href="../style/style.css">
        <script src="../script/script.js"></script>
    </head>
    <body>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="centerPage">
            <div class="creation">
                <form method="post">
                    <a href="../../index.php" class="back">➜</a>
                    <h1>CREATION DE COMPTE</h1>
                    <div class="all_inputs_labels">
                        <div class="form_1">
                            <div class="label_input">
                                <label for="num_siren">Numéro SIREN</label></br>
                                <input type="text" name="num_siren" id="num_siren" pattern="[0-9]{9}" required>
                            </div>
                            <div class="label_input">
                                <label for="raison_sociale">Nom de l'entreprise</label></br>
                                <input type="text" name="raison_sociale" id="raison_sociale" pattern="[a-zA-Z0-9]{1,}" required>
                            </div>
                        </div>
                        <div class="form_2">
                            <div class="label_input">
                                <label for="username">Nom d'utilisateur</label></br>
                                <input type="text" name="username" id="username" pattern="[a-zA-Z0-9]{1,}" required>
                            </div>
                            <div class="label_input">
                                <label for="password">Mot de passe</label></br>
                                <div class="password">
                                    <input type="password" name="password" id="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W).{12,}$" required>
                                    <button class="eye" type="button" onclick="showPassword(1, 0)"><img src="../images/view.png" id="eye_icon"/></button>
                                </div>
                            </div>
                            <div class="label_input">
                                <label for="password_confirm">Confirmer le mot de passe</label></br>
                                <div class="password confirm">
                                    <input type="password" name="confirm_password" id="confirm_password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W).{12,}$" required>
                                    <button class="eye" type="button" onclick="showPassword(2, 1)"><img src="../images/view.png" id="eye_icon"/></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accord">
                        <input type="checkbox" id="poAutorisation" name="poAutorisation" required>
                        <label for="poAutorisation">Accord du PO</label>
                    </div>
                    <br />
                    <input type="submit" value="Créer">
                </form>
            </div>
        </div>
    </body>
    <?php
        // Verification of the existence of the post variables
        if(isset($_POST['num_siren']) && isset($_POST['raison_sociale']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirm_password'])){
            // Recuperation of the post variables
            $num_siren = $_GET['num_siren'];
            $raison_sociale = $_POST['raison_sociale'];
            $username = $_POST['user'];
            $password = $_POST['motdepasse'];
            $confirm_password = $_GET['confirm_password'];
            if ($password == $confirm_password) {
                include('../script_php/cnx.php');
                // Verification of the existence of the account
                $stmt = $mysqli->prepare("SELECT * FROM utilisateur WHERE login = ?;");
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows == 1){
                    echo '<script>alert("Ce nom d\'utilisateur est déjà utilisé")</script>';
                    exit;
                } else {
                    $password = hash('sha256', $password);
                    // Creation of the account
                    $stmt = $mysqli->prepare('INSERT INTO utilisateur (login, motDePasse, typeUtilisateur) VALUES(?, ?, "client");');
                    $stmt->bind_param('ss', $username, $password);
                    if ($stmt->execute()) {
                        // Get the id of the account
                        $stmt = $mysqli->prepare("SELECT idUtilisateur FROM utilisateur WHERE login = ?;");
                        if($stmt) {
                            $stmt->bind_param('s', $username);
                            $stmt->execute();
                        } else {
                            // Gérer l'erreur de préparation de la requête ici
                            echo "Erreur de préparation de la requête : " . $mysqli->error;
                        }
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        $idUtilisateur = $row['idUtilisateur'];
                        // Creation of the client's account into the clients database
                        $stmt = $mysqli->prepare('INSERT INTO client (idUtilisateur, numSiren, raisonSociale) VALUES(?, ?, ?);');
                        $stmt->bind_param('iss', $idUtilisateur, $num_siren, $raison_sociale);
                        if ($stmt->execute()) {
                            echo '<script>alert("Compte créé avec succès")</script>';
                        }
                        else{
                            echo '<script>alert("Erreur lors de la création du compte")</script>';
                        }
                    }
                }
            } else{
                echo '<script>alert("Les mots de passe ne correspondent pas")</script>';
            }
        }
    ?>
</html>
