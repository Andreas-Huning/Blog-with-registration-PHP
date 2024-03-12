<?php
namespace Lib\Common;

        class Request
        {
            private array $requestData;
            public Session $session;

            public function __construct()
            {
                $this->requestData=$this->collectRequestData();
                $this->session = new Session();
            }

            public function isPost():bool
            {
                return $_SERVER['REQUEST_METHOD']==='POST';
            }

            public function isGet():bool
            {
                return $_SERVER['REQUEST_METHOD']==='GET';
            }

            public function get(string $key):?string
            {
                return $this->requestData[$key]??null;
            }
        

            private function cleanUpRequestData(array $requestData)
            {
                foreach($requestData as $key => $value){
                    $requestData[$key] = trim(htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, double_encode:false));
                }
                return $requestData;
            }

            private function collectRequestData():array
            {
                $requestData=array_merge($_GET,$_POST);
                return $this->cleanUpRequestData($requestData);
            }
            public function copyToSession():void
            {
                $this->session->set('form_data',$this->requestData);
            }

        }