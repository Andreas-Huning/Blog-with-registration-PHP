<?php
declare(strict_types=1);
namespace Lib\Repository;
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');

use Lib\Model\UserData;

if( DEBUG OR DEBUG_V OR DEBUG_F OR DEBUG_DB OR DEBUG_C OR DEBUG_CC ) 
{
if(DEBUG) 		    echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Output-Buffering ist Aktiv. <i>(" . basename(__FILE__) . ")</i></p>\n";  
                        ob_start();
}

class UserDataRepository
{
    protected \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;

    }

                #*****************************************#
				#********** FIND USERDATA BY ID **********#
				#*****************************************# 


                public function findUserDataByID(int $userId): ?UserData
                {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                    // Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
// if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen: <i>(" . basename(__FILE__) . ")</i></p>\n";						

                    $sql				=	'SELECT * FROM userdata 
                                            WHERE userId = :userId';                                            

                    $placeholders 	= array('userId'=>$userId);

                    // Schritt 3 DB: Prepared Statements:
                    try {
                        // Prepare: SQL-Statement vorbereiten
                        $PDOStatement = $this->db->prepare($sql);
                        
                        // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                        $PDOStatement->execute($placeholders);
                        
                    } catch(PDOException $error) {
// if(DEBUG) 		        echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                    }

                    $userDataArray = $PDOStatement -> fetch(\PDO::FETCH_ASSOC);

// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$userDataArray <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($userDataArray);					
// if(DEBUG_V)	        echo "</pre>";


                    if($userDataArray){

                        $userData = $this->mapUserDataToModel($userDataArray);

// if(DEBUG_V)	            echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$userData <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	            print_r($userData);					
// if(DEBUG_V)	            echo "</pre>";

                        return $userData;
                    }else{
                        return null;
                    }

                }//FIND USERDATA BY ID END


                #***************************************#
				#********** MAP USER TO MODEL **********#
				#***************************************#	


                public 	function mapUserDataToModel($userDataArray)
                {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";

                    //$userName, $userPassword, $userEmail, $userStatesId, $userId, $userAvatarPath, $userRegTimeStamp,
                    
                    $user = new UserData();
                    $user->setUserId($userDataArray['userId']);
                    $user->setUserDataBirthday($userDataArray['userDataBirthday']);
                    $user->setUserDataLastName($userDataArray['userDataLastName']);
                    $user->setUserDataFirstName($userDataArray['userDataFirstName']);                


// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$users <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($user);					
// if(DEBUG_V)	        echo "</pre>";
       
                    return $user;
                }//MAP USERS END

}