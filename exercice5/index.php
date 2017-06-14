<?php
/** Code pour se connecter au host puis à la base de données souhaitée : * */
try {
    /** Déclaraton de la variable dataBase connexion : instanciation de la classe PDO * */
    $dataBase = new PDO('mysql:host=localhost;dbname=colyseum;charset=utf8', 'root', 'amstcvj60');
    /** Dans le cas où la connexion ne peut se faire, déclaration de la variable message d'exception * */
} catch (Exception $e) {
    /** Dans le cas où la connexion ne peut se faire, déclaration de la variable message d'erreur * */
    $msg = 'Erreur PDO dans ' . $e->getFile() . ' ligne ' . $e->getLine() . ' : ' . $e->getMessage();
    /** Sécurisation du code en demandant l'arrêt de la recherche de connexion après avoir récupéré 
      le dossier, la ligne et le message d'erreur. * */
    die($msg);
}
//Affichage du spectacle
$queryView = 'SELECT * FROM `shows` WHERE `title`= \'Vestibulum accumsan\'';
$demonstration = $dataBase->query($queryView);
$demonstration = $demonstration->fetch(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Exercice 5 de la partie 2 en PDO</title>
        <meta charset="utf-8"/>
        <meta title="viewport" content="width=device-width, initial-scale=1"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
        <link href="../style.css" rel="stylesheet" title="text/css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <!--Créer un formulaire permettant de modifier un spectacle. Afficher les informations de Vestibulum accumsan. 
        Modifier la date du spectacle : il est repoussé au 1er janvier 2017 à 21h.-->
        <p class="lastName"><strong><u>Formulaire d'ajout de spectacle :</u></strong></p>
        <?php
        // Prédéfinition de la syntaxe de la date 
        $dateRegex = '/^[1-2]{1}[0-9]{3}+[\-]+[0-9]{2}+[\-]+[0-9]{2}$/';
        //Utilisation de isset() qui détermine si une variable est définie et est différente de NULL
        if (isset($_POST['title']) && isset($_POST['performer']) && isset($_POST['date']) && isset($_POST['showTypesId']) && isset($_POST['firstGender']) && isset($_POST['secondGender']) && isset($_POST['duration']) && isset($_POST['startTime'])):
            $title = strip_tags($_POST['title']);
            $performer = strip_tags($_POST['performer']);
            $date = strip_tags($_POST['date']);
            $showType = strip_tags($_POST['showTypesId']);
            $firstGender = strip_tags($_POST['firstGender']);
            $secondGender = strip_tags($_POST['secondGender']);
            $duration = strip_tags($_POST['duration']);
            $startTime = strip_tags($_POST['startTime']);
            if (preg_match($dateRegex, $_POST['date'])) {
                // Requête préparée pour modifier la date et l'heure de début d'un spectacle
                $queryChange = 'UPDATE `shows` SET `date` = :date, `startTime`= :startTime WHERE `title`= :title';
                $prep = $dataBase->prepare($queryChange);
                // Méthode bindValue; PDO::PARAM_STR est une constante.
                $prep->bindValue(':title', $title, PDO::PARAM_STR);
                $prep->bindValue(':date', $date, PDO::PARAM_STR);
                $prep->bindValue(':startTime', $startTime, PDO::PARAM_STR);
                // Exécution de la requête préparée
                $prep->execute();
            }
        endif;
        ?>
        <?php if (!isset($_POST['title']) && !isset($_POST['performer']) && !isset($_POST['date']) && !isset($_POST['showTypesId']) && !isset($_POST['firstGendersID']) && !isset($_POST['secondGendersID']) && !isset($_POST['duration']) && !isset($_POST['startTime'])): ?>
            <form action="index.php" method="POST">
                <p class="form_content col-lg-12"><label for="title" class="col-lg-6">Titre du spectacle : </label><input class="col-lg-6" type="text" placeholder="Titre du spectacle" name="title" value="<?= $demonstration->title ?>" required/></p>
                <p class="form_content col-lg-12"><label for="performer" class="col-lg-6">Nom de l'artiste : </label><input class="col-lg-6" type="text" placeholder="Nom de l'artiste" name="performer" value="<?= $demonstration->performer ?>"/></p>
                <p class="form_content col-lg-12"><label for="date" class="col-lg-6">Date du spectacle (aaaa-mm-jj) : </label><input class="col-lg-6" type="text" placeholder="Date du spectacle (aaaa-mm-jj)" name="date" value="<?= $demonstration->date ?>" required/></p>
                <p class="form_content col-lg-12"><label for="showType" class="col-lg-6">Type de spectacle : </label>
                    <select name="showTypesId" size="1">
                        <?php
                        /** Déclaration de la variable effectuant la requète de sélection de la colonne type dans la table shows + 
                         * fetchAll qui charge en mémoire toutes les données sous forme de tableau. * */
                        $showTypes = $dataBase->query('SELECT `type`, `id` FROM `showTypes`')->fetchAll();
                        /** Affichage de la table showTypes avec les différentes infos  * */
                        foreach ($showTypes as $showType) {
                            ?>
                            <option class="col-lg-2" type="text" placeholder="Type de spectacle" value="<?= $showType['id'] ?>"><?= $showType['type'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </p>
                <p class="form_content col-lg-12"><label for="firstGender" class="col-lg-6">Premier genre du spectacle : </label>
                    <select name="firstGender" size="1">
                        <?php
                        /** Déclaration de la variable effectuant la requète de sélection de la colonne genre dans la table genres + 
                         * fetchAll qui charge en mémoire toutes les données sous forme de tableau. * */
                        $firstGenders = $dataBase->query('SELECT `genre`, `id` FROM `genres`')->fetchAll();
                        /** Affichage de la table showTypes avec les différentes infos  * */
                        foreach ($firstGenders as $firstGender) {
                            ?>
                            <option class="col-lg-2" type="text" placeholder="Genre 1" value="<?= $firstGender['id'] ?>"><?= $firstGender['genre'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </p>
                <p class="form_content col-lg-12"><label for="secondGender" class="col-lg-6">Deuxième genre du spectacle : </label>
                    <select name="secondGender" size="1">
                        <?php
                        /** Déclaration de la variable effectuant la requète de sélection de la colonne genre dans la table genres + 
                         * fetchAll qui charge en mémoire toutes les données sous forme de tableau. * */
                        $secondGenders = $dataBase->query('SELECT `genre`, `id` FROM `genres`')->fetchAll();
                        /** Affichage de la table showTypes avec les différentes infos  * */
                        foreach ($secondGenders as $secondGender) {
                            ?>
                            <option class="col-lg-2" type="text" placeholder="Genre 1" value="<?= $secondGender['id'] ?>"><?= $secondGender['genre'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </p>
                <p class="form_content col-lg-12"><label for="duration" class="col-lg-6">Durée du spectacle hh:mm:ss : </label><input class="col-lg-6" type="time" placeholder="Durée du spectacle hh:mm:ss" name="duration"/></p>
                <p class="form_content col-lg-12"><label for="starttime" class="col-lg-6">Heure de début du spectacle hh:mm:ss : </label><input class="col-lg-6" type="time" placeholder="Heure de début du spectacle hh:mm:ss" name="startTime"/></p>
                <p class="form_content col-lg-12"><button type="submit" name="modifier" class=" col-lg-offset-6 col-lg-3">Modifier</button></p>
            </form>
        <?php endif; ?>
        <footer>
            <p class="footer"><?php include '../index.php'; ?></p>
        </footer>
    </body>
</html>