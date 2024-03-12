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

                if( DEBUG OR DEBUG_V OR DEBUG_F OR DEBUG_DB OR DEBUG_C OR DEBUG_CC ) {
if(DEBUG) 		    echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Output-Buffering ist Aktiv. <i>(" . basename(__FILE__) . ")</i></p>\n";  
                    ob_start();
                }

if(DEBUG_V)	    echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$request <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_V)	    print_r($request);					
if(DEBUG_V)	    echo "</pre>";


                if($request->isPost())
                {
                    $userEmailForm      = sanitizeString($request->get('userEmail')) ;                    
                    $passwordNew        = sanitizeString($request->get('passwordNew')) ;
                    $passwordCheck      = sanitizeString($request->get('passwordCheck')) ;
                    $passwordOrigin     = sanitizeString($request->get('passwordOrigin')) ;

if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userEmailForm: $userEmailForm <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$passwordNew: $passwordNew <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$passwordCheck: $passwordCheck <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$passwordOrigin: $passwordOrigin <i>(" . basename(__FILE__) . ")</i></p>\n";
    
                    
                    $isFormValid = true;
 
                    $errorUserEmail = validateEmail($userEmailForm);

if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$errorUserEmail: $errorUserEmail <i>(" . basename(__FILE__) . ")</i></p>\n";

                    //Check user E-Mail

                    if( $errorUserEmail !==NULL)
                    {
                        $errorMessages['email'] = $errorUserEmail;
                        $isFormValid = false; 
                    }else
                    {
                        $userId = $_SESSION['ID'];
if(DEBUG_V)	            echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userId: $userId <i>(" . basename(__FILE__) . ")</i></p>\n";                    

                        // DB-Verbindung Öffnen
                        $pdo = dbConnect();
                        $userStateRepository = new UserStateRepository($pdo);
                        $userRepository = new UserRepository($pdo,$userStateRepository);
                        $user = $userRepository->findByID($userId);

                        // DB-Verbindung schließen 
if(DEBUG_DB)            echo "<p class='debug db'><b>Line " . __LINE__ . "</b>: DB-Verbindung wird geschlossen. <i>(" . basename(__FILE__) . ")</i></p>\n";
                        unset($PDO, $PDOStatement);
                    
if(DEBUG_V)	            echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$user <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_V)	            print_r($user);					
if(DEBUG_V)	            echo "</pre>";

                        //$userName, $userPassword, $userEmail, $userStatesId, $userId, $userAvatarPath,
                        $userPassword   = $user->getUserPassword();
                        $userEmail      = $user->getUserEmail();
                        $userStatesId   = $user->getUserStatesId();
                        $userId         = $user->getUserId();
                        $userAvatarPath = $user->getUserAvatarPath();

if(DEBUG_V)	            echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userPassword: $userPassword <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	            echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userEmail: $userEmail <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	            echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userStatesId: $userStatesId <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	            echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userId: $userId <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	            echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userAvatarPath: $userAvatarPath <i>(" . basename(__FILE__) . ")</i></p>\n";
                       


                        #********** CHECK IF USER CHANGES PASSWORD **********#
					    if( $passwordNew === Null OR $passwordCheck === Null OR $passwordOrigin === Null ) {
                            // PASSWORD CHANGE INACTIVE
if(DEBUG) 			        echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Password change inactive. <i>(" . basename(__FILE__) . ")</i></p>\n"; 

                        } else 
                        {
                            // PASSWORD CHANGE ACTIVE
if(DEBUG) 			        echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Password change active. <i>(" . basename(__FILE__) . ")</i></p>\n"; 
                
                    
                            #********* 1. CHECK IF PASSWORD MATCHES REQUIREMENTS *********#
if(DEBUG) 			        echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Prüfe ob neues Passwort die Anforderung erfüllt <i>(" . basename(__FILE__) . ")</i></p>\n";
                        
                            if(!$passwordNew || strlen($passwordNew) <= 3)
                            {
if(DEBUG)	                    echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER: Passwort1 erfüllt nicht die Anforderungen <i>(" . basename(__FILE__) . ")</i></p>\n";				
                           
                                $errorMessages['password']='Muss mindestens 3 Zeichen lang sein!';
                                $isFormValid = false; 
                            }else{
if(DEBUG)	                    echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Passwort1 erfüllt die Anforderungen <i>(" . basename(__FILE__) . ")</i></p>\n";				

                                #********* 2. CHECK IF PASSWORD AND PASSWORD CHECK MATCH *********#
                                if( $passwordNew !== $passwordCheck ){
if(DEBUG) 					        echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Die Passworte stimmen nicht überein! <i>(" . basename(__FILE__) . ")</i></p>\n";								
                                    $errorMessages['password']='Die Passwörter stimmen nicht überein';
                                    $isFormValid = false; 
                                }else
                                {
                                    #********* 3. CHECK IF PASSWORD ORIGIN  AND PASSWORD DB MATCH *********#	

                                    if( password_verify( $passwordOrigin, $userPassword  ) === false ) 
                                    {
if(DEBUG) 						        echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Das Bestätigungspasswort stimmt nicht mit dem gespeicherten Passwort aus der DB überein! <i>(" . basename(__FILE__) . ")</i></p>\n";									// Neutrale Fehlermeldung für den User
                                        $errorMessages['password']='Das Bestätigungspasswort stimmt nicht mit dem gespeicherten Passwort überein';
                                        $isFormValid = false; 
                                    }else
                                    {
if(DEBUG) 						        echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Das Bestätigungspasswort stimmt mit dem gespeicherten Passwort aus der DB überein!. <i>(" . basename(__FILE__) . ")</i></p>\n";									
                                        #********** 4. HASH NEW PASSWORD **********#
if(DEBUG) 						        echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Neues Passwort wird gehasht... <i>(" . basename(__FILE__) . ")</i></p>\n";									
								
									    $userPassword = password_hash($passwordNew, PASSWORD_DEFAULT);

if(DEBUG_V) 					        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userPassword: $userPassword <i>(" . basename(__FILE__) . ")</i></p>\n";   
                                
                                    }//3. CHECK IF PASSWORD ORIGIN  AND PASSWORD DB MATCH END                               

                                }//2. CHECK IF PASSWORD AND PASSWORD CHECK MATCH END

                            }//1. CHECK IF PASSWORD MATCHES REQUIREMENTS END

                        }//CHECK IF USER CHANGES PASSWORD END         
                    
                    }//Check user E-Mail End

                    #********** FINAL FORM VALIDATION I (FIELDS VALIDATION) **********#	

                    if( $isFormValid === false )
                    {
if(DEBUG)	            echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER: Das Formular enthält noch Fehler <i>(" . basename(__FILE__) . ")</i></p>\n";				
                      
                    }else{
if(DEBUG) 				echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Das Formular enthält keine Fehler. <i>(" . basename(__FILE__) . ")</i></p>\n";									
						
                        #********** 1. CHECK IF EMAIL ADRESS IS ALREADY REGISTRED **********#	
                        // DB-Verbindung Öffnen
                        $pdo = dbConnect();
                        $userStateRepository = new UserStateRepository($pdo);
                        $userRepository = new UserRepository( $pdo,$userStateRepository );
                        $count = $userRepository->countEmailByID( $userEmailForm, $userId );
                        // DB-Verbindung schließen 
if(DEBUG_DB)         echo "<p class='debug db'><b>Line " . __LINE__ . "</b>: DB-Verbindung wird geschlossen. <i>(" . basename(__FILE__) . ")</i></p>\n";
                        unset($pdo);

if(DEBUG_V) 		    echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$count: $count <i>(" . basename(__FILE__) . ")</i></p>\n";   
                        
                        if( $count !== 0 )
                        {
                            //Fehlerfall
if(DEBUG)				    echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Die Email-Adresse '<i>$userEmailForm</i>' ist bereits für einen anderen User registriert! <i>(" . basename(__FILE__) . ")</i></p>\n";				
                            $errorMessages['email']='Diese Email-Adresse ist bereits für einen anderen User registriert'; 
                            $isFormValid = false; 
                        }else{
                            // Erfolgsfall
if(DEBUG)				    echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Die Email-Adresse '$userEmailForm' ist noch nicht für einen anderen User registriert! <i>(" . basename(__FILE__) . ")</i></p>\n";				
                            

                        	#****************************************#
							#********** IMAGE UPLOAD START **********#
							#****************************************#

if(DEBUG_V)	                echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$FILES <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_V)	                print_r($_FILES);					
if(DEBUG_V)	                echo "</pre>";

                            #********** CHECK IF IMAGE UPLOAD IS ACTIVE **********#
					
                            if( $_FILES['avatar']['tmp_name'] === '' ) 
                            {
                                // IMAGE UPLOAD IS INACTIVE
if(DEBUG) 					    echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Image upload inactive. <i>(" . basename(__FILE__) . ")</i></p>\n"; 

                            } else {
                                // IMAGE UPLOAD IS ACTIVE
if(DEBUG) 					    echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Image upload active. <i>(" . basename(__FILE__) . ")</i></p>\n"; 
                                $validateImageUploadReturnArray = validateImageUpload($_FILES['avatar']['tmp_name']);

if(DEBUG_V) 				    echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$validateImageUploadReturnArray <i>(" . basename(__FILE__) . ")</i>:<br>\n"; 
if(DEBUG_V) 				    print_r($validateImageUploadReturnArray); 
if(DEBUG_V) 				    echo "</pre>";
                                
                                #********** VALIDATE IMAGE UPLOAD **********#
                                if( $validateImageUploadReturnArray['imageError'] !== NULL ){
                                    //FEHLERFALL
                                    /*
                                        AUSNAHMEFEHLER in PHP: Wenn innerhalb eines Strings auf einen assoziativen Index 
                                        zugegriffen wird, entfallen die Anführungszeichen für den Index.
                                    */
if(DEBUG)						    echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER beim Bildupload: '<i>$validateImageUploadReturnArray[imageError]</i>' <i>(" . basename(__FILE__) . ")</i></p>\n";				
                                    $errorMessages['image'] = $validateImageUploadReturnArray['imageError'];
                                    $isFormValid = false; 
                                }else{
                                    //ERFOLGSFALL
if(DEBUG)						    echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Bild erfolgreich unter '<i>$validateImageUploadReturnArray[imagePath]</i>' auf den Server geladen. <i>(" . basename(__FILE__) . ")</i></p>\n";				
                                    
                                    $userAvatarPath = $user->getUserAvatarPath();                                 
if(DEBUG_V)	                        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userAvatarPath: $userAvatarPath <i>(" . basename(__FILE__) . ")</i></p>\n";
                                    
                                    if( $userAvatarPath !== AVATAR_DUMMY_PATH)
                                    {
                                        #********** DELETE OLD IMAGE FROM SERVER **********#
                                        if( unlink($userAvatarPath ) === false ) 
                                        {										
                                            //FEHLERFALL
if(DEBUG)								echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER: Beim Löschen des Vorgängerbildes unter $userAvatarPath ! <i>(" . basename(__FILE__) . ")</i></p>\n";														
                                        }else 
                                        {
                                            // ERFOLGSFALL
if(DEBUG)								echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Vorgängerbild unter $userAvatarPath erfolgreich gelöscht<i>(" . basename(__FILE__) . ")</i></p>\n";				
                                        
                                        }//DELETE OLD IMAGE FROM SERVER END

                                    }//CHECK IF OLD IMAGE IS AVATAR DUMMY END

                                    // Save new Image Path into Variable
                                    $userAvatarPath = $validateImageUploadReturnArray['imagePath'];
if(DEBUG_V)	                        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userAvatarPath: $userAvatarPath <i>(" . basename(__FILE__) . ")</i></p>\n";

                                }//VALIDATE IMAGE UPLOAD END
                                
                            }//CHECK IF IMAGE UPLOAD IS ACTIVE END


                            #********** FINAL FORM VALIDATION II (IMAGE ULPOAD VALIDATION)**********#	
                            if ( $isFormValid === false ){
if(DEBUG)					    echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FINAL FORM VALIDATION II: Das Formular enthält noch Fehler! <i>(" . basename(__FILE__) . ")</i></p>\n";				

                            }else{
if(DEBUG)					    echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: FINAL FORM VALIDATION II: Das Formular ist formal fehlerfrei. <i>(" . basename(__FILE__) . ")</i></p>\n";				
                                
                                //$userName, $userPassword, $userEmail, $userStatesId, $userId, $userAvatarPath, $userRegTimeStamp
                                $updateUser = new User();//$userName, $userPassword, $userEmail, $userStatesId, $userId, $userAvatarPath );
                                $updateUser->setUserPassword($userPassword);
                                $updateUser->setUserEmail($userEmailForm);
                                $updateUser->setUserStatesId($userStatesId);
                                $updateUser->setUserId($userId);
                                $updateUser->setUserAvatarPath($userAvatarPath);

if(DEBUG_V)	                    echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$updateUser <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_V)	                    print_r($updateUser);					
if(DEBUG_V)	                    echo "</pre>";

                                // DB-Verbindung Öffnen
                                $pdo = dbConnect();
                                $userStateRepository = new UserStateRepository($pdo);
                                $userRepository = new UserRepository( $pdo,$userStateRepository );                                
                                $rowCount = $userRepository->updateByID($updateUser);

                                // DB-Verbindung schließen 

if(DEBUG_DB)                 echo "<p class='debug db'><b>Line " . __LINE__ . "</b>: DB-Verbindung wird geschlossen. <i>(" . basename(__FILE__) . ")</i></p>\n";
                               
                                unset($PDO, $PDOStatement);

if(DEBUG_V)	                echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$rowCount: $rowCount <i>(" . basename(__FILE__) . ")</i></p>\n";
                               
                            }

                        }//1. CHECK IF EMAIL ADRESS IS ALREADY REGISTRED END

                    }//FINAL FORM VALIDATION I (FIELDS VALIDATION) END  

                    if( $isFormValid === false ){
                        $request->copyToSession();
                        $_SESSION['error'] = $errorMessages;
                        header(header:"Location: ../.././user/dashboard.php");
                    }else{
                        header(header:"Location: ../.././user/dashboard.php");
                    }


                }//PROCESS FORM EDIT USER DATA END