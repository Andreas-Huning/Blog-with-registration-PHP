<?php
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
session_name('Kochbuch');
session_start();

                if( isset($_SESSION['ID']) === false OR $_SESSION['IPAddress'] !== $_SERVER['REMOTE_ADDR'] ){    
if(DEBUG) 		    echo "<p class='debug auth err'><b>Line " . __LINE__ . "</b>: Login konnte nicht validiert werden! <i>(" . basename(__FILE__) . ")</i></p>\n";			
                    unset($_SESSION['ID']);    
                    unset($_SESSION['IPAddress']);                         
                    $loggedIn 	= false;
                } else {    
if(DEBUG) 		    echo "<p class='debug auth ok'><b>Line " . __LINE__ . "</b>: Login erfolgreich validiert. <i>(" . basename(__FILE__) . ")</i></p>\n";					
                    session_regenerate_id(true);    
                    $loggedIn 	= true;    
                }

$loader = new FilesystemLoader($_SERVER['DOCUMENT_ROOT'] . '/templates');
$twig = new Environment($loader, [
    // 'cache' => '/path/to/compilation_cache',
    'cache' => false, // Cache deaktivieren für die Entwicklung
]);
$twig->addGlobal('base_url', BASE_URL);
$twig->addGlobal('loggedIn', $loggedIn);