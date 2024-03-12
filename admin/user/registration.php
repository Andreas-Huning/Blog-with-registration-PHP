<?php
declare(strict_types=1);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');
use Lib\Model\User;
use Lib\Model\UserState;
use Lib\Model\Account;
use Lib\repository\UserRepository;
use Lib\repository\UserStateRepository;
use Lib\repository\AccountRepository;
use Lib\repository\AccountRoleRepository;
use Lib\Common\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



$request = new Request();
$errorMessages = NULL;


if(DEBUG) 		    echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Output-Buffering ist Aktiv. <i>(" . basename(__FILE__) . ")</i></p>\n";  
                    ob_start();
                

// if(DEBUG_V)	            echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$request <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	            print_r($request);					
// if(DEBUG_V)	            echo "</pre>";

                if($request->isPost())
                {
                    $accountNameForm    = sanitizeString($request->get('accountNameForm'));
                    $userEmailForm      = sanitizeString($request->get('userEmailForm')) ;
                    $passwordForm       = sanitizeString($request->get('passwordForm')) ;
                    $passwordCheckForm  = sanitizeString($request->get('passwordCheckForm')) ;
                    

if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$accountNameForm: $accountNameForm <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userEmailForm: $userEmailForm <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$passwordForm: $passwordForm <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$passwordCheckForm: $passwordCheckForm <i>(" . basename(__FILE__) . ")</i></p>\n";
   
                    $isFormValid = true;
                    //Check user E-Mail and AccountName
                    $errorAccountNameForm    = validateInputString($accountNameForm,minLength:4);
                    $errorUserEmail          = validateEmail($userEmailForm);

                    if( $errorUserEmail !== NULL OR $errorAccountNameForm !== NULL)
                    {
if(DEBUG)	            echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER: $errorUserEmail <i>(" . basename(__FILE__) . ")</i></p>\n";				
if(DEBUG)	            echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER: $errorAccountNameForm <i>(" . basename(__FILE__) . ")</i></p>\n";				
                       
                         $errorMessages['email']    = $errorUserEmail;
                         $errorMessages['userName'] = $errorAccountNameForm;
                         $isFormValid = false; 
                    }//Check user E-Mail END

                    //Check user Passwort
                    $errorPassword 	= validateInputString($passwordForm, minLength:4);

                    if( $errorPassword !== NULL )                    
                    {
if(DEBUG)	            echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER: $errorPassword <i>(" . basename(__FILE__) . ")</i></p>\n";				
                       
                        $errorMessages['password'] = $errorPassword;
                        $isFormValid = false; 
                    }//Check user Passwort END

                    //VALIDATE PASSWORD
                    if( $errorPassword === NULL )
                    {
                        //Compare Password / PasswordCheck
                        if( $passwordForm !== $passwordCheckForm ){

if(DEBUG)	                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER: Die Passwörter stimmen nicht überein! <i>(" . basename(__FILE__) . ")</i></p>\n";				
                            
                            $errorMessages['password'] = 'Die Passwörter stimmen nicht überein!';
                            $isFormValid = false; 
                        }else{

if(DEBUG)	                echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Die Passwörter stimmen überein! <i>(" . basename(__FILE__) . ")</i></p>\n";				
                           
                            $passwordFormHash = password_hash($passwordForm, PASSWORD_DEFAULT);
if(DEBUG_V)				    echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$passwordFormHash: $passwordFormHash <i>(" . basename(__FILE__) . ")</i></p>\n";

                        }//Compare Password / PasswordCheck END

                    }//VALIDATE PASSWORD END


					#********** FINAL FORM VALIDATION **********#

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
                        $count = $userRepository->countEmail( $userEmailForm );

if(DEBUG_V) 		    echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$count: $count <i>(" . basename(__FILE__) . ")</i></p>\n";   

                        if( $count !== 0 ) 
                        {
                            // Fehlerfall
if(DEBUG)					echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Die Email-Adresse '$userEmailForm' ist bereits registriert! <i>(" . basename(__FILE__) . ")</i></p>\n";				
                            
                            $errorMessages['email'] = 'Diese Email-Adresse ist bereits registriert!';
                            $isFormValid = false; 
    
                        } else 
                        {
                            // Erfolgsfall
if(DEBUG)					echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Die Email-Adresse '$userEmailForm' ist noch nicht registriert. <i>(" . basename(__FILE__) . ")</i></p>\n";				

                            #********** 1. CHECK IF ACCOUNTNAME IS ALREADY REGISTRED **********#	
                            // // DB-Verbindung Öffnen
                            // $pdo = dbConnect();
                            $accountRoleRepository = new AccountRoleRepository($pdo);
                            $accountRepository = new AccountRepository($pdo,$accountRoleRepository);

if(DEBUG_V) 		        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$accountNameForm: $accountNameForm <i>(" . basename(__FILE__) . ")</i></p>\n";   

                            $count = $accountRepository->findAccountByName($accountNameForm);

if(DEBUG_V) 		        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$count: $count <i>(" . basename(__FILE__) . ")</i></p>\n";   
                           
                           if( $count !== Null ) 
                            {
                                // Fehlerfall
if(DEBUG)					    echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Der AccountName '$accountNameForm' ist bereits registriert! <i>(" . basename(__FILE__) . ")</i></p>\n";				
                            
                                $errorMessages['userName'] = 'Der Nutzername ist bereits registriert!';
                                $isFormValid = false; 
    
                            } else 
                            {
                                // Erfolgsfall
if(DEBUG)					    echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Der AccountName '$accountNameForm' ist noch nicht registriert. <i>(" . basename(__FILE__) . ")</i></p>\n";				
                         

                                #********** REGISTRATION HASH GENERIEREN **********#
                                $userRegHash = sha1($userEmailForm);
if(DEBUG_V)					    echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userRegHash: $userRegHash <i>(" . basename(__FILE__) . ")</i></p>\n";


                                #********** 2. SAVE USER DATA INTO DATABASE **********#
if(DEBUG)					    echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Speichere Userdaten in die DB... <i>(" . basename(__FILE__) . ")</i></p>\n";
 
                                // $userName, $userPassword, $userEmail, $userStatesId, $userId, $userAvatarPath, $userRegTimeStamp, $userRegHash
                                $registerUser = new User();//userPassword:$passwordFormHash, userEmail:$userEmailForm, userRegHash:$userRegHash );
                                $registerUser->setUserEmail($userEmailForm);
                                $registerUser->setUserPassword($passwordFormHash);
                                $registerUser->setUserRegHash($userRegHash);

                                // DB-Verbindung Öffnen
                                $pdo = dbConnect();

                                if( $pdo->beginTransaction() === false ){
                                    //Fehlerfall
if(DEBUG) 		                    echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER beim Starten der Transaction! <i>(" . basename(__FILE__) . ")</i></p>\n";				
                                    $errorMessages['userName'] = 'Es ist ein Fehler aufgetreten! Bitte kontaktieren Sie unseren Support.';
                                    $isFormValid = false;                                     
                                } else 
                                {
if(DEBUG) 		                    echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Transaction erfolgreich gestartet. <i>(" . basename(__FILE__) . ")</i></p>\n";					

                                    $userStateRepository = new UserStateRepository($pdo);
                                    $userRepository = new UserRepository( $pdo,$userStateRepository );
                                    $rowCount = $userRepository->registerUser( $registerUser );

                                    //  USER SPEICHERN
                                    if( $rowCount !== 1 ) 
                                    {
                                        // Fehlerfall
if(DEBUG)						        echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER beim Speichern der Userdaten in die DB! <i>(" . basename(__FILE__) . ")</i></p>\n";				
                                                                                            
                                    } else 
                                    {
                                        $newUserID = $pdo->lastInsertID();
                                        // Erfolgsfall
if(DEBUG)						        echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Neuer User erfolgreich unter ID$newUserID in die DB gespeichert. <i>(" . basename(__FILE__) . ")</i></p>\n";				
                                        
                                        // save AccountData
                                        $registerAccount = new Account();
                                        $registerAccount->setUserId((int)$newUserID);
                                        $registerAccount->setAccountName($accountNameForm);

                                        $accountRepository = new AccountRepository($pdo);
                                        $rowCount= $accountRepository->addAccount($registerAccount);
                                        if( $rowCount !== 1 ) 
                                        {
                                            // Fehlerfall
if(DEBUG)						            echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER beim Speichern der Userdaten in die DB! <i>(" . basename(__FILE__) . ")</i></p>\n";				
                                                                                                
                                        } else 
                                        {
                                            $newAccountID = $pdo->lastInsertID();
if(DEBUG)						            echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Neuer Account erfolgreich unter ID$newAccountID in die DB gespeichert. <i>(" . basename(__FILE__) . ")</i></p>\n";				

                                            $sql="INSERT INTO userdata
                                                    (userId)
                                                    VALUES
                                                    (:userId)
                                                ";
                                            $placeholders = array('userId'=>$newUserID);
                                            try {
                                                // Prepare: SQL-Statement vorbereiten
                                                $PDOStatement = $pdo->prepare($sql);
                                                
                                                // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                                                $PDOStatement->execute($placeholders);
                                                
                                            } catch(PDOException $error) {
if(DEBUG) 		                            echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
                                                
                                            }
                                            $rowCount = $PDOStatement->rowCount();
                                            if($rowCount !==1){
if(DEBUG)						                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER beim Speichern der Userdaten in die DB! <i>(" . basename(__FILE__) . ")</i></p>\n";				
                                               
                                            }else{

                                                #********** COMMIT DB CHANGES **********#
							                    if( $pdo->commit() === false ) 
                                                {
								                    // Fehlerfall
if(DEBUG) 					                        echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER beim Durchführen des Commits! <i>(" . basename(__FILE__) . ")</i></p>\n"; 
                                                    $errorMessages['userName'] = 'Es ist ein Fehler aufgetreten! Bitte versuchen Sie es später noch einmal.';
                                                    $isFormValid = false;  
								    
							                    } else {
								                // Erfolgsfall
if(DEBUG) 					                    echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Commit erfolgreich durchgeführt. <i>(" . basename(__FILE__) . ")</i></p>\n"; 
                        
                                                #********** GENERATE CONFIRMATION EMAIL **********#
if(DEBUG)						                echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Generiere Confirmation Email... <i>(" . basename(__FILE__) . ")</i></p>\n";
								
                                                    $url = "http://localhost/confirmRegistration.php?action=conReg&regHash=$userRegHash";
                                                    $content = "<html>
                                                                <head></head>
                                                                <body>
                                                                    <h4>Hallo $accountNameForm,</h4>
                                                                    <p>
                                                                        Sie haben sich am " . date('d.m.Y') . " um " . date('H:m') . " Uhr
                                                                        auf unserer Webseite registriert.<br>
                                                                        Um den Registrierungsvorgang abzuschließen, rufen Sie bitte folgende URL auf:
                                                                    </p>
                                                                    <p><a href='$url'>Bitte diesen Link klicken, um die Registrierung abzuschließen</a></p>
                                                                    <p>
                                                                        Sie können sich erst nach Abschluss dieses Registrierungsvorgangs auf unserer 
                                                                        Seite einloggen.
                                                                    </p>
                                                                    <p>
                                                                        Sollten Sie sich nicht auf unserer Webseite registriert haben, können Sie diese 
                                                                        Email ignorieren; Ihre Daten werden dann automatisch aus unserer
                                                                        Datenbank gelöscht.
                                                                    </p>
                                                                    <p>
                                                                        Viele Grüße<br>
                                                                        Ihr www.Localhost.de-Team
                                                                    </p>															
                                                                </body>
                                                            </html>";
                                                    $mail = new PHPMailer(true);

                                                    try {
                                                        //Server settings
                                                        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                                                        $mail->isSMTP();                                            //Send using SMTP
                                                        $mail->Host       = 'smtp.mail.com';                        //Set the SMTP server to send through
                                                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                                                        $mail->Username   = 'admin@admin.com';                      //SMTP username
                                                        $mail->Password   = 'Passwort';                             //SMTP password
                                                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                                                        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                                                
                                                        //Recipients
                                                        $mail->setFrom('admin@admin.com', 'FROM LOCALHOST');
                                                        $mail->addAddress($userEmailForm, 'MAIL AN');           //Add a recipient
                                                        // $mail->addAddress('ellen@example.com');               //Name is optional
                                                        $mail->addReplyTo('admin@admin.com', 'Antwort An');
                                                        // $mail->addCC('cc@example.com');
                                                        // $mail->addBCC('bcc@example.com');
                                                
                                                        //Attachments
                                                        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                                                        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
                                                
                                                        //Content
                                                        $mail->isHTML(true);                                  //Set email format to HTML
                                                        $mail->Subject = 'Ihre Registrierung bei Localhost.de';
                                                        $mail->Body    = $content;
                                                        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                                                
                                                        // $mail->send();
                                                
// if(DEBUG_V)	                                        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$mail <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	                                        print_r($mail);					
// if(DEBUG_V)	                                        echo "</pre>";
                                                
                                                        echo 'Message has been sent';
                                                    } catch (Exception $e) {
                                                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                                    }//GENERATE CONFIRMATION EMAIL AND SEND MAIL END						
                                                }
                                            
                                                } // COMMIT DB CHANGES END
                                            





                                            





							                
                                            
                                            // DB-Verbindung schließen 
if(DEBUG_DB)                                echo "<p class='debug db'><b>Line " . __LINE__ . "</b>: DB-Verbindung wird geschlossen. <i>(" . basename(__FILE__) . ")</i></p>\n";
                                            unset($PDO, $PDOStatement);

if(DEBUG_V)						            echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$url: $url <i>(" . basename(__FILE__) . ")</i></p>\n";
								
                                        }//// save AccountData END

                                    }//  USER SPEICHERN END

                                }// TRANSACTION END 

                            }//1. CHECK IF ACCOUNTNAME IS ALREADY REGISTRED   END                             
                        
                        }//1. CHECK IF EMAIL ADRESS IS ALREADY REGISTRED END

                    }//FINAL FORM VALIDATION END

                    if( $isFormValid === false ){
                        $request->copyToSession();
                        $_SESSION['error'] = $errorMessages;
                        header(header:"Location: ../.././registration.php");
                    }else{
                        $request->copyToSession();
                        $_SESSION['url'] = $url;
                        header(header:"Location: ../.././registersuccess.php");
                    }


// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$arrayName <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($errorMessages);					
// if(DEBUG_V)	        echo "</pre>";

                 
                    
                   

                



                }//PROCESS FORM REGISTRATION USER END