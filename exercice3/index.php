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
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Exercice 3 de la partie 2 en PDO</title>
        <meta charset="utf-8"/>
        <meta title="viewport" content="width=device-width, initial-scale=1"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
        <link href="../style.css" rel="stylesheet" title="text/css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <!--Créer un formulaire permettant d'ajouter un spectacle. Il contiendra les champs : titre, artiste, date, 
        type de spectacle, genre 1, genre 2, durée, heure de début. Ajouter le spectacle "I love techno" de David Guetta 
        qui a lieu le 20 septembre 2019. C'est un concert (showTypesId : 1) de musique électronique 
        (firstGendersId : 4) et clubbing (secondGenreId : 10) qui dure 3 heures et qui commence à 21h.-->
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
                // Requête préparée pour insérer un nouveau spectacle
                $queryShows = 'INSERT INTO `shows` (`title`, `performer`, `date`, `showTypesId`, `firstGenresId`, `secondGenreId`, `duration`, `startTime`) VALUES (:title, :performer, :date, :showTypesId, :firstGenresId, :secondGenresId, :duration, :startTime)';
                $prepShows = $dataBase->prepare($queryShows);
                // Méthode bindValue; PDO::PARAM_STR est une constante.
                $prepShows->bindValue(':title', $title, PDO::PARAM_STR);
                $prepShows->bindValue(':performer', $performer, PDO::PARAM_STR);
                $prepShows->bindValue(':date', $date, PDO::PARAM_STR);
                $prepShows->bindValue(':showTypesId', $showType, PDO::PARAM_INT);
                $prepShows->bindValue(':firstGenresId', $firstGender, PDO::PARAM_INT);
                $prepShows->bindValue(':secondGenresId', $secondGender, PDO::PARAM_INT);
                $prepShows->bindValue(':duration', $duration, PDO::PARAM_STR);
                $prepShows->bindValue(':startTime', $startTime, PDO::PARAM_STR);
                // Exécution de la requête préparée
                $prepShows->execute();
            }
        endif;
        ?>
        <?php if (!isset($_POST['title']) && !isset($_POST['performer']) && !isset($_POST['date']) && !isset($_POST['showTypesId']) && !isset($_POST['firstGendersID']) && !isset($_POST['secondGendersID']) && !isset($_POST['duration']) && !isset($_POST['startTime'])): ?>
            <form action="index.php" method="POST">
                <p class="form_content col-lg-12"><label for="title" class="col-lg-6">Titre du spectacle : </label><input class="col-lg-6" type="text" placeholder="Titre du spectacle" name="title" required/></p>
                <p class="form_content col-lg-12"><label for="performer" class="col-lg-6">Nom de l'artiste : </label><input class="col-lg-6" type="text" placeholder="Nom de l'artiste" name="performer" required/></p>
                <p class="form_content col-lg-12"><label for="date" class="col-lg-6">Date du spectacle (aaaa-mm-jj) : </label><input class="col-lg-6" type="text" placeholder="Date du spectacle (aaaa-mm-jj)" name="date" required/></p>
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
                <p class="form_content col-lg-12"><button type="submit" name="valider" class=" col-lg-offset-6 col-lg-3">Envoyer</button></p>
            </form>
        <?php endif; ?>
        <footer>
            <p class="footer"><?php include '../index.php'; ?></p>
        </footer>
    </body>
</html>