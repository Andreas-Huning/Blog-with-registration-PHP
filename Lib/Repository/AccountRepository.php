<?php
declare(strict_types=1);
namespace Lib\Repository;
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');

use Lib\Model\Account;
use Lib\Model\AccountRole;

if( DEBUG OR DEBUG_V OR DEBUG_F OR DEBUG_DB OR DEBUG_C OR DEBUG_CC ) 
{
if(DEBUG) 		    echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Output-Buffering ist Aktiv. <i>(" . basename(__FILE__) . ")</i></p>\n";  
                        ob_start();
}

            class AccountRepository
            {
                protected \PDO $db;
                protected ?AccountRoleRepository $accountRoleRepository;
            
                public function __construct(\PDO $db, ?AccountRoleRepository $accountRoleRepository = NULL)
                {
                    $this->db = $db;
                    $this->accountRoleRepository = $accountRoleRepository;
                }

                #****************************************#
                #********** FIND ACCOUNT BY ID **********#
                #****************************************# 


                public function findAccountByID(int $userId): ?Account
                {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                    // Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
// if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen: <i>(" . basename(__FILE__) . ")</i></p>\n";						

                    $sql				=	'SELECT * FROM account 
                                            INNER JOIN (accrole) USING (accRoleId)
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

                        $user = $this->mapAccountToModel($usersArray);

// if(DEBUG_V)	            echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$user <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	            print_r($user);					
// if(DEBUG_V)	            echo "</pre>";

                        return $user;
                    }else{
                        return null;
                        }

                }//FIND ACCOUNT BY ID END




                #***********************************************#
                #********** FIND ACCOUNT BY ACCOUNTID **********#
                #***********************************************# 


                public function findAccountByAccountID(int $accountId): ?Account
                {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                    // Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen: <i>(" . basename(__FILE__) . ")</i></p>\n";						

if(DEBUG_V)	echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$accountId: $accountId <i>(" . basename(__FILE__) . ")</i></p>\n";


                    $sql				=	'SELECT * FROM account 
                                            INNER JOIN (accrole) USING (accRoleId)
                                            WHERE accountId = :accountId';                                            

                    $placeholders 	= array('accountId'=>$accountId);

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

                        $user = $this->mapAccountToModel($usersArray);

// if(DEBUG_V)	            echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$user <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	            print_r($user);					
// if(DEBUG_V)	            echo "</pre>";

                        return $user;
                    }else{
                        return null;
                        }

                }//FIND ACCOUNT BY ID END
















                #******************************************#
                #********** FIND ACCOUNT BY NAME **********#
                #******************************************# 


                public function findAccountByName(string $accountName): ?Account
                {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                    // Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
// if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen: <i>(" . basename(__FILE__) . ")</i></p>\n";						

                    $sql				=	'SELECT * FROM account 
                                            INNER JOIN (accrole) USING (accRoleId)
                                            WHERE accountName = :accountName';                                            

                    $placeholders 	= array('accountName'=>$accountName);

                    // Schritt 3 DB: Prepared Statements:
                    try {
                        // Prepare: SQL-Statement vorbereiten
                        $PDOStatement = $this->db->prepare($sql);
                        
                        // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                        $PDOStatement->execute($placeholders);
                        
                    } catch(PDOException $error) {
// if(DEBUG) 		        echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                    }

                    $accountArray = $PDOStatement -> fetch(\PDO::FETCH_ASSOC);

// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$accountArray <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($accountArray);					
// if(DEBUG_V)	        echo "</pre>";


                    if($accountArray){

                        $account = $this->mapAccountToModel($accountArray);

// if(DEBUG_V)	            echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$accountArray <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	            print_r($accountArray);					
// if(DEBUG_V)	            echo "</pre>";

                        return $account;
                    }else{
                        return null;
                        }

                }//FIND ACCOUNT BY ID END


                #**********************************#
				#********** ADD ACCOUNT  **********#
				#**********************************# 


                public function addAccount( Account $accountArray ): int
                {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                    
                    // Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
// if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen: <i>(" . basename(__FILE__) . ")</i></p>\n";						


                    $sql				=	'INSERT INTO account 
                                            (accountName, userId)
                                            VALUES
                                            (:accountName, :userId)
                                            ';                                            

                    $placeholders 	= array('accountName'   =>$accountArray->getAccountName(),
                                            'userId'        =>$accountArray->getUserId()
                                            );
// if(DEBUG_V)	    echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$sql: $sql <i>(" . basename(__FILE__) . ")</i></p>\n";

 
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
if(DEBUG) 			    echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Es wurden keine Daten geändert <i>(" . basename(__FILE__) . ")</i></p>\n";					

                        return $rowCount;
                    }else{
                        $newAccountID = $this->db->lastInsertID();

if(DEBUG) 				echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Account erfolgreich unter ID $newAccountID angelegt. <i>(" . basename(__FILE__) . ")</i></p>\n";					
                                                            
                        return $rowCount;
                    }

                }//ADD ACCOUNT END


                #******************************************#
				#********** MAP ACCOUNT TO MODEL **********#
				#******************************************#	


                public 	function mapAccountToModel($accountArray)
                {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";

// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$accountArray <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($accountArray);					
// if(DEBUG_V)	        echo "</pre>";
                  
                    $account = new Account();
                    $account->setUserId($accountArray['userId']);
                    $account->setAccRoleId($accountArray['accRoleId']);
                    $account->setAccountName($accountArray['accountName']);
                    $account->setAccountId($accountArray['accountId']);
                 
                    $accountName = $this->accountRoleRepository->findAccountRoleByAccountID($accountArray['accRoleId']); 
                    

// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$accountName <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($accountName);					
// if(DEBUG_V)	        echo "</pre>";

                    $account->setAccountRole($accountName);                   

// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$account <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($account);					
// if(DEBUG_V)	        echo "</pre>";

                    return $account;
                }//mapAccountToModel END

            }//class AccountRepository END