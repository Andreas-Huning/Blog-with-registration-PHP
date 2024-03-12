<?php

declare(strict_types=1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');

use Lib\Common\Request;
$request = new Request();

                if($request->isGet())
                {

// if(DEBUG_V)         echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$request <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($request);					
// if(DEBUG_V)	        echo "</pre>";

                    if( $request->get('action') !== NULL  ) 
                    {
if(DEBUG)		        echo "<p class='debug'>🧻 <b>Line " . __LINE__ . "</b>: URL-Parameter 'action' wurde übergeben. <i>(" . basename(__FILE__) . ")</i></p>\n";										
    
                        $action = sanitizeString($request->get('action')) ;

if(DEBUG_V)	            echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$action: $action <i>(" . basename(__FILE__) . ")</i></p>\n";
    
                        if($request->get('action') === 'showprofil')
                        {
if(DEBUG)			        echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Action showprofil wurde übergeben... <i>(" . basename(__FILE__) . ")</i></p>\n";
                        
                            $accountName = sanitizeString($request->get('user'));

if(DEBUG_V)	                echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$accountName: $accountName <i>(" . basename(__FILE__) . ")</i></p>\n";
                           
                            $PDO = dbConnect();
                            $sql = "SELECT * FROM account
                                    INNER JOIN users USING (userId)
                                    Left JOIN userdata USING (userId) 
                                    INNER JOIN accrole USING (accRoleId)                                     
                                    WHERE accountName = :accountName
                                    ";
                            $placeholders = array('accountName'=>$accountName);
                            try {
                                // Prepare: SQL-Statement vorbereiten
                                $PDOStatement = $PDO->prepare($sql);
                                
                                // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                                $PDOStatement->execute($placeholders);
                                
                            } catch(PDOException $error) {
if(DEBUG) 		                echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
                                
                            }

                            $user = $PDOStatement->fetch(PDO::FETCH_ASSOC);

// if(DEBUG_V)                 echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$user <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	                print_r($user);					
// if(DEBUG_V)	                echo "</pre>"; 

                            if(isset($user['userDataBirthday'])){
                                $userBirthday = isoToEuDateTime($user['userDataBirthday'])['date'];
                                $user['birthday']=$userBirthday;
if(DEBUG_V)	                echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userBirthday: $userBirthday <i>(" . basename(__FILE__) . ")</i></p>\n";

                            }
                           if(isset($user['userRegTimeStamp'])){
                            $userRegTime = isoToEuDateTime($user['userRegTimeStamp'])['date'];
if(DEBUG_V)	                echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userRegTime: $userRegTime <i>(" . basename(__FILE__) . ")</i></p>\n";
                            $user['userRegTime']=$userRegTime;
                           }

                            $accountId = $user['accountId'];

                            $sql = "SELECT * FROM posts                                    
                            WHERE accountId = :accountId
                            ";
                            $placeholders = array('accountId'=>$accountId);
                            try {
                                // Prepare: SQL-Statement vorbereiten
                                $PDOStatement = $PDO->prepare($sql);
                                
                                // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                                $PDOStatement->execute($placeholders);
                                
                            } catch(PDOException $error) {
if(DEBUG) 		                echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
                                
                            }

                            $posts = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);

// if(DEBUG_V)                 echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$posts <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	                print_r($posts);					
// if(DEBUG_V)	                echo "</pre>"; 

                            $user['posts'] = $posts;

// if(DEBUG_V)                 echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$user <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	                print_r($user);					
// if(DEBUG_V)	                echo "</pre>";  

                        }//SHOW PROFIL END    

                    }// ACTION END

                }// GET END

echo $twig->render('user\profil.html.twig',[ 'user'=>$user]);
