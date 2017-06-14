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
        <title>Exercice 7 de la partie 2 en PDO</title>
        <meta charset="utf-8"/>
        <meta lastName="viewport" content="width=device-width, initial-scale=1"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
        <link href="../style.css" rel="stylesheet" lastName="text/css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <!--Créer deux formulaires contenant tous les champs nécessaires à la création ou modification des clients. 
        Afficher les informations des clients n°24 et 25. Les supprimer grâce à un bouton supprimer que vous aurez ajouté. 
        (Voir image fournie)-->
        <p class="lastName"><strong><u>Formulaire de résiliation :</u></strong></p>
        <?php
        // Prédéfinition de la syntaxe des champs à compléter dans le formulaire 
        $nameRegex = '/^[a-zA-Z]+[-]?[a-zA-Z]+$/';
        $birthRegex = '/^[1-2]{1}[0-9]{3}+[\-]+[0-9]{2}+[\-]+[0-9]{2}$/';
        $cardNumberRegex = '/^[0-9]{0,9}[1-9]{1}$/';
        //Utilisation de isset() qui détermine si une variable est définie et est différente de NULL
        if (isset($_POST['id1Number']) && isset($_POST['id2Number']) && isset($_POST['lastName']) && isset($_POST['firstName']) && isset($_POST['birthDate'])):
            $id1Number = strip_tags($_POST['id1Number']);
            $id2Number = strip_tags($_POST['id2Number']);
            $lastName = strip_tags($_POST['lastName']);
            $firstName = strip_tags($_POST['firstName']);
            $birthDate = strip_tags($_POST['birthDate']);
            if (preg_match($nameRegex, $_POST['lastName']) && preg_match($nameRegex, $_POST['firstName']) && preg_match($birthRegex, $_POST['birthDate'])) {
                // Initialisation des variables
                $card = 0;
                $cardNumber = NULL;
                if (isset($_POST['card']) && isset($_POST['cardNumber']) && preg_match($cardNumberRegex, $_POST['cardNumber'])) {
                    $card = 1;
                    $cardNumber = strip_tags($_POST['cardNumber']);
                }
                //Requête pour supprimer des clients en fonction de leur id 
                $queryDeletion = 'DELETE FROM `clients` WHERE `id` IN (:id1Number, :id2Number)';
                $prep = $dataBase->prepare($queryDeletion);
                // Méthode bindValue; PDO::PARAM_STR est une constante.
                $prep->bindValue(':id1Number', $id1Number, PDO::PARAM_INT);
                $prep->bindValue(':id2Number', $id2Number, PDO::PARAM_INT);
                // Exécution de la requête préparée
                $prep->execute();
            }
        endif;
        ?>
        <?php if (!isset($_POST['lastName']) && !isset($_POST['firstName']) && !isset($_POST['birthDate']) && !isset($_POST['card']) && !isset($_POST['cardNumber'])): ?>
            <form action="index.php" method="POST">
                <p class="lastName"><strong><u>Premier formulaire :</u></strong></p>
                <p class="form_content col-lg-12"><label for="id1Number" class="col-lg-6">Nom : </label><input class="col-lg-6" type="text" placeholder="Numéro de client" name="id1Number" required/></p>
                <p class="form_content col-lg-12"><label for="lastname" class="col-lg-6">Nom : </label><input class="col-lg-6" type="text" placeholder="Nom" name="lastName" required/></p>
                <p class="form_content col-lg-12"><label for="firstname" class="col-lg-6">Prénom : </label><input class="col-lg-6" type="text" placeholder="Prénom" name="firstName" required/></p>
                <p class="form_content col-lg-12"><label for="birthDate" class="col-lg-6">Date de Naissance (aaaa-mm-jj) : </label><input class="col-lg-6" type="text" placeholder="____-__-__" name="birthDate" /></p>
                <p class="form_content col-lg-12"><label for="fidelity_card" class="col-lg-6">Carte de fidélité : </label><input class="col-lg-2" type="checkbox" id="cbox" value="checkbox" name="card" />J'ai une carte de fidélité</p>
                <p class="form_content col-lg-12"><label for="number_card" class="col-lg-6">Votre numéro de carte : </label><input class="col-lg-6" type="text" placeholder="Votre numéro de carte" name="cardNumber" /></p>
                <p class="lastName"><strong><u>Deuxième formulaire :</u></strong></p>
                <p class="form_content col-lg-12"><label for="id2Number" class="col-lg-6">Nom : </label><input class="col-lg-6" type="text" placeholder="Numéro de client" name="id2Number" required/></p>
                <p class="form_content col-lg-12"><label for="lastname" class="col-lg-6">Nom : </label><input class="col-lg-6" type="text" placeholder="Nom" name="lastName" required/></p>
                <p class="form_content col-lg-12"><label for="firstname" class="col-lg-6">Prénom : </label><input class="col-lg-6" type="text" placeholder="Prénom" name="firstName" required/></p>
                <p class="form_content col-lg-12"><label for="birthDate" class="col-lg-6">Date de Naissance (aaaa-mm-jj) : </label><input class="col-lg-6" type="text" placeholder="____-__-__" name="birthDate"/></p>
                <p class="form_content col-lg-12"><label for="fidelity_card" class="col-lg-6">Carte de fidélité : </label><input class="col-lg-2" type="checkbox" id="cbox" value="checkbox" name="card" />J'ai une carte de fidélité</p>
                <p class="form_content col-lg-12"><label for="number_card" class="col-lg-6">Votre numéro de carte : </label><input class="col-lg-6" type="text" placeholder="Votre numéro de carte" name="cardNumber"/></p>
                <p class="form_content col-lg-12"><button type="submit" name="Supprimer" class=" col-lg-offset-6 col-lg-3">Supprimer</button></p>
            </form>
        <?php endif; ?>
        <footer>
            <p class="footer"><?php include '../index.php'; ?></p>
        </footer>
    </body>
</html>