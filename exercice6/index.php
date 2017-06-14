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
//Affichage du client
$queryView = 'SELECT * FROM `clients` WHERE `id`= 5';
$user = $dataBase->query($queryView);
$user = $user->fetch(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Exercice 6 de la partie 2 en PDO</title>
        <meta charset="utf-8"/>
        <meta lastName="viewport" content="width=device-width, initial-scale=1"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
        <link href="../style.css" rel="stylesheet" lastName="text/css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <!--Créer un formulaire permettant de modifier un client. Afficher les informations du client n°5. 
        Modifier son nom et son prénom : il s'appellera Nicolas Monteiro.-->
        <p class="lastName"><strong><u>Formulaire d'inscription :</u></strong></p>
        <?php
        // Prédéfinition de la syntaxe des champs à compléter dans le formulaire 
        $nameRegex = '/^[a-zA-Z]+[-]?[a-zA-Z]+$/';
        $birthRegex = '/^[1-2]{1}[0-9]{3}+[\-]+[0-9]{2}+[\-]+[0-9]{2}$/';
        $cardNumberRegex = '/^[0-9]{0,9}[1-9]{1}$/';
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
                //Requête pour modifier le nom et le prénom du client n°5
                $queryChange = 'UPDATE `clients` SET `lastName`= :lastName, `firstName`= :firstName WHERE `id`= 5';
                $prep = $dataBase->prepare($queryChange);
                // Méthode bindValue; PDO::PARAM_STR est une constante.
                $prep->bindValue(':lastName', $lastName, PDO::PARAM_STR);
                $prep->bindValue(':firstName', $firstName, PDO::PARAM_STR);
                // Exécution de la requête préparée
                $prep->execute();
            }
        endif;
        ?>
        <?php if (!isset($_POST['lastName']) && !isset($_POST['firstName']) && !isset($_POST['birthDate']) && !isset($_POST['card']) && !isset($_POST['cardNumber'])): ?>
            <form action="index.php" method="POST">
                <p class="form_content col-lg-12"><label for="lastname" class="col-lg-6">Nom : </label><input class="col-lg-6" type="text" placeholder="Nom" name="lastName" value="<?= $user->lastName ?>" required/></p>
                <p class="form_content col-lg-12"><label for="firstname" class="col-lg-6">Prénom : </label><input class="col-lg-6" type="text" placeholder="Prénom" name="firstName" value="<?= $user->firstName ?>" required/></p>
                <!--Mise en place d'une valeur fixe pour la date de naissance car l'exercice a pour but de changer le nom 
                et le prénom d'un client dont l'id = 5 -->
                <p class="form_content col-lg-12"><label for="birthDate" class="col-lg-6">Date de Naissance (aaaa-mm-jj) : </label><input class="col-lg-6" type="text" placeholder="____-__-__" name="birthDate" value="1979-11-04"/></p>
                <p class="form_content col-lg-12"><label for="fidelity_card" class="col-lg-6">Carte de fidélité : </label><input class="col-lg-2" type="checkbox" id="cbox" value="checkbox" name="card" <?= ($user->card == 1) ? 'checked' : '' ?>/>J'ai une carte de fidélité</p>
                <p class="form_content col-lg-12"><label for="number_card" class="col-lg-6">Votre numéro de carte : </label><input class="col-lg-6" type="text" placeholder="Votre numéro de carte" name="cardNumber" value="<?= $user->cardNumber ?>"/></p>
                <p class="form_content col-lg-12"><button type="submit" name="modifier" class=" col-lg-offset-6 col-lg-3">Modifier</button></p>
            </form>
        <?php endif; ?>
        <footer>
            <p class="footer"><?php include '../index.php'; ?></p>
        </footer>
    </body>
</html>


Correction : 

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

//déclaration de variables vides pour l'inspection des champs du formulaire
$lastName = '';
$firstName = '';
$birthDate = '';
$cardNumber = 0;
$card = 0;

//écriture d'une regex qui permet un grand nombre de lettres 
$wordsRegex = '#^[A-Z-a-z- éèàêâùïüëç]{2,}$#';
/*écriture d'une regex pour la date de naissance qui permet d'abord de rentrer de 0 à 31, séparér par un slash ou un tiret
ensuite les mois, de 0 à 9 ou de 10 à 12
puis les années de 1900 à 1999 ou de 2000 à 2009*/
$birthDateRegex = '#^([0-2][0-9]|[3][0-1])[/-]([0]?[0-9]|[1][0-2])[/-]([1][9][0-9][0-9]|[2][0][0][0-9])$#';
$cardNumberRegex = '#^[0-9][0-9]|[0-9][0-9]$#';


//penser à utiliser $_request
if(isset($_POST['lastName'])){
    $lastName = trim(strip_tags($_POST['lastName']));
}elseif(isset($_GET['lastName'])){
    $lastName = trim(strip_tags($_GET['lastName']));
}
if(isset($_POST['firstName'])){
    $firstName = trim(strip_tags($_POST['firstName']));
}elseif(isset($_GET['firstName'])){
    $firstName = trim(strip_tags($_GET['firstName']));
}
if(isset($_POST['birthDate'])){
    $birthDate = trim(strip_tags($_POST['birthDate']));
}elseif(isset($_GET['birthDate'])){
    $birthDate = trim(strip_tags($_GET['birthDate']));
}
if(isset($_POST['cardNumber'])){
    $cardNumber = trim(strip_tags($_POST['cardNumber']));
}elseif(isset($_GET['cardNumber'])){
    $cardNumber = trim(strip_tags($_GET['cardNumber']));
}
if(isset($_POST['yesCard'])){
    $card = 1;
    $cardNumberVerif = (preg_match($cardNumberRegex, $cardNumber) == true);
}elseif(isset($_POST['noCard'])){
    $card = 0;
    $cardNumberVerif = NULL;
}

//requête qui sélectionne les informations dns la bases de données 
$query = $bdd->query('SELECT `lastName`, `firstName`, DATE_FORMAT(`birthDate`, \'%d-%m-%Y\') AS `birthDate`, `card`, `cardNumber` FROM `clients` WHERE `id`=5');
$gets = $query->fetch(PDO::FETCH_OBJ);

if((preg_match($wordsRegex, $firstName) == true) && (preg_match($wordsRegex, $lastName) == true) && (preg_match($birthDateRegex, $birthDate) == true)) {
    //requête qui permet de mettre à jour les informations dans la bases de données 
    $push = $bdd->prepare('UPDATE `clients` SET `lastName`=:lastName, `firstName`=:firstName WHERE `id`=5');
    //On assigne une valeur à nos marqueurs nominatifs avec la méthode bindvalue
    $push->bindValue(':lastName', $lastName, PDO::PARAM_STR);
    $push->bindValue(':firstName', $firstName, PDO::PARAM_STR);
    $push->execute();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>PDO : exercice 4, partie 6</title>
        <link href="bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body class="container-fluid">
        <h1>Vos informations</h1>
        <form action="index.php" method="post" id="centered" class="col-lg-offset-2 col-lg-8">
            <div class="row">
                <label for="lastName" class="col-lg-3">Nom : </label>
                <input type="text" name="lastName" value="<?php echo $gets->lastName ?>" placeholder="Votre nom" class="col-lg-4"/>
            </div>
            <div class="row">
                <label for="firstName" class="col-lg-3">Prénom : </label>
                <input type="text" name="firstName" value="<?php echo $gets->firstName ?>" placeholder="Votre prénom" class="col-lg-4"/>
            </div>
            <div class="row">
                <label for="birthDate" class="col-lg-3">Date de naissance: </label>
                <input type="text" name="birthDate" value="<?php echo $gets->birthDate ?>" placeholder="Votre date de naissance" class="col-lg-4"/>
            </div>
            <div class="row">
                <p class="col-lg-3">Carte de fidelité ?</p>
                <div class="col-lg-2">
                    <input <?php if($gets->card == 1){ echo 'checked'; } ?> type="checkbox" name="yesCard" id="yesCard" value="yesCard" ><label for="yesCard">Oui.</label>
                </div>
                <div class="col-lg-2">
                    <input <?php if($gets->card == 0){ echo 'checked'; } ?>  type="checkbox" name="noCard" id="noCard" value="noCard"><label for="noCard">Non.</label>
                </div>
            </div>
            <div class="row">
                <label for="cardNumber" class="col-lg-3">Numéro de carte : </label>
                <input type="text" name="cardNumber" value="<?php echo $gets->cardNumber ?>" placeholder="Entrez votre numéro de carte" class="col-lg-4"/>
            </div>
            <button type="submit" name="validate" id="validate">valider</button>
        </form>
    </body>
</html>