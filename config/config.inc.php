<?php
#**************************************************************************************************************************#

				#******************************************#
				#********** GLOBAL CONFIGURATION **********#
				#******************************************#

				#********** DATABASE CONFIGURATION **********#
				define('DB_SYSTEM', 	'mysql');
				define('DB_HOST', 		'localhost');
				define('DB_NAME', 		'blog');
				define('DB_USER', 		'root');
				define('DB_PWD', 		'');
								
				
				#********** EXTERNAL STRING VALIDATION CONFIGURATION **********#
				define('INPUT_MIN_LENGTH',		0);
				define('INPUT_MAX_LENGTH',		255);
				define('INPUT_MANDATORY',		true);
				
				#********** IMAGE UPLOAD CONFIGURATION **********#				
				define('IMAGE_MAX_HEIGHT', 				800 );
				define('IMAGE_MAX_WIDTH', 				800 );
				define('IMAGE_MIN_SIZE', 				1024);
				define('IMAGE_MAX_SIZE',				128*1024 );
				define('IMAGE_ALLOWED_MIME_TYPES', 		array('image/jpg' => '.jpg', 'image/jpeg' => '.jpeg', 'image/png' => '.png', 'image/gif' => '.gif') );
				define('IMAGE_UPLOAD_PATH', 			'../.././public/images/userimages/' );
				define('AVATAR_DUMMY_PATH', 			'../.././public/images/avatar_dummy.png' );
				
				  
				
				#********** DEBUGGING **********#
				define('DEBUG', 	false);	// Debugging for main document
				define('DEBUG_V', 	false);	// Debugging for values
				define('DEBUG_F', 	false);	// Debugging for form funktions
				define('DEBUG_DB', 	false);	// Debugging for DB funktions
				define('DEBUG_C', 	false);	// Debugging for classes
				define('DEBUG_CC', 	false);	// Debugging for class constructors
				
				#********** Globale Pfadangabe **********#
				define('BASE_URL', 	$_SERVER['DOCUMENT_ROOT'].'');	// Base URL
				
				#********** Einbindungen globaler Dateien **********#				
				require_once( BASE_URL .'/vendor/autoload.php');
				require_once( BASE_URL .'/config/twig.php');


#**************************************************************************************************************************#


				#*************************************#
				#********** SANITIZE STRING **********#
				#*************************************#

				function sanitizeString( $value )
				{
if(DEBUG_F)			echo "<p class='debug sanitizeString'>🌀 <b>Line " . __LINE__ . "</b>: Aufruf " . __FUNCTION__ . "('$value') <i>(" . basename(__FILE__) . ")</i></p>\n";
					if($value !== Null)
					{
						$value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, double_encode:false);
						$value = trim($value);	
						if( $value ==='' ){
							$value = NULL;
						}
					}
					return $value;										
				}


#**************************************************************************************************************************#


				#*************************************#
				#********** VALIDATE EMAIL **********#
				#*************************************#


				function validateInputString( $value, $mandatory=INPUT_MANDATORY, $minLength=INPUT_MIN_LENGTH, $maxLength=INPUT_MAX_LENGTH )
				{
	
if(DEBUG_F)			echo "<p class='debug validateInputString'>🌀 <b>Line " . __LINE__ . "</b>: Aufruf " . __FUNCTION__ . "('$value [$minLength|$maxLength],mandatory:$mandatory') <i>(" . basename(__FILE__) . ")</i></p>\n";

					if( $mandatory === true AND $value === NULL ){
						return 'Dies ist ein Pflichtfeld!';
					}elseif( $value !== NULL AND mb_strlen($value) > $maxLength ){
						return "Darf maximal $maxLength Zeichen lang sein!";
					}elseif( $value !== NULL AND mb_strlen($value) < $minLength ){
						return "Muss mindestens $minLength Zeichen lang sein!";									
					}			
				}


#**************************************************************************************************************************#


				#*******************************************#
				#********** VALIDATE EMAIL FORMAT **********#
				#*******************************************#

				function validateEmail($value, $mandatory=true) 
				{
				
if(DEBUG_F) 		echo "<p class='debug validateEmail'>🌀 <b>Line " . __LINE__ . "</b>: Aufruf " . __FUNCTION__ . "('value:$value, mandatory:$mandatory') <i>(" . basename(__FILE__) . ")</i></p>\n";
					
						if( $mandatory === true AND $value === NULL )
						{
							return 'Dies ist ein Pflichtfeld!';					
						}elseif( filter_var($value,FILTER_VALIDATE_EMAIL)=== false )
						{
							return 'Dies ist keine gültige Email-Adresse!';
						}else{
							return NULL;
						}
					}
	
#**************************************************************************************************************************#
#**************************************************************************************************************************#


				#*******************************************#
				#********** VALIDATE IMAGE UPLOAD **********#
				#*******************************************#

				function validateImageUpload( $fileTemp,
														$imageMaxHeight 			= IMAGE_MAX_HEIGHT,
														$imageMaxWidth 				= IMAGE_MAX_WIDTH,
														$imageMinSize 				= IMAGE_MIN_SIZE,
														$imageMaxSize 				= IMAGE_MAX_SIZE,
														$imageAllowedMimeTypes 		= IMAGE_ALLOWED_MIME_TYPES,
														$imageUploadPath		 	= IMAGE_UPLOAD_PATH ) 
				{
if(DEBUG_F) 		echo "<p class='debug validateImageUpload'>🌀 <b>Line " . __LINE__ . "</b>: Aufruf " . __FUNCTION__ . "('$fileTemp') <i>(" . basename(__FILE__) . ")</i></p>\n";
					
					$imageDataArray = getimagesize($fileTemp);
				
if(DEBUG_F)			echo "<pre class='debug validateImageUpload value'><b>Line " . __LINE__ . "</b>: \$imageDataArray <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_F)			print_r($imageDataArray);					
if(DEBUG_F)			echo "</pre>";				
					
					if( $imageDataArray === false )
					{
						return array('imagePath' => NULL ,'imageError'=> 'Dies ist keine Bilddatei');
					
					}else
					{					
						$imageWidth 		= sanitizeString( $imageDataArray[0] );
						$imageHeight 		= sanitizeString( $imageDataArray[1] );
						$imageMimeType		= sanitizeString( $imageDataArray['mime'] );
						$fileSize 			= fileSize($fileTemp);
						
if(DEBUG_F)			echo "<p class='debug validateImageUpload value'><b>Line " . __LINE__ . "</b>: \$imageWidth: 	$imageWidth px<i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_F)			echo "<p class='debug validateImageUpload value'><b>Line " . __LINE__ . "</b>: \$imageHeight: 	$imageHeight px<i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_F)			echo "<p class='debug validateImageUpload value'><b>Line " . __LINE__ . "</b>: \$imageMimeType: $imageMimeType <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_F)			echo "<p class='debug validateImageUpload value'><b>Line " . __LINE__ . "</b>: \$fileSize: 		". round($fileSize/1024, 1) ." kB<i>(" . basename(__FILE__) . ")</i></p>\n";
						
						
					}
					if( !$imageWidth OR !$imageHeight OR !$imageMimeType OR $fileSize < $imageMinSize )
					{						
						return array('imagePath' => NULL ,'imageError'=> 'Verdächtiger Dateiheader');
					}	

					if( array_key_exists($imageMimeType,$imageAllowedMimeTypes) === false)
					{						
						return array('imagePath' => NULL ,'imageError'=> 'Dies ist kein erlaubter Bildtyp');
					}

					if( $imageWidth > $imageMaxWidth )
					{						
						return array('imagePath' => NULL ,'imageError'=> "Die Bildbreite darf maximal ".$imageMaxWidth." px betragen");
					} 

					if( $imageHeight > $imageMaxHeight )
					{
						return array('imagePath' => NULL ,'imageError'=> "Die Bildhöhe darf maximal ".$imageMaxHeight." px betragen");
					}			
			
					if( $fileSize > $imageMaxSize )
					{
						$imageMaxSize = $imageMaxSize/1024;
						return array('imagePath' => NULL ,'imageError'=> "Die Dateigröße darf maximal ".$imageMaxSize." kB betragen");
					}
					$fileName = mt_rand() . str_shuffle('abcdefghijklmnopqrstuvwxyz__--0123456789'). str_replace( array('.', ' '), '',microtime() );

					$fileExtension = $imageAllowedMimeTypes[$imageMimeType];
				
					$fileTarget = $fileName. $fileExtension;

					$userId = $_SESSION['ID'];
if(DEBUG_V)			echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$userId: $userId <i>(" . basename(__FILE__) . ")</i></p>\n";


					$imageUploadPath = $imageUploadPath."".$_SESSION['ID']."/";

if(DEBUG_V)			echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$imageUploadPath: $imageUploadPath <i>(" . basename(__FILE__) . ")</i></p>\n";

					if( file_exists($imageUploadPath) === false OR is_dir($imageUploadPath) === false ) {									
						if( mkdir($imageUploadPath) === false) {
							// Fehlerfall
							return false;
						}
					}
					$fileTarget = $imageUploadPath. $fileName . $fileExtension;

if(DEBUG_V)		echo "<p class='debug validateImageUpload value'><b>Line " . __LINE__ . "</b>: \$fileTarget: Länge: ".strlen($fileTarget)." <i>(" . basename(__FILE__) . ")</i></p>\n";
if(DEBUG_V)		echo "<p class='debug validateImageUpload value'><b>Line " . __LINE__ . "</b>: \$fileTarget: '$fileTarget' <i>(" . basename(__FILE__) . ")</i></p>\n";

					if( move_uploaded_file( $fileTemp, $fileTarget ) === false ){
						
if(DEBUG_F) 		echo "<p class='debug validateImageUpload err'><b>Line " . __LINE__ . "</b>: FEHLER beim Verschieben des Bildes nach <i>'$fileTarget'</i>! <i>(" . basename(__FILE__) . ")</i></p>\n";						
						
						return array('imagePath' => NULL ,'imageError'=> 'Es ist ein Fehler aufgetreten! Bitte versuchen Sie es später noch einmal.');
					}else{
						
if(DEBUG_F) 		echo "<p class='debug validateImageUpload ok'><b>Line " . __LINE__ . "</b>: Bild erfolgreich nach <i>'$fileTarget'</i> verschoben <i>(" . basename(__FILE__) . ")</i></p>\n";					
						return array('imagePath' => $fileTarget ,'imageError'=> NULL);						
					}
				}
			
				
#**************************************************************************************************************************#


				function isoToEuDateTime($value) {						
						if($value) {
							if( strpos($value, " ") ) {
								$dateTimeArray = explode(" ", $value);
								$date = $dateTimeArray[0];
								$dateArray 	= explode("-", $date);
								$time = $dateTimeArray[1];
								$time 		= substr($time, 0, 5);

							} else {
								$dateArray 	= explode("-", $value);
								$time 		= NULL;
							}				
							$euDate = "$dateArray[2].$dateArray[1].$dateArray[0]";																		
						} else {
							$euDate 		= NULL;
							$time 		= NULL;						
						}
						return array("date"=>$euDate, "time"=>$time);					
					}


#**************************************************************************************************************************#
						function dbConnect($DBName=DB_NAME) {
				
if(DEBUG_DB)	echo "<p class='debug db'><b>Line " . __LINE__ .  "</b> | " . __METHOD__ . "(): Versuche mit der DB '<b>$DBName</b>' zu verbinden... <i>(" . basename(__FILE__) . ")</i></p>\r\n";					

						try {
							$PDO = new PDO(DB_SYSTEM . ":host=" . DB_HOST . "; dbname=$DBName; charset=utf8mb4", DB_USER, DB_PWD);
							$PDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
							$PDO->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);				
						} catch(PDOException $error) {
if(DEBUG_DB)		echo "<p class='debug db err'><b>Line " . __LINE__ .  "</b> | " . __METHOD__ . "(): <i>FEHLER: " . $error->GetMessage() . " </i> <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							exit;
						}

if(DEBUG_DB)	echo "<p class='debug db ok'><b>Line " . __LINE__ .  "</b> | " . __METHOD__ . "(): Erfolgreich mit der DB '<b>$DBName</b>' verbunden. <i>(" . basename(__FILE__) . ")</i></p>\r\n";

						return $PDO;
					}
#**************************************************************************************************************************#
	