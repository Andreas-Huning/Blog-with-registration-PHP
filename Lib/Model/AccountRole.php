<?php
declare(strict_types=1);

namespace Lib\Model;
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');

                class AccountRole 
                {
                    private int     $accRoleId;
                    private string  $accRoleName;

                    public function getAccRoleName():string
                    {
                        return $this->accRoleName;
                    }

                    public function setAccRoleName(string $accRoleName)
                    {
                        $this->accRoleName = $accRoleName;

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
                }//class AccountRole  END