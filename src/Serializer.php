#!/usr/bin/env php

<?php   
    require_once __DIR__ . '/../src/InterfaceJSON.php';
    require_once __DIR__ . '/../src/InterfaceXML.php';

    class Serializer implements JSONserializr, XMLserializr {
        private $var1;
        private $var2;
        private $var3;
        public $yaml;
        public $i;

        public function __construct ($obj) {
            $this->var1 = $obj->name;
            $this->var2 = $obj->surname;
            $this->var3 = $obj->age;
            $this->yaml = '';
            $this->i = 0;
        }

        public function jsonSerialize($obj) {
            $arr = [];
            foreach ($obj as $key => $value) {
                $arr[$key] = $value;
            };
            return $arr;
        }
        

        public function recursiveSetTab($obj) {
            foreach ($obj as $key => $value) {
                if (is_array($value)){
                    $this->i += 1;
                    $this->yaml .= "\r\n" . str_repeat("\t", $this->i) . "$key :";
                    $this->recursiveSetTab($value);
                } else {
                    $this->yaml .= "\r\n" . str_repeat("\t", $this->i) . "$key : $value";
                }
            };

            $this->i -= 1;
            return $this->yaml;
        }

        public function yamlSerialize($obj) {
            $index = 0;
            $this->recursiveSetTab($obj);
            return $this->yaml;
        }

        public function xmlSerialize($obj) {
            $xml = '';

            foreach ($obj as $key => $value) {
               
                $xml .= $index === 0 ? "--- \r\n$key : $value" : "\r\n$key : $value";
                $index++;
                return $xml;
            };
        }

        public function serialize($obj, $format) {
            switch ($format) {
                case 'json':
                    return $this->jsonSerialize($obj);
                case 'xml': 
                    return $this->xmlSerialize($obj);
                case 'yaml': 
                    return $this->yamlSerialize($obj);
                default: 'unknown';
            }
        }
    }

   