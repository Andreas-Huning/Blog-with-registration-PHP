<?php
declare(strict_types=1);
namespace Lib\Repository;
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');

use Lib\Model\User;
use Lib\Model\UserState;

if( DEBUG OR DEBUG_V OR DEBUG_F OR DEBUG_DB OR DEBUG_C OR DEBUG_CC ) 
{
if(DEBUG) 		    echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Output-Buffering ist Aktiv. <i>(" . basename(__FILE__) . ")</i></p>\n";  
                        ob_start();
}

class UserRepository
{
    protected \PDO $db;
    protected ?UserStateRepository $userStateRepository;

    public function __construct(\PDO $db, ?UserStateRepository $userStateRepository = NULL)
    {
        $this->db = $db;
        $this->userStateRepository = $userStateRepository;

    }

                #******************************************#
				#********** CHECK EXISTING EMAIL **********#
				#******************************************# 


                public function countEmail(string $userEmail): ?int
                {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                    // Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
// if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen: <i>(" . basename(__FILE__) . ")</i></p>\n";						

                    $sql				=	'SELECT COUNT(userEmail) FROM users 
                                            WHERE userEmail=:userEmail';                                            

                    $placeholders 	= array('userEmail' => $userEmail);

                    // Schritt 3 DB: Prepared Statements:
                    try {
                        // Prepare: SQL-Statement vorbereiten
                        $PDOStatement = $this->db->prepare($sql);
                        
                        // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                        $PDOStatement->execute($placeholders);
                        
                    } catch(PDOException $error) {
// if(DEBUG) 		        echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                    }

                    $count = $PDOStatement->fetchColumn();
if(DEBUG_V)			echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$count: $count <i>(" . basename(__FILE__) . ")</i></p>\n";

                        return $count;

                }//CHECK EXISTING EMAIL END




                #************************************************#
				#********** CHECK EXISTING EMAIL BY ID **********#
				#************************************************# 


                public function countEmailByID(string $userEmail, int $userId): ?int
                {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                    // Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
// if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen: <i>(" . basename(__FILE__) . ")</i></p>\n";						

                    $sql				=	'SELECT COUNT(userEmail) FROM users 
                                            WHERE userEmail=:userEmail
                                            AND userId != :userId';                                            

                    $placeholders 	= array('userEmail' => $userEmail,
                                            'userId' => $userId);

                    // Schritt 3 DB: Prepared Statements:
                    try {
                        // Prepare: SQL-Statement vorbereiten
                        $PDOStatement = $this->db->prepare($sql);
                        
                        // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                        $PDOStatement->execute($placeholders);
                        
                    } catch(PDOException $error) {
// if(DEBUG) 		        echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                    }
 
                    $count = $PDOStatement->fetchColumn();
// if(DEBUG_V)			echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$count: $count <i>(" . basename(__FILE__) . ")</i></p>\n";

                        return $count;

                }//CHECK EXISTING EMAIL BY ID END



                #****************************************#
				#********** FIND USER BY EMAIL **********#
				#****************************************# 

                public function findByEmail(string $email): ?User
                {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                    // Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
// if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen: <i>(" . basename(__FILE__) . ")</i></p>\n";						

                    $sql				=	'SELECT * FROM users 
                                            INNER JOIN  userstates USING ( userStatesId )
                                            INNER JOIN  account USING ( userId )
                                            WHERE userEmail = :userEmail';                                            

                    $placeholders 	= array('userEmail'=>$email);

                    // Schritt 3 DB: Prepared Statements:
                    try {
                        // Prepare: SQL-Statement vorbereiten
                        $PDOStatement = $this->db->prepare($sql);
                        
                        // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                        $PDOStatement->execute($placeholders);
                        
                    } catch(PDOException $error) {
// if(DEBUG) 		        echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                    }

                    $usersArray = $PDOStatement -> fetch(\PDO::FETCH_ASSOC);

// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$usersArray <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($usersArray);					
// if(DEBUG_V)	        echo "</pre>";


                    if($usersArray){

                        $user = $this->mapUserToModel($usersArray);

// if(DEBUG_V)	            echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$user <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	            print_r($user);					
// if(DEBUG_V)	            echo "</pre>";

                        return $user;
                    }else{
                        return null;
                    }

                }//FIND USER BY EMAIL END



                #*************************************#
				#********** FIND USER BY ID **********#
				#*************************************# 


                public function findByID(int $userId): ?User
                {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                    // Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
// if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen: <i>(" . basename(__FILE__) . ")</i></p>\n";						

                    $sql				=	'SELECT * FROM users 
                                            INNER JOIN  userstates USING ( userStatesId )
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

                    $usersArray = $PDOStatement -> fetch(\PDO::FETCH_ASSOC);

// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$usersArray <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($usersArray);					
// if(DEBUG_V)	        echo "</pre>";


                    if($usersArray){

                        $user = $this->mapUserToModel($usersArray);

// if(DEBUG_V)	            echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$user <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	            print_r($user);					
// if(DEBUG_V)	            echo "</pre>";

                        return $user;
                    }else{
                        return null;
                    }

                }//FIND USER BY ID END


                #***************************************#
				#********** UPDATE USER BY ID **********#
				#***************************************# 


                public function updateByID(User $usersArray ): null|string|int
                {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                    
                    // Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
// if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen: <i>(" . basename(__FILE__) . ")</i></p>\n";						

                    $userId = $usersArray->getUserId();
// if(DEBUG_V)	echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userId: $userId <i>(" . basename(__FILE__) . ")</i></p>\n";
                 
                    //$userName, $userPassword, $userEmail, $userStatesId, $userId, $userAvatarPath, $userRegTimeStamp
                    $sql				=	'UPDATE users 
                                            SET
                                            userPassword	= :userPassword,
                                            userEmail		= :userEmail,
                                            userStatesId	= :userStatesId,
                                            userAvatarPath	= :userAvatarPath                                            
                                            WHERE userId    = :userId';                                            

                    $placeholders 	= array(
                                            'userPassword'  =>$usersArray->getUserPassword(),
                                            'userEmail'     =>$usersArray->getUserEmail(),
                                            'userStatesId'  =>$usersArray->getUserStatesId(),
                                            'userAvatarPath'=>$usersArray->getUserAvatarPath(),
                                            'userId'        =>$usersArray->getUserId()
                                        );
// if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$sql: $sql <i>(" . basename(__FILE__) . ")</i></p>\n";

 
                    // Schritt 3 DB: Prepared Statements:
                    try {
                        // Prepare: SQL-Statement vorbereiten
                        $PDOStatement = $this->db->prepare($sql);
                        
                        // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                        $PDOStatement->execute($placeholders);
                        
                    } catch(PDOException $error) {
// if(DEBUG) 		        echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                    }

                    $rowCount = $PDOStatement->rowCount();
// if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$rowCount: $rowCount <i>(" . basename(__FILE__) . ")</i></p>\n";



                    if($rowCount !==1)
                    {
// if(DEBUG) 			    echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Es wurden keine Daten geändert <i>(" . basename(__FILE__) . ")</i></p>\n";					

                        return $rowCount;
                    }else{
                        $newUserID = $this->db->lastInsertID();

// if(DEBUG) 				echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Userdatensatz erfolgreich geändert. <i>(" . basename(__FILE__) . ")</i></p>\n";					
                                                            
                        return $newUserID;
                    }

                }//FIND USER BY ID END


                #************************************#
				#********** REGISTER USER  **********#
				#************************************# 


                public function registerUser(User $usersArray ): null|string|int
                {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                    
                    // Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
// if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen: <i>(" . basename(__FILE__) . ")</i></p>\n";						

                   
// if(DEBUG_V)	echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userId: $userId <i>(" . basename(__FILE__) . ")</i></p>\n";
                 
                    //$userName, $userPassword, $userEmail, $userStatesId, $userId, $userAvatarPath, $userRegTimeStamp
                    $sql				=	'INSERT INTO users 
                                            (userPassword, userEmail, userRegHash)
                                            VALUES
                                            (:userPassword, :userEmail, :userRegHash)
                                            ';                                            

                    $placeholders 	= array('userEmail'      =>$usersArray->getUserEmail(),
                                            'userPassword'  =>$usersArray->getUserPassword(),
                                            'userRegHash'     =>$usersArray->getUserRegHash()
                                        );
// if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$sql: $sql <i>(" . basename(__FILE__) . ")</i></p>\n";

 
                    // Schritt 3 DB: Prepared Statements:
                    try {
                        // Prepare: SQL-Statement vorbereiten
                        $PDOStatement = $this->db->prepare($sql);
                        
                        // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                        $PDOStatement->execute($placeholders);
                        
                    } catch(PDOException $error) {
if(DEBUG) 		        echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                    }

                    $rowCount = $PDOStatement->rowCount();
if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$rowCount: $rowCount <i>(" . basename(__FILE__) . ")</i></p>\n";



                    if($rowCount !==1)
                    {
if(DEBUG) 			    echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Es wurden keine Daten gespeichert <i>(" . basename(__FILE__) . ")</i></p>\n";					

                        return $rowCount;
                    }else{
                        $newUserID = $this->db->lastInsertID();

if(DEBUG) 				echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Userdatensatz erfolgreich unter ID $newUserID  gespeichert. <i>(" . basename(__FILE__) . ")</i></p>\n";					
                                                            
                        return $rowCount;
                    }

                }//REGISTER USER END



                #*******************************************#
				#********** FIND USER BY HASHREG  **********#
				#*******************************************# 


                public function findUserByHash(string $regHash ): User
                {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                    
                    // Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen: <i>(" . basename(__FILE__) . ")</i></p>\n";						

                    $sql				=	'SELECT * FROM users 
                                            WHERE
                                            userRegHash =:userRegHash
                                            ';                                            

                    $placeholders 	= array('userRegHash'=> $regHash);

// if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$sql: $sql <i>(" . basename(__FILE__) . ")</i></p>\n";

 
                    // Schritt 3 DB: Prepared Statements:
                    try {
                        // Prepare: SQL-Statement vorbereiten
                        $PDOStatement = $this->db->prepare($sql);
                        
                        // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                        $PDOStatement->execute($placeholders);
                        
                    } catch(PDOException $error) {
if(DEBUG) 		        echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                    }

                    $usersArray = $PDOStatement -> fetch(\PDO::FETCH_ASSOC);

// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$usersArray <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($usersArray);					
// if(DEBUG_V)	        echo "</pre>";


                    if($usersArray){

                        $user = $this->mapUserToModel($usersArray); 

// if(DEBUG_V)	            echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$user <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	            print_r($user);					
// if(DEBUG_V)	            echo "</pre>";

                        return $user;
                    }else{
                        return null;
                    }

                }//FIND USER BA HASHREG END


   				#***************************************#
				#********** MAP USER TO MODEL **********#
				#***************************************#	


                public 	function mapUserToModel($usersArray)
                {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";

                    //$userName, $userPassword, $userEmail, $userStatesId, $userId, $userAvatarPath, $userRegTimeStamp,
                    
                    $user = new User();
                    $user->setUserId($usersArray['userId']);
                    $user->setUserPassword($usersArray['userPassword']);
                    $user->setUserStatesId($usersArray['userStatesId']);
                    $user->setUserAvatarPath($usersArray['userAvatarPath']);
                    $user->setUserRegTimeStamp($usersArray['userRegTimeStamp']);
                    $user->setUserEmail($usersArray['userEmail']);                  
                                    
                    $userStatus = $this->userStateRepository->findByID($usersArray['userStatesId']);
                    // $userStatus = $this->userStateRepository->mapUserStatusToModel($usersArray);
                    $user -> setUserState($userStatus);  

// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$users <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($user);					
// if(DEBUG_V)	        echo "</pre>";
       
                    return $user;
                }//MAP USERS END




                

}