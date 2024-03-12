<?php
declare(strict_types=1);

namespace Lib\Model;
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.inc.php');

                class UserState 
                {
                    private int     $userStatesId;
                    private string  $userStatesLabel ;

				
					#********** UserStatesId **********#
                    public function getUserStatesId():?int
                    {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        return $this->userStatesId;
                    }
                    public function setUserStatesId(int $userStatesId):void
                    {
// if(DEBUG_C)			    echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        $this->userStatesId = $userStatesId;
                    }
                    #********** UserStatesLabel **********#
                    public function getUserStatesLabel():string
                    {
// if(DEBUG_C)			echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        return $this->userStatesLabel;
                    }
                    public function setUserStatesLabel(string $userStatesLabel):void
                    {
// if(DEBUG_C)			    echo "<p class='debug class'>🌀 <b>Line " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</p>\n";
                        $this->userStatesLabel = $userStatesLabel;
                    }

                }//class UserStatesLabel END