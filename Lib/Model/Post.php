<?php
declare(strict_types=1);

namespace Lib\Model;
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');

                class Post 
                {
                    private ?int        $postId;
                    private string      $postTitel;
                    private string      $postText;
                    private string      $postImagePath;
                    private string      $postDate;
                    private int         $accountId;
                    private Account     $account;

                    public function getAccountId():int
                    {
                        return $this->accountId;
                    }

                    public function setAccountId($accountId):void
                    {
                        $this->accountId = $accountId;
                    }

                    public function getPostImagePath():string
                    {
                        return $this->postImagePath;
                    }

                    public function setPostImagePath(string $postImagePath):void
                    {
                        $this->postImagePath = $postImagePath;
                    }

                    public function getPostText():string
                    {
                        return $this->postText;
                    }

                    public function setPostText(string $postText):void
                    {
                        $this->postText = $postText;
                    }

                    public function getPostTitel():string
                    {
                        return $this->postTitel;
                    }

                    public function setPostTitel(string $postTitel):void
                    {
                        $this->postTitel = $postTitel;
                    }

                    public function getPostId():int
                    {
                        return $this->postId;
                    }

                    public function setPostId(?int $postId):void
                    {
                        $this->postId = $postId;
                    }
                    #********** ACCOUNT **********#
                    public function getAccount():Account
                    {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        return $this->account;
                    }
                    public function setAccount(Account $account):void
                    {
// if(DEBUG_C)			    echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        $this->account = $account;
                    }

                    public function getPostDate():string
                    {
                        return $this->postDate;
                    }

                    public function setPostDate(?string $postDate):void
                    {
                        $this->postDate = $postDate;
                    }
                }//class Post END