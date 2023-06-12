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

// https://github.com/google/recaptcha télécharger le recaptcha et mettre dans le dossier du site

// require ReCaptcha class, permet de load le recaptcha sur le site 

require('../js/recaptcha-master/src/autoload.php');

// configuration d'expéditeur et réceptionneur 

$from = 'contact du formulaire test <noreply@test.lol>';
$sendTo = ' grexo <grexo@hotmail.fr>';

// Objet du mail

$objet = 'Nouveau message prevenant du formulaire de contact test';

// array $nomDeVariable => text qui apparait dans le mail
// array 'key' (descripteur) relié au name du l'input du form qui est en method "post" => 'value' (valeur) 
 
$fields = array('firstname' => 'Prenom', 'name' => 'Nom', 'email' => 'mail', 'phone' => 'tel', 'message' => 'Message');

// Déclaration de variable pour valider le formulaire complet
$messageOk = 'Message envoyé, merci et à bientôt !';
$errorMessage = 'Erreur, veuillez réessayer à nouveau...';

// Obtenir les clés ReCaptcha sur ce site https://www.google.com/recaptcha/admin/create
// Clé ReCaptcha côté serveur : 
$recaptchaSecret = '6Ld7mTglAAAAAH01z9M9SEOtla_MCtMebxImsuz7';

// effectuer les tests pour vérifier les données du formulaire après un submit (optionnel)

// print_r($_POST);

// try : demande à php d'essayer de faire tout ce qu'il y a dans le bloc try

try
{
    // !empty = n'est pas vide
    if(!empty($_POST))
    {
        // Validation du ReCaptcha si il y a une erreur on renvoie une exception (erreur)
        // if (!isset($_POST['g-recaptcha-response'])) 
        // {
        //     throw new \Exception('ReCaptcha is not set.');
        // }

        // $recaptcha = new \ReCaptcha\ReCaptcha($recaptchaSecret);

        // // we validate the ReCaptcha field together with the user's IP address

        // $response = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

        // if (!$response->isSuccess()) 
        // {
        //     throw new \Exception('ReCaptcha was not validated.');
        // }

        // everything went well, we can compose the message, as usually

        // /!\ A retravailler pour comprendre /!\

        // Déclaration d'une variable qui est true par défaut
        $isValid = true;

        // if function isEmail n'est pas vide 

        if(isEmail($_POST['email']))
        {
            echo 'adresse mail valide';
        }
        else
        {
            echo 'Adresse non valide, réessayez';
            $isValid = false;
        };



        // if function isEmail n'est pas vide
        
        if(isPhone($_POST['phone']))
        {
            echo $_POST ['phone'];
        }
        else
        {
            echo "Le numéro n'est pas valide, réessayez";
            $isValid = false;
        };


        // Si la condition d'un if contient une $variable uniquement c'est la même chose que if($variable) = true
        if($isValid)
        {
            $emailText = "Vous avez un nouveau message du formulaire de contact test \n============================================\n";

            // Pour tout ce qui est écrit dans le tableau (Array) qui contient $key => $value 
            foreach($_POST as $key => $value)
            {
                // Si les keys des fields sont remplis
                if(isset($fields[$key]))
                {
                    // Alors tu concatène .= la key et la value
                    $emailText .= "$fields[$key]: $value \n";
                }
            }


            $headers = array('Content-Type: text/plain; charset="UTF-8";',
            'From: ' . $from,
            'Reply-To: ' . $from,
            'Return-Path: ' . $from,
            );
            // fonction mail official php
            mail($sendTo, $subject, $emailText, implode("\n", $headers));
            // osef c'est pour du ajax
            $responseArray = array('type' => 'success', 'message' => $okMessage);
        }
        else 
        {
            echo "mail ou téléphone pas valide, message non envoyé";
        };
    }
}

// dans le cas d'une erreur tu fais ça
catch (\Exception $e)
{
    $responseArray = array('type' => 'danger', 'message' => $errorMessage);
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{
    $encoded = json_encode($responseArray);
    header('Content-Type: application/json');
    echo $encoded;
}

else 
{
    echo $responseArray['message'];
}



?>