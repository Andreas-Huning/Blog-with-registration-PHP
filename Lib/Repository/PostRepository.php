<?php
declare(strict_types=1);
namespace Lib\Repository;
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');

use Lib\Model\Post;
use Lib\Model\Account;

                if( DEBUG OR DEBUG_V OR DEBUG_F OR DEBUG_DB OR DEBUG_C OR DEBUG_CC ) 
                {
if(DEBUG) 		    echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Output-Buffering ist Aktiv. <i>(" . basename(__FILE__) . ")</i></p>\n";  
                    ob_start();
                }

                class PostRepository
                {
                    protected \PDO $db;
                    protected ?AccountRepository  $accountRepository;

                    public function __construct(\PDO $db, ?AccountRepository $accountRepository = NULL)
                    {
                        $this->db = $db;
                        $this->accountRepository = $accountRepository;
                    }

                    #********************************#
                    #********** SAVE POST  **********#
                    #********************************# 


                public function savePost( Post $postArray ):int
                {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                    
                    // Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen: <i>(" . basename(__FILE__) . ")</i></p>\n";						
                  
                 
                    // Save Post to DB $postTitelForm,$postTextForm,$accountId,$imagePath
                    $sql				=	'INSERT INTO posts 
                                            (postTitel, postText, postImagePath,accountId)
                                            VALUES
                                            (:postTitel, :postText, :postImagePath,:accountId)
                                            ';                                            

                    $placeholders 	= array('postTitel'     =>$postArray->getPostTitel(),
                                            'postText'     =>$postArray->getPostText(),
                                            'postImagePath' =>$postArray->getPostImagePath(),
                                            'accountId'     =>$postArray->getAccountId()
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
                        $newPostID = $this->db->lastInsertID();

if(DEBUG) 				echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Userdatensatz erfolgreich unter ID $newPostID  gespeichert. <i>(" . basename(__FILE__) . ")</i></p>\n";					
                                                            
                        return $rowCount;
                    }

                }//SAVE POST END


                #************************************#
				#********** FIND ALL POSTS **********#
				#************************************#
                
                
                public function findAllPost(): ?array
                {
if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";

                    // Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen
if(DEBUG)			echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Schritt 2 DB: SQL-Statement und Placeholder-Array erstellen: <i>(" . basename(__FILE__) . ")</i></p>\n";						

                    $sql				=	'SELECT * FROM posts
                                            INNER JOIN account USING(accountId)
                                            ORDER BY postDate DESC';                                            
                
                    $placeholders 	= array();
                    
                    // Schritt 3 DB: Prepared Statements:
                    try {
                        // Prepare: SQL-Statement vorbereiten
                        $PDOStatement = $this->db->prepare($sql);
                        
                        // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                        $PDOStatement->execute($placeholders);
                        
                    } catch(PDOException $error) {
//if(DEBUG) 		    echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                    }
                    $postDataArray = $PDOStatement -> fetchAll(\PDO::FETCH_ASSOC);

// if(DEBUG_V)	            echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$postDataArray <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	            print_r($postDataArray);					
// if(DEBUG_V)	            echo "</pre>";


                    if($postDataArray){
//if(DEBUG)	            echo "<p class='debug'><b>Line " . __LINE__ . "</b>: postDataArray. <i>(" . basename(__FILE__) . ")</i></p>\n";

                        foreach($postDataArray as $postData)
                        {
                            $post = $this->mapPostToModel($postData);
                            $posts[]=$post;            
                        }
                        
// if(DEBUG_V)	            echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$postDataArray <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	            print_r($postDataArray);					
// if(DEBUG_V)	            echo "</pre>";

                        return $posts;

                    }else{
                        return null;
                    }

                }// Find all END
  

                #***************************************#
				#********** MAP POST TO MODEL **********#
				#***************************************#	


                public 	function mapPostToModel($postData)
                {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";

                    $postDate = isoToEuDateTime($postData['postDate'])['date']." - ".isoToEuDateTime($postData['postDate'])['time']."Uhr";
                   
                    $post = new Post();
                    $post->setPostId($postData['postId']);
                    $post->setPostTitel($postData['postTitel']);
                    $post->setPostText($postData['postText']);
                    $post->setPostImagePath($postData['postImagePath']);
                    $post->setAccountId($postData['accountId']);
                    $post->setPostDate($postDate);


                   $accountName = $this->accountRepository->findAccountByAccountID($postData['accountId']);
                    
                    $post->setAccount($accountName);

// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$accountName <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($accountName);					
// if(DEBUG_V)	        echo "</pre>";

// if(DEBUG_V)	        echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$post <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	        print_r($post);					
// if(DEBUG_V)	        echo "</pre>";
       
                    return $post;
                }//MAP USERS END






}