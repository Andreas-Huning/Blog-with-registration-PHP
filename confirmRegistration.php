<?php
declare(strict_types=1);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');
use Lib\Model\User;
use Lib\Model\UserState;
use Lib\repository\UserRepository;
use Lib\repository\UserStateRepository;
use Lib\Common\Request;

$request = new Request();
$errorMessages;


                if( isset($_GET['regHash']) === false ) {
                    // Zugriff verboten
// if(DEBUG)		echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Unberechtigter Seitenaufruf! <i>(" . basename(__FILE__) . ")</i></p>\n";				
                    
                    header(header:"Location: ./index.php");                    
                    exit();
                }else
                {
// if(DEBUG)		    echo "<p class='debug'>🧻 <b>Line " . __LINE__ . "</b>: URL-Parameter 'regHash' wurde übergeben. <i>(" . basename(__FILE__) . ")</i></p>\n";										

// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$request <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($request);					
// if(DEBUG_V)	        echo "</pre>";

                    $regHash          = sanitizeString($request->get('regHash')) ;

// if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$regHash: $regHash <i>(" . basename(__FILE__) . ")</i></p>\n";
                        
                        // DB-Verbindung Öffnen
                        $pdo = dbConnect();
                        $userStateRepository = new UserStateRepository($pdo);
                        $userRepository = new UserRepository($pdo,$userStateRepository);
                        $user = $userRepository->findUserByHash($regHash);

                        // DB-Verbindung schließen 
// if(DEBUG_DB)            echo "<p class='debug db'><b>Line " . __LINE__ . "</b>: DB-Verbindung wird geschlossen. <i>(" . basename(__FILE__) . ")</i></p>\n";
                        
                        unset($pdo);

// if(DEBUG_V)	            echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$user <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	            print_r($user);					
// if(DEBUG_V)	            echo "</pre>";

                        // REGISTRIERUNG DURCHFÜHREN
                        if($user === NULL){
// if(DEBUG)			        echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Der Hashwert wurde nicht in der DB gefunden! <i>(" . basename(__FILE__) . ")</i></p>\n";				
                            
                            $errorMessages['login']='Loginname oder Passwort falsch!';
                            $request->copyToSession();
                            $_SESSION['error'] = $errorMessages;
                            header(header:"Location: ./index.php");                    
                            exit();
                        }else
                        {
// if(DEBUG)			        echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Der Hashwert wurde in der DB gefunden. <i>(" . basename(__FILE__) . ")</i></p>\n";				

                            $userId  = $user->getUserId();

// if(DEBUG_V)	                echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userId: $userId <i>(" . basename(__FILE__) . ")</i></p>\n";
                           
                            // DB-Verbindung Öffnen
                            $PDO = dbConnect();

                            $sql 		= 'UPDATE users
                                            SET
                                            userRegHash = :userRegHash,
                                            userStatesId = :userStatesId
                                            WHERE 
                                            userId  = :userId ';

                            $params 	= array('userStatesId'	=> 2,
                                                'userId'		=> $userId,
                                                'userRegHash'   => NULL
                                                  );

                            try {
                                // Prepare: SQL-Statement vorbereiten
                                $PDOStatement = $PDO->prepare($sql);
                                
// if(DEBUG_V)	echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$PDOStatement <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	print_r($PDOStatement->debugDumpParams());					
// if(DEBUG_V)	echo "</pre>";



                                // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                                $PDOStatement->execute($params);



                                
                            } catch(PDOException $error) {
// if(DEBUG) 		                echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
                                
                            }	
                            $rowCount = $PDOStatement->rowCount();

// if(DEBUG_V)				echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$rowCount: $rowCount <i>(" . basename(__FILE__) . ")</i></p>\n";
                                
                            if( $rowCount !== 1 ) {
                                // Fehlerfall
// if(DEBUG)					echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER beim Abschließen der Registrierung! <i>(" . basename(__FILE__) . ")</i></p>\n";				
                                    
                            } else {
                                // Erfolgsfall
// if(DEBUG)					echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Registrierung erfolgreich abgeschlossen. <i>(" . basename(__FILE__) . ")</i></p>\n";				
                                    
                                  
                                #********** UMLEITUNG AUF success SEITE **********#
                                header(header:"Location: ./login.php");                                     
                                    
                            } // ACTIVATE REGISTRATION END

                        }// REGISTRIERUNG DURCHFÜHREN END

                }// URL VALIDATION END

