<?php

declare(strict_types=1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');

use Lib\Common\Request;
use Lib\Model\Post;
use Lib\Model\Account;
use Lib\repository\PostRepository;
use Lib\repository\AccountRepository;
use Lib\repository\AccountRoleRepository;
$request = new Request();

$sessionData['form_data'] = $request->session->getAll();
$PDO = dbConnect();
$accountRoleRepository = new AccountRoleRepository($PDO);
$accountRepository = new AccountRepository($PDO,$accountRoleRepository);

$postRepository = new PostRepository($PDO,$accountRepository);
$posts = $postRepository->findAllPost();


if(DEBUG_V)	echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$posts <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
if(DEBUG_V)	print_r($posts);					
if(DEBUG_V)	echo "</pre>";


// if(DEBUG_V)	echo "<pre class='debug value'><b>Line " . __LINE__ . "</b>: \$sessionData <i>(" . basename(__FILE__) . ")</i>:<br>\n";					
// if(DEBUG_V)	print_r($sessionData);					
// if(DEBUG_V)	echo "</pre>";

echo $twig->render('index.html.twig',[ 'sessionData'=>$sessionData,'posts'=>$posts ]);
unset($_SESSION['form_data']);
unset($_SESSION['error']); 
unset($_SESSION['url']); 