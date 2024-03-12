<?php
declare(strict_types=1);

namespace Lib\Model;
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');

                class User 
                {
                    private ?int        $userId;
                    private string      $userPassword;
                    private string      $userEmail;
                    private ?int        $userStatesId;
                    private ?string     $userRegTimeStamp;
                    private ?string     $userAvatarPath;
                    private ?string     $userRegHash;
                    private UserState   $userState;
                    private Account     $account;



                    #*************************************#
					#********** GETTER & SETTER **********#
					#*************************************#
				
					#********** USERID **********#
                    public function getUserId():?int
                    {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        return $this->userId;
                    }
                    public function setUserId(?int $userId):void
                    {
// if(DEBUG_C)			    echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        $this->userId = $userId;
                    }


                    
                    #********** USERPASSWORD **********#
                    public function getUserPassword():string
                    {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        return $this->userPassword;
                    }
                    public function setUserPassword(string $userPassword):void
                    {
// if(DEBUG_C)			    echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        $this->userPassword = $userPassword;
                    }

                    #********** USEREMAIL **********#
                    public function getUserEmail():string
                    {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        return $this->userEmail;
                    }
                    public function setUserEmail(string $userEmail):void
                    {
// if(DEBUG_C)			    echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        $this->userEmail = $userEmail;
                    }
                    
                    #********** USERSATUSID **********#
                    public function getUserStatesId():?int
                    {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        return $this->userStatesId;
                    }
                    public function setUserStatesId(?int $userStatesId):void
                    {
// if(DEBUG_C)			    echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        $this->userStatesId = $userStatesId;
                    }

                    #********** USER REG TIME STAMP **********#
                    public function getUserRegTimeStamp():?string
                    {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        return $this->userRegTimeStamp;
                    }
                    public function setUserRegTimeStamp(?string $userRegTimeStamp):void
                    {
// if(DEBUG_C)			    echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        $this->userRegTimeStamp = $userRegTimeStamp;
                    }

                    #********** USER AVATAR PATH **********#
                    public function getUserAvatarPath():?string
                    {
// if(DEBUG_C)			    echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        return $this->userAvatarPath;
                    }
                    public function setUserAvatarPath(?string $userAvatarPath):void
                    {
// if(DEBUG_C)			    echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        $this->userAvatarPath = $userAvatarPath;
                    }


                    

                    #********** USER userRegHash **********#
                    public function getUserRegHash():?string
                    {
// if(DEBUG_C)			    echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        return $this->userRegHash;
                    }
                    public function setUserRegHash(?string $userRegHash):void
                    {
// if(DEBUG_C)			    echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        $this->userRegHash = $userRegHash;
                    }



                    #********** USERSTATE **********#
                    public function getUserState():UserState
                    {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        return $this->userState;
                    }
                    public function setUserState(UserState $userState):void
                    {
// if(DEBUG_C)			    echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        $this->userState = $userState;
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

                }//class User END
                