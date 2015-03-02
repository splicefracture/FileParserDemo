<?php

class FileParser
{

    var $symbol_table = [];

    var $truthy = ["true","yes","on"];
    var $falsey = ["false","no","off"];

    public function FileParser($filename){

        $fp = fopen($filename, "r");

        if (!$fp) throw new Exception("File Input Error");

        while($str = fgets($fp))
        {    
            // remove comments
            $str = preg_replace("/(.*?)\#(.*)/", "$1", $str);

            // remove whitespace
            $str = preg_replace('/(?| *(".*?") *| *(\'.*?\') *)| +/s', '$1', $str);

            // split over equals
            $parsed = preg_split ( '/[=]/', $str );

            if (count($parsed)>=2)
            {
                $key   = trim($parsed[0]);
                $value = trim($parsed[1]);

                // cast numeric values
                if (is_numeric($value))
                {
                    $value = strpos($value,".")===FALSE ? intval($value) : floatval($value);
                }
                // cast bool : true
                if (!(array_search($value,$this->truthy)===FALSE))
                {
                    $value = TRUE;
                }
                // cast bool : false
                else if (!(array_search($value,$this->falsey)===FALSE))
                {
                    $value = FALSE;
                }

                $this->symbol_table[$key] = $value;
            }
        }

        fclose($fp);
    }

    public function getAll(){
        return $this->symbol_table;
    }

    public function get($key){
        if (array_key_exists($key,$this->symbol_table)){
            return $this->symbol_table[$key];
        }
        throw new Exception("Parsed Key not found");
    }

}


