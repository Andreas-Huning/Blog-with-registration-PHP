<?php
declare(strict_types=1);
namespace Lib\Repository;
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');

use Lib\Model\UserState;

if( DEBUG OR DEBUG_V OR DEBUG_F OR DEBUG_DB OR DEBUG_C OR DEBUG_CC ) 
{
if(DEBUG) 		    echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Output-Buffering ist Aktiv. <i>(" . basename(__FILE__) . ")</i></p>\n";  
                        ob_start();
}

class UserStateRepository
{
    protected \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

                #*******************************************#
				#********** FIND USERSTATUS BY ID **********#
				#*******************************************# 


                public function findByID(int $userStatesId): ?UserState
                {
//if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                    // Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
//if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen: <i>(" . basename(__FILE__) . ")</i></p>\n";						

                    $sql				=	'SELECT * FROM userstates 
                                            WHERE userStatesId  = :userStatesId ';                                            

                    $placeholders 	= array('userStatesId'=>$userStatesId);

                    // Schritt 3 DB: Prepared Statements:
                    try {
                        // Prepare: SQL-Statement vorbereiten
                        $PDOStatement = $this->db->prepare($sql);
                        
                        // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                        $PDOStatement->execute($placeholders);
                        
                    } catch(PDOException $error) {
//if(DEBUG) 		        echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                    }

                    $userStausArray = $PDOStatement -> fetch(\PDO::FETCH_ASSOC);

// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$usersArray <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($userStausArray);					
// if(DEBUG_V)	        echo "</pre>";


                    if($userStausArray){

                        $userStatus = $this->mapUserStatusToModel($userStausArray);

// if(DEBUG_V)	            echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$user <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	            print_r($userStatus);					
// if(DEBUG_V)	            echo "</pre>";

                        return $userStatus;
                    }else{
                        return null;
                    }

                }//FIND USERSTATUS BY ID END


   				#********************************************#
				#********** MAP USERSATUS TO MODEL **********#
				#********************************************#	


                public 	function mapUserStatusToModel($userStausArray)
                {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";

                    //$userStatesLabel, $userStatesId
                    
                    $userStatus = new UserState();
                    $userStatus -> setUserStatesId($userStausArray['userStatesId']); 
                    $userStatus -> setUserStatesLabel($userStausArray['userStatesLabel']); 
                                        


// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$userStatus <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($userStatus);					
// if(DEBUG_V)	        echo "</pre>";
      
                    return $userStatus;
                }//mapUserStatusToMode END
}