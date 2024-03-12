<?php

                declare(strict_types=1);

                require($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');

                #****************************************************#
                #********** DATENBANK USERSTATUS ERSTELLEN **********#
                #****************************************************#

                // DB-Verbindung herstellen
                $PDO = dbConnect();

                // Datenbanktabellen USERSTATES erstellen
                $sql	=	"CREATE TABLE IF NOT EXISTS userStates (
                    userStatesId INT AUTO_INCREMENT PRIMARY KEY,
                    userStatesLabel VARCHAR(255) NOT NULL)";                                           

                    $placeholders 	= array();
                try {
                    // Prepare: SQL-Statement vorbereiten
                    $PDOStatement = $PDO->prepare($sql);
                    
                    // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                    $PDOStatement->execute($placeholders);
                    
                } catch(PDOException $error) {
if(DEBUG) 		    echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
        
                }

               
                #**********************************************#
                #********** DATENBANK USER ERSTELLEN **********#
                #**********************************************#


                // Datenbanktabellen USER erstellen
                $sql	=	"CREATE TABLE IF NOT EXISTS users (
                            userId INT AUTO_INCREMENT PRIMARY KEY,
                            userEmail VARCHAR(255) NOT NULL,
                            userPassword VARCHAR(255) NOT NULL,
                            userRegHash VARCHAR(255) NULL,
                            userRegTimeStamp timestamp Null  DEFAULT CURRENT_TIMESTAMP,
                            userPwdHash VARCHAR(255) NULL,
                            userPwdTimeStamp timestamp NULL,
                            userAvatarPath VARCHAR(255) Not NULL DEFAULT '../.././public/images/avatar_dummy.png',
                            userStatesId  INT(11) NOT NULL DEFAULT 1,
                            FOREIGN KEY (userStatesId) REFERENCES userStates(userStatesId) )";                                            

                $placeholders 	= array();
                try {
                    // Prepare: SQL-Statement vorbereiten
                    $PDOStatement = $PDO->prepare($sql);
                    
                    // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                    $PDOStatement->execute($placeholders);
                    
                } catch(PDOException $error) {
if(DEBUG) 		    echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
                
                }


                #**************************************************#
                #********** DATENBANK USERDATA ERSTELLEN **********#
                #**************************************************#


                // Datenbanktabellen USER erstellen
                $sql	=	"CREATE TABLE IF NOT EXISTS userData (
                    userDataId INT AUTO_INCREMENT PRIMARY KEY,
                    userDataFirstName VARCHAR(255) NULL,
                    userDataLastName VARCHAR(255) NULL,
                    userDataBirthday VARCHAR(255) NULL,
                    userId int(11)NOT NULL,
                    FOREIGN KEY (userId) REFERENCES users(userId) )";                                            

                    $placeholders 	= array();
                try {
                    // Prepare: SQL-Statement vorbereiten
                    $PDOStatement = $PDO->prepare($sql);
                    
                    // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                    $PDOStatement->execute($placeholders);
                    
                } catch(PDOException $error) {
if(DEBUG) 		    echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
        
                }


                #**************************************************#
                #********** DATENBANK ADRESSEN ERSTELLEN **********#
                #**************************************************#


                // Datenbanktabellen ADR erstellen
                $sql	=	"CREATE TABLE IF NOT EXISTS adr (
                    adrId INT AUTO_INCREMENT PRIMARY KEY,
                    adrName VARCHAR(255) NULL,
                    adrStreet VARCHAR(255) NULL,
                    adrStreetNr VARCHAR(255) NULL,
                    adrZipCode VARCHAR(255) NULL,
                    adrCity VARCHAR(255) NULL,
                    userId int(11)NOT NULL,
                    FOREIGN KEY (userId) REFERENCES users(userId) )";                                            

                    $placeholders 	= array();
                try {
                    // Prepare: SQL-Statement vorbereiten
                    $PDOStatement = $PDO->prepare($sql);
                    
                    // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                    $PDOStatement->execute($placeholders);
                    
                } catch(PDOException $error) {
                // if(DEBUG) 		    echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                }

                #*************************************************#
                #********** DATENBANK ACCROLE ERSTELLEN **********#
                #*************************************************#


                // Datenbanktabellen ADR erstellen
                $sql	=	"CREATE TABLE IF NOT EXISTS accRole 
                (
                    accRoleId INT AUTO_INCREMENT PRIMARY KEY,
                    accRoleName VARCHAR(255) NOT NULL
                )";                                            

                    $placeholders 	= array();
                try {
                    // Prepare: SQL-Statement vorbereiten
                    $PDOStatement = $PDO->prepare($sql);
                    
                    // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                    $PDOStatement->execute($placeholders);
                    
                } catch(PDOException $error) {
if(DEBUG) 		    echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                }


                #*************************************************#
                #********** DATENBANK ACCOUNT ERSTELLEN **********#
                #*************************************************#  
                
                
                // Datenbanktabellen Account erstellen
                $sql	=	"CREATE TABLE IF NOT EXISTS account (
                    accountId INT AUTO_INCREMENT PRIMARY KEY,
                    accountName VARCHAR(255) NOT NULL,
                    accRoleId int(11) NOT NULL DEFAULT 1,
                    userId int(11) NOT NULL,                    
                    FOREIGN KEY (accRoleId) REFERENCES accRole(accRoleId),
                    FOREIGN KEY (userId) REFERENCES users(userId))";                                            

                    $placeholders 	= array();
                try {
                    // Prepare: SQL-Statement vorbereiten
                    $PDOStatement = $PDO->prepare($sql);
                    
                    // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                    $PDOStatement->execute($placeholders);
                    
                } catch(PDOException $error) {
if(DEBUG) 		    echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                }// DATENBANK ACCOUNT ERSTELLEN END


                #*******************************************************#
                #********** DATENEINTRAG USERSTATUS ERSTELLEN **********#
                #*******************************************************#


                 // Datenbankeinträge USERSTATES auslesen
                 $sql	=	'SELECT COUNT(userStatesLabel) as anzahl
                 FROM userstates';                                           

                $placeholders 	= array();
                 try {
                    // Prepare: SQL-Statement vorbereiten
                 $PDOStatement = $PDO->prepare($sql);
                
                 // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                    $PDOStatement->execute($placeholders);
                
                } catch(PDOException $error) {
if(DEBUG) 		    echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                }
                    $rowcount = $PDOStatement->fetchALL(PDO::FETCH_ASSOC);
                    $rowcount = $rowcount[0]['anzahl'];

if(DEBUG_V)	    echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$rowcount <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_V)	    print_r($rowcount);					
if(DEBUG_V)	    echo "</pre>";

                    if ($rowcount !== 0)
                    {
if(DEBUG)	        echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER: Datensätze gefunden <i>(" . basename(__FILE__) . ")</i></p>\n";				                  
                    }

                    if ($rowcount === 0)
                    {
if(DEBUG)	        echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Keine Datensätze gefunden <i>(" . basename(__FILE__) . ")</i></p>\n";				
                            
     
                    // Datenbank USERSTATES unregistred erstellen
                    $sql	=	"INSERT INTO userStates 
                                (userStatesLabel )
                                VALUES 
                                (:userStatesLabel)";                                           

                        $placeholders 	= array('userStatesLabel'=>'unregistred');
                    try {
                        // Prepare: SQL-Statement vorbereiten
                        $PDOStatement = $PDO->prepare($sql);
                        
                        // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                        $PDOStatement->execute($placeholders);
                        
                    } catch(PDOException $error) {
if(DEBUG) 		    echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                    }
                    $rowCount = $PDOStatement->rowCount();

if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$rowCount: $rowCount <i>(" . basename(__FILE__) . ")</i></p>\n";

                    if($rowCount !==1)
                    {
if(DEBUG)	            echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Status unregistred konnte nicht angelegt werden <i>(" . basename(__FILE__) . ")</i></p>\n";				

                    }else
                    {
if(DEBUG)	            echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Status unregistred erfolgreich angelegt <i>(" . basename(__FILE__) . ")</i></p>\n";				


                    // Datenbank USERSTATES open erstellen
                    $sql	=	"INSERT INTO userStates 
                    (userStatesLabel )
                    VALUES 
                    (:userStatesLabel)";                                           

                    $placeholders 	= array('userStatesLabel'=>'open');
                    try 
                    {
                        // Prepare: SQL-Statement vorbereiten
                        $PDOStatement = $PDO->prepare($sql);

                        // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                        $PDOStatement->execute($placeholders);

                    } catch(PDOException $error) {
if(DEBUG) 		     echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                    }
                        $rowCount = $PDOStatement->rowCount();

if(DEBUG_V)	            echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$rowCount: $rowCount <i>(" . basename(__FILE__) . ")</i></p>\n";

                        if($rowCount !==1)
                    {
if(DEBUG)	         echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Status open konnte nicht angelegt werden <i>(" . basename(__FILE__) . ")</i></p>\n";				

                    }else
                    {
if(DEBUG)	         echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Status open erfolgreich angelegt <i>(" . basename(__FILE__) . ")</i></p>\n";				

                        // Datenbank USERSTATES blocked erstellen
                        $sql	=	"INSERT INTO userStates 
                        (userStatesLabel )
                        VALUES 
                        (:userStatesLabel)";                                           

                        $placeholders 	= array('userStatesLabel'=>'blocked');
                    try {
                        // Prepare: SQL-Statement vorbereiten
                        $PDOStatement = $PDO->prepare($sql);

                        // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                        $PDOStatement->execute($placeholders);

                    } catch(PDOException $error) {
if(DEBUG) 		     echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                    }
                        $rowCount = $PDOStatement->rowCount();

if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$rowCount: $rowCount <i>(" . basename(__FILE__) . ")</i></p>\n";

                    if($rowCount !==1)
                    {
if(DEBUG)	        echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Status blocked konnte nicht angelegt werden <i>(" . basename(__FILE__) . ")</i></p>\n";				

                    }else
                    {
if(DEBUG)	        echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Status blocked erfolgreich angelegt <i>(" . basename(__FILE__) . ")</i></p>\n";				
                 
                    } // Datenbank USERSTATES blocked erstellen END

                }// Datenbank USERSTATES open erstellen END

            }// Datenbank USERSTATES unregistred erstellen

        }// Datenbankeinträge USERSTATES auslesen  und erstellenEND


                #********************************************************#
                #********** DATENEINTRAG ACCOUNTROLE ERSTELLEN **********#
                #********************************************************#


                 // Datenbankeinträge ACCOUNT auslesen
                 $sql	=	'SELECT COUNT(accRoleName) as anzahl
                 FROM accRole';                                           

                $placeholders 	= array();
                 try {
                    // Prepare: SQL-Statement vorbereiten
                 $PDOStatement = $PDO->prepare($sql);
                
                 // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                    $PDOStatement->execute($placeholders);
                
                } catch(PDOException $error) {
if(DEBUG) 		    echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                }
                    $rowcount = $PDOStatement->fetchALL(PDO::FETCH_ASSOC);
                    $rowcount = $rowcount[0]['anzahl'];

if(DEBUG_V)	    echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$rowcount <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_V)	    print_r($rowcount);					
if(DEBUG_V)	    echo "</pre>";

                    if ($rowcount !== 0)
                    {
if(DEBUG)	        echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER: Datensätze gefunden <i>(" . basename(__FILE__) . ")</i></p>\n";				                  
                    }

                    if ($rowcount === 0)
                    {
if(DEBUG)	        echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Keine Datensätze gefunden <i>(" . basename(__FILE__) . ")</i></p>\n";				

                        // Datenbank ACCOUNTROLE BENUTZER erstellen
                        $sql	=	"INSERT INTO accRole 
                        (accRoleName )
                        VALUES 
                        (:accRoleName)";                                           

                        $placeholders 	= array('accRoleName'=>'Benutzer');
                        try {
                        // Prepare: SQL-Statement vorbereiten
                        $PDOStatement = $PDO->prepare($sql);

                        // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                        $PDOStatement->execute($placeholders);

                        } catch(PDOException $error) {
if(DEBUG) 		    echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                        }
                        $rowCount = $PDOStatement->rowCount();

if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$rowCount: $rowCount <i>(" . basename(__FILE__) . ")</i></p>\n";

                        if($rowCount !==1)
                        {
if(DEBUG)	            echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: accRoleName BENUTZER konnte nicht angelegt werden <i>(" . basename(__FILE__) . ")</i></p>\n";				

                        }else
                        {
if(DEBUG)	            echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: accRoleName BENUTZER erfolgreich angelegt <i>(" . basename(__FILE__) . ")</i></p>\n";				

                            // Datenbank ACCOUNTROLE ADMIN erstellen
                            $sql	=	"INSERT INTO accRole 
                            (accRoleName )
                            VALUES 
                            (:accRoleName)";                                           

                            $placeholders 	= array('accRoleName'=>'Admin');
                            try {
                            // Prepare: SQL-Statement vorbereiten
                            $PDOStatement = $PDO->prepare($sql);

                            // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                            $PDOStatement->execute($placeholders);

                            } catch(PDOException $error) {
                            if(DEBUG) 		    echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                            }
                            $rowCount = $PDOStatement->rowCount();

if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$rowCount: $rowCount <i>(" . basename(__FILE__) . ")</i></p>\n";

                            if($rowCount !==1)
                            {
if(DEBUG)	            echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: accRoleName ADMIN konnte nicht angelegt werden <i>(" . basename(__FILE__) . ")</i></p>\n";				

                            }else
                            {
if(DEBUG)	            echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: accRoleName ADMIN erfolgreich angelegt <i>(" . basename(__FILE__) . ")</i></p>\n";				

                            }// Datenbank accRoleName ADMIN erstellen END

                        }// Datenbank ACCOUNTROLE BENUTZER erstellen

                    }//DATENEINTRAG ACCOUNTROLE ERSTELLEN END


                #*************************************************#
                #********** DATENEINTRAG USER ERSTELLEN **********#
                #*************************************************#
    
                // Datenbankeinträge USERS auslesen
                $sql	=	'SELECT COUNT(userEmail) as anzahl
                FROM users';                                           

                $placeholders 	= array();
                try {
                // Prepare: SQL-Statement vorbereiten
                $PDOStatement = $PDO->prepare($sql);

                // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                $PDOStatement->execute($placeholders);

                } catch(PDOException $error) {
if(DEBUG) 		    echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                }
                $rowcount = $PDOStatement->fetchALL(PDO::FETCH_ASSOC);
                $rowcount = $rowcount[0]['anzahl'];
if(DEBUG_V)	    echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$rowcount <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_V)	    print_r($rowcount);					
if(DEBUG_V)	    echo "</pre>";

                if ($rowcount !== 0)
                {
if(DEBUG)	        echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER: User Datensätze gefunden <i>(" . basename(__FILE__) . ")</i></p>\n";				                  
                }else
                {
if(DEBUG)	        echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Keine User Datensätze gefunden <i>(" . basename(__FILE__) . ")</i></p>\n";				

                    // $userName       = 'alfa';
                    $userEmail      = 'alfa@alfa.de';
                    $userPassword   = '$2y$10$IdL/rO52TeDm3w9kbBwJ2OxU70z1FQQGSWLGcHugJ1IYzcryrpJBu';//1234
                    $userStatesId   = 2;

                    // Datenbank USER Benutzer erstellen
                    $sql	=	"INSERT INTO users 
                    (userEmail, userPassword, userStatesId )
                    VALUES 
                    (:userEmail, :userPassword, :userStatesId)";                                           

                    $placeholders 	= array(
                                                'userEmail'     => $userEmail,
                                                'userPassword'  => $userPassword,
                                                'userStatesId'  => $userStatesId      
                                            );

                    try {
                        // Prepare: SQL-Statement vorbereiten
                        $PDOStatement = $PDO->prepare($sql);

                        // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                        $PDOStatement->execute($placeholders);

                    } catch(PDOException $error) {
if(DEBUG) 		            echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                    }
                        $rowCount = $PDOStatement->rowCount();  

if(DEBUG_V)	      echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: USER \$rowCount: $rowCount <i>(" . basename(__FILE__) . ")</i></p>\n";
                    if($rowCount !==1)
                    {
if(DEBUG)	        echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER: User konnte nicht angelgt werden <i>(" . basename(__FILE__) . ")</i></p>\n";				                  

                    }else{
if(DEBUG)	        echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: User Alfa erfolgreich angelegt <i>(" . basename(__FILE__) . ")</i></p>\n";				
    
                        // Datenbank ACCOUNT Benutzer erstellen
                        $userName   = 'alfa';
                        $accRoleId  = 2;
                        $userId     = 1;
                        $sql	=	"INSERT INTO account 
                        (accountName, accRoleId, userId )
                        VALUES 
                        (:accountName, :accRoleId, :userId)";                                           

                        $placeholders 	= array(                                                   
                                                    'accountName'   => $userName,
                                                    'accRoleId'     => $accRoleId,
                                                    'userId'        => $userId      
                                                );

                        try {
                            // Prepare: SQL-Statement vorbereiten
                            $PDOStatement = $PDO->prepare($sql);

                            // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                            $PDOStatement->execute($placeholders);

                        } catch(PDOException $error) {
if(DEBUG) 		            echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										

                        }// Datenbank ACCOUNT Benutzer erstellen END
                        $sql="INSERT INTO userdata
                                (userId)
                                VALUES
                                (:userId)
                            ";
                        $placeholders = array('userId'=>$userId);
                        try {
                            // Prepare: SQL-Statement vorbereiten
                            $PDOStatement = $PDO->prepare($sql);
                            
                            // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                            $PDOStatement->execute($placeholders);
                            
                        } catch(PDOException $error) {
if(DEBUG) 		            echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
                                                
                        }
                                            

                    }// Datenbank USER Benutzer erstellen END

                }//DATENEINTRAG USER ERSTELLEN END


                #**********************************************#
                #********** DATENBANK POST ERSTELLEN **********#


                // Datenbanktabellen Post erstellen
                $sql = "CREATE TABLE IF NOT EXISTS posts 
                (
                    postId INT AUTO_INCREMENT PRIMARY KEY,
                    postTitel VARCHAR(255) NOT NULL,
                    postText TEXT NOT NULL,
                    postImagePath VARCHAR(255) NOT NULL,
                    postDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    accountId int(11) NOT NULL,
                    FOREIGN KEY (accountId) REFERENCES account(accountId)
                )";                                         

                    $placeholders 	= array();
                try {
                    // Prepare: SQL-Statement vorbereiten
                    $PDOStatement = $PDO->prepare($sql);
                    
                    // Execute: SQL-Statement ausführen und ggf. Platzhalter füllen
                    $PDOStatement->execute($placeholders);
                    
                } catch(PDOException $error) {
if(DEBUG) 		    echo "<p class='debug db err'><b>Line " . __LINE__ . "</b>: FEHLER: " . $error->GetMessage() . "<i>(" . basename(__FILE__) . ")</i></p>\n";										
        
                }


                // DB-Verbindung schließen 
if(DEBUG_DB)        echo "<p class='debug db'><b>Line " . __LINE__ . "</b>: DB-Verbindung wird geschlossen. <i>(" . basename(__FILE__) . ")</i></p>\n";
                unset($PDO, $PDOStatement);

                header(header:"Location: ../index.php");