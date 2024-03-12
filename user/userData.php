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
                    $pdo = dbConnect();
                    $userStateRepository = new UserStateRepository($pdo);
                    $userRepository = new UserRepository($pdo,$userStateRepository);
                    $user = $userRepository->findByID($userId);

                    $accountRoleRepository = new AccountRoleRepository($pdo);
                    $accountRepository = new AccountRepository($pdo,$accountRoleRepository);
                    $account = $accountRepository->findAccountByID($userId); 

                    $userDataRepository = new UserDataRepository($pdo);
                    $userData = $userDataRepository->findUserDataByID($userId);

// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$userData <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($userData);					
// if(DEBUG_V)	        echo "</pre>";                  



                    $sessionData['form_data'] = $request->session->getAll();

                    // DB-Verbindung schließen 
//if(DEBUG_DB)        echo "<p class='debug db'><b>Line " . __LINE__ . "</b>: DB-Verbindung wird geschlossen. <i>(" . basename(__FILE__) . ")</i></p>\n";
                    unset($pdo);

// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$user <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($user);					
// if(DEBUG_V)	        echo "</pre>";

                    $datum = isoToEuDateTime($user->getUserRegTimeStamp());


// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$sessionData <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($sessionData);					
// if(DEBUG_V)	        echo "</pre>";  

                    echo $twig->render('user\userData.html.twig', ['user' => $user, 'date' => $datum,'sessionData'=>$sessionData,'account'=>$account,'userData'=>$userData] );
                    unset($_SESSION['form_data']);
                    unset($_SESSION['error']);	 	
                }else{

                    header (header:"Location:../index.php");
                }
