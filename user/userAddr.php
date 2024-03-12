<?php

declare(strict_types=1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');
use Lib\Model\User;
use Lib\Model\UserState;
use Lib\Model\AccountRole;
use Lib\Model\Account;
use Lib\Model\UserData;

use Lib\repository\UserRepository;
use Lib\repository\UserStateRepository;
use Lib\repository\AccountRepository;
use Lib\repository\AccountRoleRepository;
use Lib\repository\UserDataRepository;

use Lib\Common\Request;
$request = new Request();
                if( $loggedIn )
                {
//if(DEBUG)	        echo "<p class='debug'><b>Line " . __LINE__ . "</b>: Benutzer ist angemeldet <i>(" . basename(__FILE__) . ")</i></p>\n";
                     
                    $userId = $_SESSION['ID'];
//if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userId: $userId <i>(" . basename(__FILE__) . ")</i></p>\n";
                   

                    // DB-Verbindung Öffnen
                    $PDO = dbConnect();
                    $userStateRepository = new UserStateRepository($PDO);
                    $userRepository = new UserRepository($PDO,$userStateRepository);
                    $user = $userRepository->findByID($userId);

                    $accountRoleRepository = new AccountRoleRepository($PDO);
                    $accountRepository = new AccountRepository($PDO,$accountRoleRepository);
                    $account = $accountRepository->findAccountByID($userId); 

                    $userDataRepository = new UserDataRepository($PDO);
                    $userData = $userDataRepository->findUserDataByID($userId);

                    $sql            = 'Select * from adr where userId =:userId';
                    $placeholders   = array('userId' => $userId );

                    try {
                        // Prepare: SQL-Statement vorbereiten
                        $PDOStatement = $PDO->prepare($sql);
                        
                        // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                        $PDOStatement->execute($placeholders);
                        
                    } catch(PDOException $error) {
    if(DEBUG) 		echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
                        $dbError = 'Fehler beim Zugriff auf die Datenbank!';
                    }
                    $adrArray = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);

// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$adrArray <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($adrArray);					
// if(DEBUG_V)	        echo "</pre>";                    


                    $sessionData['form_data'] = $request->session->getAll();

                    // DB-Verbindung schließen 
//if(DEBUG_DB)        echo "<p class='debug db'><b>Line " . __LINE__ . "</b>: DB-Verbindung wird geschlossen. <i>(" . basename(__FILE__) . ")</i></p>\n";
                    unset($pdo);



                    $datum = isoToEuDateTime($user->getUserRegTimeStamp());


// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$sessionData <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($sessionData);					
// if(DEBUG_V)	        echo "</pre>";  

                    echo $twig->render('user\userAddr.html.twig', ['user' => $user, 'date' => $datum,'sessionData'=>$sessionData,'account'=>$account,'userData'=>$userData,'addressArray'=>$adrArray] );
                    unset($_SESSION['form_data']);
                    unset($_SESSION['error']);	 	
                }else{

                    header (header:"Location:../index.php");
                }