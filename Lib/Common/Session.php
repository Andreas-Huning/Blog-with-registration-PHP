<?php
namespace Lib\Common;

        class Session
        {
            public function __construct()
            {
                if(session_status() === PHP_SESSION_NONE){
                    session_start();
                }
            }
            public function set(string $key, $value)
            {
                $_SESSION[$key]=$value;
            }

            public function get(string $key):mixed
            {
               return $_SESSION[$key]??null; 
            }

            public function getFormData(string $key)
            {
                return $_SESSION['form_data'][$key]??null;
            }

            public function getAll():array
            {
                return $_SESSION;
            }

            public function delete(string $key):void
            {
                unset($_SESSION[$key]);
            }

            public function destroy():void
            {
                session_destroy();
            }
        }