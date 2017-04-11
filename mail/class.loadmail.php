<?php

class LoadMail{

	function getHeadColumn($name,$string){
		$tmp=strtolower(substr($string,0,strlen($name)+1));
		if( $tmp == strtolower($name).':'){
			return $this->_decodeHeader(substr($string,strlen($tmp),strlen($string)));
		}else{
			return false;
		}
	}

	function getFROMEmail($string){
		if(preg_match("/<([^>]|\n)*>/", $string, $res)){
			while(preg_match("/<([^>]|\n)*>/", $string, $res)){
				$email = $res[0];
				$string = str_replace($email,'',$string);
			}
			return trim(substr($email,1,strlen($email)-2));
		}else{
			return $string;
		}
	}

	function _decodeHeader($input)
    {
        // Remove white space between encoded-words
        $input = preg_replace('/(=\?[^?]+\?(q|b)\?[^?]*\?=)(\s)+=\?/i', '\1=?', $input);

        // For each encoded-word...
        while (preg_match('/(=\?([^?]+)\?(q|b)\?([^?]*)\?=)/i', $input, $matches)) {

            $encoded  = $matches[1];
            $charset  = $matches[2];
            $encoding = $matches[3];
            $text     = $matches[4];

            switch (strtolower($encoding)) {
                case 'b':
                    $text = base64_decode($text);
                    break;

                case 'q':
                    $text = str_replace('_', ' ', $text);
                    preg_match_all('/=([a-f0-9]{2})/i', $text, $matches);
                    foreach($matches[1] as $value)
                        $text = str_replace('='.$value, chr(hexdec($value)), $text);
                    break;
            }

            $input = str_replace($encoded, $text, $input);
        }

        return trim($input);
    }


} // end class LoadMail

?>