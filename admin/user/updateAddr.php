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
                    $adrNameForm            = sanitizeString($request->get('adrNameForm')) ;   
                    $userStreetForm         = sanitizeString($request->get('userStreetForm')) ;   
                    $userStreetNrForm       = sanitizeString($request->get('userStreetNrForm')) ;     
                    $userZipCodeForm        = sanitizeString($request->get('userZipCodeForm')) ; 
                    $userCityForm           = sanitizeString($request->get('userCityForm')) ; 
                    $userId                 = $_SESSION['ID']; 

if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$adrNameForm: $adrNameForm <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userStreetForm: $userStreetForm <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userStreetNrForm: $userStreetNrForm <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userZipCodeForm: $userZipCodeForm <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userCityForm: $userCityForm <i>(" . basename(__FILE__) . ")</i></p>\n";

if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userId: $userId <i>(" . basename(__FILE__) . ")</i></p>\n";
                
                    // DB-Verbindung Öffnen
                    $PDO = dbConnect();

                    $sql = 'SELECT COUNT(adrName) 
                            FROM adr 
                            WHERE userId = :userId
                            AND adrName =:adrName';

                    $placeholders 	= array('userId' => $userId,
                                            'adrName'=>$adrNameForm
                                        );

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
                        $sql = 'INSERT INTO adr
                        (adrName, adrStreet, adrStreetNr,adrZipCode,adrCity,userId )
                        VALUES
                        (:adrName, :adrStreet,:adrStreetNr,:adrZipCode,:adrCity,:userId)';

                    }else{
                        $sql = 'UPDATE adr 
                        SET
                        adrStreet       = :adrStreet, 
                        adrStreetNr     = :adrStreetNr,
                        adrZipCode      = :adrZipCode,
                        adrCity         = :adrCity
                        WHERE userId    = :userId
                        AND adrName     = :adrName';
                    }
                    $placeholders = array( 'adrName'        => $adrNameForm,
                                            'adrStreet'     => $userStreetForm,
                                            'adrStreetNr'   => $userStreetNrForm, 
                                            'adrZipCode'    => $userZipCodeForm, 
                                            'adrCity'       => $userCityForm, 
                                            'userId'        => $userId
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
if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$rowCount: $rowCount <i>(" . basename(__FILE__) . ")</i></p>\n";

                    // DB-Verbindung schließen 
// if(DEBUG_DB)     echo "<p class='debug db'><b>Line " . __LINE__ . "</b>: DB-Verbindung wird geschlossen. <i>(" . basename(__FILE__) . ")</i></p>\n";
                    unset($PDO, $PDOStatement);

                    header(header:"Location: ../.././user/userAddr.php");

                }