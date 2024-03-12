<?php

declare(strict_types=1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');

use Lib\Common\Request;
$request = new Request();

$sessionData['form_data'] = $request->session->getAll();

echo $twig->render('user\login.html.twig',[ 'sessionData'=>$sessionData ]);
unset($_SESSION['form_data']);
unset($_SESSION['error']); 