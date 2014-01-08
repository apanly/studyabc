<?php
class APF_Util_CodecUtils {
    public static function hex_encode($string) {
        $hex = NULL;
        for ($ix = 0; $ix < strlen($string); $ix++ ) {
            $char =  substr($string, $ix, 1);
            $ord = ord($char);
            if ($ord < 16) {
                $hex .= '0'.dechex($ord);
            } else {
                $hex .= dechex($ord);
            }
        }
        return $hex;
    }

    public static function hex_decode($hex) {
        $string = NULL;
        for ($ix = 0; $ix < strlen($hex); $ix = $ix + 2) {
            $ord = hexdec(substr($hex, $ix, 2));
            $string .= chr($ord);
        }
        return $string;
    }

}