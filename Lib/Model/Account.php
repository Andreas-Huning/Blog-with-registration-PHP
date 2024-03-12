<?php
declare(strict_types=1);

namespace Lib\Model;
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');

                class Account 
                {
                    private ?int            $accountId ;
                    private string          $accountName;
                    private int             $accRoleId;
                    private int             $userId;
                    private AccountRole     $accountRole;

                    public function getUserId():int
                    {
                        return $this->userId;
                    }

                    public function setUserId(?int $userId)
                    {
                        $this->userId = $userId;

                        return $this;
                    }


                    public function getAccRoleId():int
                    {
                        return $this->accRoleId;
                    }


                    public function setAccRoleId(int $accRoleId)
                    {
                        $this->accRoleId = $accRoleId;

                        return $this;
                    }

                    public function getAccountName():string
                    {
                        return $this->accountName;
                    }

                    public function setAccountName(string $accountName)
                    {
                        $this->accountName = $accountName;

                        return $this;
                    }

                    public function getAccountId():?int
                    {
                        return $this->accountId;
                    }

                    public function setAccountId(?int $accountId)
                    {
                        $this->accountId = $accountId;

                        return $this;
                    }

                    public function getAccountRole():AccountRole
                    {
                        return $this->accountRole;
                    }

                    public function setAccountRole(AccountRole $accountRole)
                    {
                        $this->accountRole = $accountRole;

                        return $this;
                    }
                }//class Account END