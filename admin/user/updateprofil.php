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
                    $userDataFirstName  = sanitizeString($request->get('userFirstNameForm')) ;   
                    $userDataLastName   = sanitizeString($request->get('userLastNameForm')) ;     
                    $userDataBirthday   = sanitizeString($request->get('userBirthdayForm')) ; 
                    $userId             = $_SESSION['ID']; 

if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userDataFirstName: $userDataFirstName <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userDataLastName: $userDataLastName <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userDataBirthday: $userDataBirthday <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userId: $userId <i>(" . basename(__FILE__) . ")</i></p>\n";

                    // DB-Verbindung Öffnen
                    $PDO = dbConnect();

                    $sql = 'SELECT COUNT(userId) 
                            FROM userdata 
                            WHERE userId = :userId';

                    $placeholders 	= array('userId' => $userId);

                    try {
                        // Prepare: SQL-Statement vorbereiten
                        $PDOStatement = $PDO->prepare($sql);                        
                        // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                        $PDOStatement->execute($placeholders);                        
                    } catch(PDOException $error) {
if(DEBUG) 		        echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
                    } 
                    $count = $PDOStatement->fetchColumn();

if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$count: $count <i>(" . basename(__FILE__) . ")</i></p>\n";

                    if( $count === 0){
                        $sql = 'INSERT INTO userdata
                        (userDataFirstName, userDataLastName, userDataBirthday,userId )
                        VALUES
                        (:userDataFirstName, :userDataLastName,:userDataBirthday,:userId)';

                    }else{
                        $sql = 'UPDATE userdata 
                        SET
                        userDataFirstName   = :userDataFirstName, 
                        userDataLastName    = :userDataLastName, 
                        userDataBirthday    = :userDataBirthday
                        WHERE userId        = :userId';
                    }
                    $placeholders = array( 'userDataFirstName'  => $userDataFirstName,
                                            'userDataLastName'  => $userDataLastName,
                                            'userDataBirthday'  => $userDataBirthday, 
                                            'userId'            => $userId
                                        );
                    try {
                        // Prepare: SQL-Statement vorbereiten
                        $PDOStatement = $PDO->prepare($sql);                        
                        // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                        $PDOStatement->execute($placeholders);                        
                    } catch(PDOException $error) {
if(DEBUG) 		        echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
                    } 

                    $rowCount = $PDOStatement->rowCount();
if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$count: $count <i>(" . basename(__FILE__) . ")</i></p>\n";

                    // DB-Verbindung schließen 
// if(DEBUG_DB)     echo "<p class='debug db'><b>Line " . __LINE__ . "</b>: DB-Verbindung wird geschlossen. <i>(" . basename(__FILE__) . ")</i></p>\n";
                    unset($PDO, $PDOStatement);



                    header(header:"Location: ../.././user/userData.php");
                }//PROCESS FORM EDIT USER DATA END
