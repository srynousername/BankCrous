<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Suppression de compte</title>
        <link rel="stylesheet" href="../style/style.css">
        <link rel="stylesheet" href="../style/delete_acc.css">
    </head>
    <body>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="centerPage">
            <div class="account-delete">
                <form method="post">
                <a href="../../index.php" class="back">➜</a>
                    <h1>Suppression de compte</h1>
                    <div class="delete-zone">
                        <?php
                            include("../script_php/cnx.php");
                            $typeUser = "client";
                            $stmt = $mysqli->prepare("SELECT * FROM utilisateur WHERE typeUtilisateur = ?;");
                            $stmt->bind_param("s", $typeUser);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ( $result->num_rows > 0) {
                                echo "<div class=\"account-select\">";
                                echo "<select name=\"account\" id=\"account\" class=\"selectBox\">
                                <option value=\"0\">Select account</option>";
                                while($row = $result->fetch_assoc()){
                                    echo "<option value=\"".$row['idUtilisateur']."\">".$row['login']."</option>";
                                }
                                echo "</select>
                                </div>
                                <input type=\"submit\" value=\"Supprimer\">";
                            }
                            else {
                                echo "<p>Aucun compte à supprimer.</p>";
                            }
                        ?>
                    </div>
                </form>
            </div>
        </div>
        <?php
            if (isset($_POST["account"])) {
                $idUser = $_POST["account"];
                $stmt = $mysqli->prepare("DELETE FROM utilisateur WHERE idUtilisateur = ? AND typeUtilisateur = ?;");
                $stmt->bind_param("ss", $idUser, $typeUser);
                if ($stmt->execute()) {
                    echo "<script>alert(\"Le compte a bien été supprimé.\");</script>";
                } else {
                    echo "<script>alert(\"Une erreur est survenue lors de la suppression du compte.\");</script>";
                }
            }
        ?>
    </body>
</html>