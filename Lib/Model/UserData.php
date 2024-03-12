<?php
declare(strict_types=1);

namespace Lib\Model;
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');

                class UserData 
                {
                    private ?int     $userDataId;
                    private ?string   $userDataFirstName;
                    private ?string   $userDataLastName;
                    private ?string   $userDataBirthday;
                    private ?int      $userId;

                    public function getUserId():int
                    {
                        return $this->userId;
                    }

                    public function setUserId(int $userId):void
                    {
                        $this->userId = $userId;
                        
                    }

                    public function getUserDataBirthday():?string
                    {
                        return $this->userDataBirthday;
                    }

                    public function setUserDataBirthday(?string $userDataBirthday):void
                    {
                        $this->userDataBirthday = $userDataBirthday;
                        
                    }

                    public function getUserDataLastName():?string
                    {
                        return $this->userDataLastName;
                    }

                    public function setUserDataLastName(?string $userDataLastName):void
                    {
                        $this->userDataLastName = $userDataLastName;
                        
                    }

                    public function getUserDataFirstName():?string
                    {
                        return $this->userDataFirstName;
                    }

                    public function setUserDataFirstName(?string $userDataFirstName):void
                    {
                        $this->userDataFirstName = $userDataFirstName;
                        
                    }

                    public function getUserDataId():?int
                    {
                        return $this->userDataId;
                    }

                    public function setUserDataId($userDataId):void
                    {
                        $this->userDataId = $userDataId;
                        
                    }
                }//class UserData END