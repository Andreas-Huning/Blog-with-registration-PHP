<?php
declare(strict_types=1);
namespace Lib\Repository;
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');

if( DEBUG OR DEBUG_V OR DEBUG_F OR DEBUG_DB OR DEBUG_C OR DEBUG_CC ) 
{
if(DEBUG) 		    echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Output-Buffering ist Aktiv. <i>(" . basename(__FILE__) . ")</i></p>\n";  
                        ob_start();
}

use Lib\Model\AccountRole;

            class AccountRoleRepository
            {
                protected \PDO $db;

                public function __construct(\PDO $db)
                {
                    $this->db = $db;
                }


                #***************************************************#
                #********** FIND ACCOUNTROLE BY ACCOUNTID **********#
                #****************************************************# 


                public function findAccountRoleByAccountID(int $accRoleId): ?AccountRole
                {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                    // Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
// if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen: <i>(" . basename(__FILE__) . ")</i></p>\n";						

                    $sql				=	'SELECT * FROM accrole 
                                            WHERE accRoleId = :accRoleId';                                            

                    $placeholders 	= array('accRoleId'=>$accRoleId);

                    // Schritt 3 DB: Prepared Statements:
                    try {
                        // Prepare: SQL-Statement vorbereiten
                        $PDOStatement = $this->db->prepare($sql);
                        
                        // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                        $PDOStatement->execute($placeholders);
                        
                    } catch(PDOException $error) {
if(DEBUG) 		        echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                    }

                    $accountArray = $PDOStatement -> fetch(\PDO::FETCH_ASSOC);

// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$accountArray <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($accountArray);					
// if(DEBUG_V)	        echo "</pre>";


                    if($accountArray){

                        $account = $this->mapAccountRoleToModel($accountArray);

// if(DEBUG_V)	            echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$account <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	            print_r($account);					
// if(DEBUG_V)	            echo "</pre>";

                        return $account;
                    }else{
                        return null;
                        }

                }//FIND ACCOUNT BY ID END

                #**********************************************#
				#********** MAP ACCOUNTROLE TO MODEL **********#
				#**********************************************#	


                public 	function mapAccountRoleToModel($usersArray):AccountRole
                {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
 
// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$usersArray <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($usersArray);					
// if(DEBUG_V)	        echo "</pre>";


                    $accountRole = new AccountRole();
                    $accountRole->setAccRoleId($usersArray['accRoleId']);
                    $accountRole->setAccRoleName($usersArray['accRoleName']);

// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$accountRole <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($accountRole);					
// if(DEBUG_V)	        echo "</pre>";
       
                    return $accountRole;
                }//mapAccountToModel END

            }
