<?php
declare(strict_types=1);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');
use Lib\Model\User;
use Lib\Model\UserState;
use Lib\Model\AccountRole;
use Lib\Model\Account;
use Lib\repository\UserRepository;
use Lib\repository\UserStateRepository;
use Lib\Common\Request;
use Lib\repository\AccountRepository;
use Lib\repository\AccountRoleRepository;

$request = new Request();
$errorMessages;

                if( DEBUG OR DEBUG_V OR DEBUG_F OR DEBUG_DB OR DEBUG_C OR DEBUG_CC ) {
// if(DEBUG) 		    echo "<p class='debug hint'><b>Line " . __LINE__ . "</b>: Output-Buffering ist Aktiv. <i>(" . basename(__FILE__) . ")</i></p>\n";  
                    ob_start();
                }
 
// if(DEBUG_V)	            echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$request <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	            print_r($request);					
// if(DEBUG_V)	            echo "</pre>";
                if($request->isPost())
                {
                    $email          = sanitizeString($request->get('f1')) ;
                    $password       = sanitizeString($request->get('f2')) ;

// if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$email: $email <i>(" . basename(__FILE__) . ")</i></p>\n";
// if(DEBUG_V)	        echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$password: $password <i>(" . basename(__FILE__) . ")</i></p>\n";
                    
                    $isFormValid = true;
                    
                    if(!$email || filter_var($email,FILTER_VALIDATE_EMAIL) === false)
                    {
                        $errorMessages['email']='Dies ist keine gültige Email-Adresse!';
                        $isFormValid = false; 
                    }
                    if(!$password || strlen($password) <= 3)
                    {
                        $errorMessages['password']='Muss mindestens 3 Zeichen lang sein!';
                        $isFormValid = false; 
                    }
                    if( $isFormValid === false )
                    {
// if(DEBUG)	               echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER: Das Formular enthält noch Fehler <i>(" . basename(__FILE__) . ")</i></p>\n";				
                      
                    }else
                    {
                        // DB-Verbindung Öffnen
                        $pdo = dbConnect();
                        $userStateRepository = new UserStateRepository($pdo);
                        $userRepository = new UserRepository($pdo,$userStateRepository);
                        $user = $userRepository->findByEmail($email);


// if(DEBUG_V)	            echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$user <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	            print_r($user);					
// if(DEBUG_V)	            echo "</pre>";                       
                   
                        if( $user === NULL )
                        {
// if(DEBUG)	                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER: User nicht gefunden! <i>(" . basename(__FILE__) . ")</i></p>\n";				
                            $errorMessages['login']='Loginname oder Passwort falsch!';
                        }else
                        {
// if(DEBUG)	                echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: User gefunden. <i>(" . basename(__FILE__) . ")</i></p>\n";				
  
                            if( password_verify( $password, $user->getUserPassword() ) === false ) 
                            {
// if(DEBUG)					echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Das Passwort aus dem Formular stimmt nicht mit dem Passwort aus der DB überein! <i>(" . basename(__FILE__) . ")</i></p>\n";				
                            $errorMessages['login']='Loginname oder Passwort falsch!';
                            }else
                            {
// if(DEBUG)					    echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Das Passwort aus dem Formular stimmt mit dem Passwort aus der DB überein. <i>(" . basename(__FILE__) . ")</i></p>\n";				

                                $userStatus = $user->getUserState()->getUserStatesId();
                                
                                if( $userStatus !== 2 ) {
                                    // Fehlerfall
if(DEBUG)						    echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Login verweigert <i>(" . basename(__FILE__) . ")</i></p>\n";				
                                    $errorMessages['login']='Ihr Login ist noch nicht freigeschaltet';
                                } else {
                                    // Erfolgsfall
if(DEBUG)							echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Login gewährt <i>(" . basename(__FILE__) . ")</i></p>\n";				
                                    
                                    $userId=$user->getUserId();

                                    $accountRoleRepository = new AccountRoleRepository($pdo);
                                    $accountRepository = new AccountRepository($pdo,$accountRoleRepository);
                                    $account = $accountRepository->findAccountByID($userId); 

                                    $accountName = $account->getAccountName();
                                    $accountId = $account->getAccountId();

// if(DEBUG_V)	            echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$accountName <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	            print_r($accountName);					
// if(DEBUG_V)	            echo "</pre>";



                                    #********** SAVE USER DATA INTO SESSION FILE **********#
                                    $_SESSION['ID'] 		    = $userId;
                                    $_SESSION['AccountName']    = $accountName;
                                    $_SESSION['AccountId']      = $accountId;
                                    $_SESSION['IPAddress'] 	    = $_SERVER['REMOTE_ADDR'];                                 
                                    header(header:"Location: ../../user/dashboard.php");
                                    exit;
                                }
                            }
                        }
                    }
                    $request->copyToSession();
                    $_SESSION['error'] = $errorMessages;

                    header(header:"Location: ../.././login.php");
                }

                if($request->isGet())
                {
 
// if(DEBUG_V)	            echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$request <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	            print_r($request);					
// if(DEBUG_V)	            echo "</pre>";

                    if( $request->get('action') !== NULL  ) 
                    {
// if(DEBUG)		        echo "<p class='debug'>🧻 <b>Line " . __LINE__ . "</b>: URL-Parameter 'action' wurde übergeben. <i>(" . basename(__FILE__) . ")</i></p>\n";										
                        
                        $action          = trim(htmlspecialchars($request->get('action'), ENT_QUOTES | ENT_HTML5, double_encode:false)) ;

// if(DEBUG_V)	            echo "<p class='debug value'><b>Line " . __LINE__ . "</b>: \$action: $action <i>(" . basename(__FILE__) . ")</i></p>\n";
                    
                        if($request->get('action') === 'logout')
                        {
// if(DEBUG)			        echo "<p class='debug'>📑 <b>Line " . __LINE__ . "</b>: Logout wird durchgeführt... <i>(" . basename(__FILE__) . ")</i></p>\n";
                            unset($_SESSION['ID']);    
                            unset($_SESSION['IPAddress']);  
                            
                            header(header:"Location: ../.././index.php"); 
                        }
                    }   

                } 