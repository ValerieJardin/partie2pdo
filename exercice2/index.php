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
        <title>Exercice 2 de la partie 2 en PDO</title>
        <meta charset="utf-8"/>
        <meta lastName="viewport" content="width=device-width, initial-scale=1"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
        <link href="../style.css" rel="stylesheet" lastName="text/css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <!--Consigne : Créer un formulaire permettant d'ajouter un client dans la base de données. 
        Ajouter à ce formulaire les champs permettant de créer une carte de fidélité : numéro de carte et type de carte.
        Ajouter, grâce à ce formulaire, Louise Ciccone née le 16 août 1958 et possédant une carte de fidélité. 
        Ajouter sa carte de fidélité n°7125. C'est une carte de type 2.-->
        <p class="lastName"><strong><u>Formulaire d'inscription :</u></strong></p>
        <?php
        // Prédifinition de la syntaxe des champs à compléter dans le formulaire 
        $nameRegex = '/^[a-zA-Z]+[-]?[a-zA-Z]+$/';
        $birthRegex = '/^[1-2]{1}[0-9]{3}+[\-]+[0-9]{2}+[\-]+[0-9]{2}$/';
        $cardNumberRegex = '/^[0-9]{0,9}[1-9]{1}$/';
        $cardTypeRegex = '/^[0-9]+$/';
        //Utilisation de isset() qui détermine si une variable est définie et est différente de NULL
        if (isset($_POST['lastName']) && isset($_POST['firstName']) && isset($_POST['birthDate'])):
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
                // Initialisation des variables
                $cardType = NULL;
                $cardCreate = NULL;
                if (isset($_POST['typeCard']) && isset($_POST['cardCreate']) && preg_match($cardTypeRegex, $_POST['typeCard']) && preg_match($cardNumberRegex, $_POST['cardCreate'])) {
                    $cardType = strip_tags($_POST['typeCard']);
                    $cardCreate = strip_tags($_POST['cardCreate']);
                }
                // Requête préparée pour insérer un nouveau client
                $queryClients = 'INSERT INTO `clients` (`lastName`, `firstName`, `birthDate`, `card`, `cardNumber`) VALUES (:lastName, :firstName, :birthDate, :card, :cardNumber)';
                $prepClients = $dataBase->prepare($queryClients);
                // Méthode bindValue; PDO::PARAM_STR est une constante.
                $prepClients->bindValue(':lastName', $lastName, PDO::PARAM_STR);
                $prepClients->bindValue(':firstName', $firstName, PDO::PARAM_STR);
                $prepClients->bindValue(':birthDate', $birthDate, PDO::PARAM_STR);
                $prepClients->bindValue(':card', $card, PDO::PARAM_INT);
                $prepClients->bindValue(':cardNumber', $cardNumber, PDO::PARAM_INT);
                // Exécution de la requête préparée
                $prepClients->execute();
                // Requête préparée pour insérer une nouvelle carte de fidélité
                $queryCreateCard = 'INSERT INTO `cards` (`cardTypesId`, `cardNumber`) VALUES (:cardType, :cardCreate)';
                $prepCreateCard = $dataBase->prepare($queryCreateCard);
                // Méthode bindValue; PDO::PARAM_STR est une constante.
                $prepCreateCard->bindValue(':cardType', $cardType, PDO::PARAM_INT);
                $prepCreateCard->bindValue(':cardCreate', $cardCreate, PDO::PARAM_INT);
                // Exécution de la requête préparée
                $prepCreateCard->execute();
            }
        endif;
        ?>
        <?php if (!isset($_POST['lastName']) && !isset($_POST['firstName']) && !isset($_POST['birthDate']) && !isset($_POST['card']) && !isset($_POST['cardNumber'])): ?>
            <form action="index.php" method="POST">
                <p class="form_content col-lg-12"><label for="lastname" class="col-lg-6">Nom : </label><input class="col-lg-6" type="text" placeholder="Nom" name="lastName" required/></p>
                <p class="form_content col-lg-12"><label for="firstname" class="col-lg-6">Prénom : </label><input class="col-lg-6" type="text" placeholder="Prénom" name="firstName" required/></p>
                <p class="form_content col-lg-12"><label for="birthDate" class="col-lg-6">Date de Naissance (aaaa-mm-jj) : </label><input class="col-lg-6" type="text" placeholder="____-__-__" name="birthDate" required/></p>
                <p class="form_content col-lg-12"><em>Vous avez déjà une carte de fidélité :</em></p>
                <p class="form_content col-lg-12"><label for="fidelity_card" class="col-lg-6">Carte de fidélité : </label><input class="col-lg-2" type="checkbox" id="cbox" value="checkbox" name="card"/>J'ai une carte de fidélité</p>
                <p class="form_content col-lg-12"><label for="number_card" class="col-lg-6">Votre numéro de carte : </label><input class="col-lg-6" type="text" placeholder="Votre numéro de carte" name="cardType"/></p>
                <p class="form_content col-lg-12"><em>Création de votre carte de fidélité :</em></p>
                <p class="form_content col-lg-12"><label for="type_card" class="col-lg-6">Votre type de carte : </label><input class="col-lg-6" type="text" placeholder="Votre type de carte" name="typeCard"/></p>
                <p class="form_content col-lg-12"><label for="create_card" class="col-lg-6">Le numéro de la carte créée : </label><input class="col-lg-6" type="text" placeholder="Le numéro de la carte créée" name="cardCreate"/></p>
                <p class="form_content col-lg-12"><button type="submit" name="valider" class=" col-lg-offset-6 col-lg-3">Envoyer</button></p>
            </form>
        <?php endif; ?>
        <footer>
            <p class="footer"><?php include '../index.php'; ?></p>
        </footer>
    </body>
</html>