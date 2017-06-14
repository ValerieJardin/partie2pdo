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
// Si j'ai cliqué sur le bouton supprimer ...
if (isset($_POST['delete'])) {
    // Si les champs Numéro de réservation et Numéro de client ne sont pas vides
    if (!empty($_POST['bookingId']) && !empty($_POST['clientId'])) {
        // Alors je récupère l'entrée qui est sous forme de tableau (ex : [24, 25])
        $clientsId = $_POST['clientId'];
        // Requête pour supprimer des clients en fonction de leur id 
        $queryDeletion = 'DELETE FROM `bookings` WHERE `id` IN (?, ?)';
        $prep = $dataBase->prepare($queryDeletion);
        // Boucle permettant de récupérer que  le numéro de client de chaque formulaire (24 puis 25 dans notre cas)
        foreach ($clientsId as $key => $clientId) {
            // Nettoyage de chaque entrée numéro de client
            $clientId = strip_tags($clientId);
            /** Méthode bindValue; PDO::PARAM_STR est une constante. Dans la mesure ou les numéros de client à récupérer 
             * commencent à 1 et que les clés d'un tableau commencent à 0 nous devons spécifier dans notre méthode 
             * bindValue "$key+1" donc que nous récupérons les clés à partir de 1. * */
            $prep->bindValue($key + 1, $clientId, PDO::PARAM_INT);
        }
// Exécution de la requête préparée
        $prep->execute();
    }
}
// J'écrie ma requête pour afficher sur ma page les clients 24 et 25 dans une variable
$queryBooking = 'SELECT `id`, `clientId` FROM `bookings` WHERE `clientId` IN (24,25)';
// Réalisation de la requête et je récupère les données sous forme de tableau d'objet
$bookings = $dataBase->query($queryBooking)->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html>    
    <head>
        <title>Exercice 8 de la partie 2 en PDO</title>
        <meta charset="utf-8"/>
        <meta lastName="viewport" content="width=device-width, initial-scale=1"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
        <link href="../style.css" rel="stylesheet" lastName="text/css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <!--Afficher autant de formulaires que de réservations dont l'id client est 24 ou 25. Après les formulaires, ajouter 
        un bouton supprimer et supprimer toutes ces réservations. (Voir image fournie)-->
        <p class="lastName"><strong><u>Formulaire de résiliation :</u></strong></p>
        <!-- Boucle foreach pour récupérer les clés des clients ligne par ligne. -->
        <?php foreach ($bookings as $key => $booking) { ?>
            <form action="index.php" method="POST">
                <p class="form_content col-lg-12"><label class="col-lg-6">Numéro de réservation : </label><input class="col-lg-6" type="text" placeholder="Numéro de réservation" name="bookingId[<?= $key ?>]" value="<?= $booking->id ?>" required/></p>
                <p class="form_content col-lg-12"><label class="col-lg-6">Numéro de client : </label><input class="col-lg-6" type="text" placeholder="Nom" name="clientId[<?= $key ?>]" value="<?= $booking->clientId ?>" required/></p>
            <?php } ?>
            <p class="form_content col-lg-12"><button type="submit" name="delete" class=" col-lg-offset-6 col-lg-3">Supprimer</button></p>              
        </form>
        <footer>
            <p class="footer"><?php include '../index.php'; ?></p>
        </footer>
    </body>
</html>