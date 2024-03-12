<?php
declare(strict_types=1);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');

use Lib\Common\Request;
use Lib\Model\Post;
use Lib\repository\PostRepository;
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

                    $postTitelForm  = sanitizeString($request->get('postTitelForm')) ;  
                    $postTextForm   = sanitizeString($request->get('postTextForm')) ;
                    $accountId    = $_SESSION['AccountId'];  

if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$postTitelForm: $postTitelForm <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$postTextForm: $postTextForm <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$accountId: $accountId <i>(" . basename(__FILE__) . ")</i></p>\n";

                    $isFormValid = true;

                    //Daten Validieren
                    $errorPostTitelForm     = validateInputString($postTitelForm,minLength:4);
                    $errorPostTextForm      = validateInputString($postTextForm,minLength:4, maxLength:10000);


                        #*******************************************#
                        #********** FORM VALIDATION START **********#
                        #*******************************************#


                    if( $errorPostTitelForm !== NULL OR $errorPostTextForm !== NULL)
                    {

if(DEBUG_V)	            echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$errorPostTitelForm: $errorPostTitelForm <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	            echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$errorPostTextForm: $errorPostTextForm <i>(" . basename(__FILE__) . ")</i></p>\n";

                        $errorMessages['Titel'] = $errorPostTitelForm;
                        $errorMessages['Text']  = $errorPostTextForm;
                        $isFormValid = false; 

                    }else
                    {
if(DEBUG) 				echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Das Formular enthält keine Fehler. <i>(" . basename(__FILE__) . ")</i></p>\n";									
                       
                        #****************************************#
                        #********** IMAGE UPLOAD START **********#
                        #****************************************#

if(DEBUG_V)	            echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$FILES <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_V)	            print_r($_FILES);					
if(DEBUG_V)	            echo "</pre>";

                        if( $_FILES['postImageForm']['tmp_name'] === '' ) 
                        {
                            // Kein Bild
if(DEBUG) 					    echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Kein Bild gefunden. <i>(" . basename(__FILE__) . ")</i></p>\n"; 
                        $errorMessages['image']  = 'Bitte wählen Sie ein Bild aus';
                        $isFormValid = false; 
                        } else {
                            // Bild gefunden
if(DEBUG) 					echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Bild gefunden. <i>(" . basename(__FILE__) . ")</i></p>\n"; 

                            $validateImageUploadReturnArray = validateImageUpload($_FILES['postImageForm']['tmp_name']);

if(DEBUG_V) 				echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$validateImageUploadReturnArray <i>(" . basename(__FILE__) . ")</i>:<br>\n"; 
if(DEBUG_V) 				print_r($validateImageUploadReturnArray); 
if(DEBUG_V) 				echo "</pre>";
                                
                            #********** VALIDATE IMAGE UPLOAD **********#
                            if( $validateImageUploadReturnArray['imageError'] !== NULL )
                            {
if(DEBUG)						echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER beim Bildupload: '<i>$validateImageUploadReturnArray[imageError]</i>' <i>(" . basename(__FILE__) . ")</i></p>\n";				
                                $errorMessages['image'] = $validateImageUploadReturnArray['imageError'];
                                $isFormValid = false; 
                            }else{
                                //ERFOLGSFALL
                                $imagePath = $validateImageUploadReturnArray['imagePath'];
if(DEBUG)						echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Bild erfolgreich unter '<i>$imagePath</i>' auf den Server geladen. <i>(" . basename(__FILE__) . ")</i></p>\n";				
                            
                                // Save Post to DB $postTitelForm,$postTextForm,$accountId,$imagePath
                                $newPost = new Post();
                                $newPost->setPostTitel($postTitelForm);
                                $newPost->setPostText($postTextForm);
                                $newPost->setPostImagePath($imagePath);
                                $newPost->setAccountId($accountId);

if(DEBUG_V) 				    echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$newPost <i>(" . basename(__FILE__) . ")</i>:<br>\n"; 
if(DEBUG_V) 				    print_r($newPost); 
if(DEBUG_V) 				    echo "</pre>";


                                $pdo = dbConnect();
                                $postRepository = new PostRepository($pdo);
                                $rowCount = $postRepository->savePost($newPost);
                                $newPostID = $pdo->lastInsertID();

if(DEBUG_V)	                    echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$rowCount: $rowCount <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)	                    echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$newPostID: $newPostID <i>(" . basename(__FILE__) . ")</i></p>\n";
                              
                                if($rowCount !==1)
                                {
if(DEBUG)	                        echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER: Keine Daten geändert <i>(" . basename(__FILE__) . ")</i></p>\n";				

                                }else
                                {
if(DEBUG)	                        echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Post erfolgreich unter ID $newPostID angelegt<i>(" . basename(__FILE__) . ")</i></p>\n";				
                                    
                                    
                                }

                            }//VALIDATE IMAGE UPLOAD END

                        }//IMAGE UPLOAD START END

                    }//FORM VALIDATION END

                    
                    if( $isFormValid === false ){
                        $request->copyToSession();
                        $_SESSION['error'] = $errorMessages;
                        header(header:"Location: ../.././user/createPost.php");
                    }else{
                        header(header:"Location: ../.././index.php");
                    }

                }