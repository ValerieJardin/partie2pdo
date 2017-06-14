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
    // Si les champs Numéro de billet, Prix et Numéro de réservation ne sont pas vides
    if (!empty($_POST['ticketId']) && !empty($_POST['price']) && !empty($_POST['bookingsId'])) {
        // Alors je récupère l'entrée qui est sous forme de tableau (ex : [24, 25])
        $bookingsId = $_POST['bookingsId'];
        /** Requête pour supprimer des billets en fonction de leur id. 
         * La boucle foreach à l'intérieur du IN() permet de récupérer les numéros de réservation de chaque formulaire (24 puis 25 dans notre cas).
         * ".=" est équivalent à "$queryDeletion = $queryDeletion . (concaténation) ')';  **/
        $queryDeletion = 'DELETE FROM `tickets` WHERE `id` IN (?'; foreach ($bookingsId as $bookingId){$queryDeletion .= ', ?'; } $queryDeletion .= ')';
        $prep = $dataBase->prepare($queryDeletion);
        // Boucle permettant de récupérer uniquement le numéro de billet de chaque formulaire (24 puis 25 dans notre cas)
        foreach ($bookingsId as $key => $bookingId) {
            // Nettoyage de chaque entrée numéro de billet
            $bookingId = strip_tags($bookingId);
            /** Méthode bindValue; PDO::PARAM_STR est une constante. Dans la mesure ou les numéros de billet à récupérer 
             * commencent à 1 et que les clés d'un tableau commencent à 0 nous devons spécifier dans notre méthode 
             * bindValue "$key+1" donc que nous récupérons les clés à partir de 1. * */
            $prep->bindValue($key + 1, $bookingId, PDO::PARAM_INT);
        }
// Exécution de la requête préparée
        $prep->execute();
    }
}
// J'écrie ma requête pour afficher sur ma page les billets 24 et 25 dans une variable
$queryBooking = 'SELECT `id`,`price`, `bookingsId` FROM `tickets` WHERE `bookingsId` IN (24,25)';
// Réalisation de la requête et je récupère les données sous forme de tableau d'objet
$bookings = $dataBase->query($queryBooking)->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html>    
    <head>
        <title>Exercice 9 de la partie 2 en PDO</title>
        <meta charset="utf-8"/>
        <meta lastName="viewport" content="width=device-width, initial-scale=1"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
        <link href="../style.css" rel="stylesheet" lastName="text/css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <!--Afficher autant de formulaires que de billets appartenant aux réservations 24 ou 25. Après les formulaires, 
        ajouter un bouton supprimer et supprimer tous ces billets. (Voir image fournie)-->
        <p class="lastName"><strong><u>Formulaire de résiliation :</u></strong></p>
        <!-- Boucle foreach pour récupérer les clés des clients ligne par ligne. -->
        <?php foreach ($bookings as $key => $booking) { ?>
            <form action="index.php" method="POST">
                <p class="form_content col-lg-12"><label class="col-lg-6">Numéro de billet : </label><input class="col-lg-6" type="text" placeholder="Numéro de billet" name="ticketId[<?= $key ?>]" value="<?= $booking->id ?>" required/></p>
                <p class="form_content col-lg-12"><label class="col-lg-6">Prix : </label><input class="col-lg-6" type="text" placeholder="Prix" name="price[<?= $key ?>]" value="<?= $booking->price ?>" required/></p>
                <p class="form_content col-lg-12"><label class="col-lg-6">Numéro de réservation : </label><input class="col-lg-6" type="text" placeholder="Numéro de réservation" name="bookingsId[<?= $key ?>]" value="<?= $booking->bookingsId ?>" required/></p>
            <?php } ?>
            <p class="form_content col-lg-12"><button type="submit" name="delete" class=" col-lg-offset-6 col-lg-3">Supprimer</button></p>              
        </form>
        <footer>
            <p class="footer"><?php include '../index.php'; ?></p>
        </footer>
    </body>
</html>