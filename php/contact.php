<?php

// function verification des fields (ce qu'il y a dans le champs écrit pas les clients), mails, tel

function isEmail($isMail)
{
    return filter_var($isMail, FILTER_VALIDATE_EMAIL);
}

function isPhone($isTel)
{
    return preg_match("/^[0-9 ]*$/", $isTel);
}

// https://github.com/google/recaptcha

// require ReCaptcha class

require('../js/recaptcha-master/src/autoload.php');

// configuration du expéditeur et réceptionneur 

$from = 'contact du formulaire test <noreply@test.lol>';
$sendTo = ' grexo <grexo@hotmail.fr>';

// Objet du mail

$objet = 'Nouveau message prevenant du formulaire de contact test';

// array $nomDeVariable => text qui apparait dans le mail
// array 'key' (descripteur) => 'value' (valeur)
 
$fields = array('firstname' => 'Prenom', 'name' => 'Nom', 'email' => 'mail', 'phone' => 'tel', 'message' => 'Message');

$messageOk = 'Message envoyé, merci et à bientôt !';
$errorMessage = 'Erreur, veuillez réessayer à nouveau...';

// https://www.google.com/recaptcha/admin/site/624466299/setup (Recaptacha côté serveur obtenir sur ce site la clé)

$recaptchaSecret = '6Ld7mTglAAAAAH01z9M9SEOtla_MCtMebxImsuz7';

// effectuer les tests pour vérifier les données du formulaire après un submit

print_r($_POST);



?>