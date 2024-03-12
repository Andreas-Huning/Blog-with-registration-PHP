<?php

declare(strict_types=1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');

use Lib\Common\Request;
$request = new Request();

$sessionData['form_data'] = $request->session->getAll();

// if(DEBUG_V)	echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$arrayName <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	print_r($sessionData);					
// if(DEBUG_V)	echo "</pre>";

echo $twig->render('user\registration.html.twig',[ 'sessionData'=>$sessionData ]);
unset($_SESSION['form_data']);
unset($_SESSION['error']);