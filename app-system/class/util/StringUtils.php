<?php
class Util_StringUtils {
    public static function build_url ($url,$params) {
        if (preg_match("/W0QQ/i",$url)) {
            $uri = self::encode_seo_parameters($params);
            return $url . "QQ" . str_replace("W0QQ","",$uri);
        } else {
            return $url . self::encode_seo_parameters($params);
        }
    }
    public static function decode_seo_parameters($string) {
        $parameters = array();

        if (!$string) {
            return $parameters;
        }

        $pos = strpos($string, '?');
        if ($pos) {
            $string = substr($string, 0, $pos);
        }
        if (!$string) {
            return $parameters;
        }

        $pos = strpos($string, 'W0QQ');
        if ($pos) {
            $string = substr($string, $pos + 4);
        } else {
            return $parameters;
        }
        if (!$string) {
            return $parameters;
        }

        $list = preg_split('/QQ/', $string, -1, PREG_SPLIT_NO_EMPTY);
        foreach($list as $item) {
            @list($name, $value) = preg_split('/Z/', $item, 2, PREG_SPLIT_NO_EMPTY);
            if (!$name) {
                continue;
            }
            $name = urldecode($name);
            $value = urldecode($value);
            if (!isset($parameters[$name])) {
                $parameters[$name] = $value;
            } elseif (is_array($parameters[$name])) {
                $parameters[$name][] = $value;
            } else {
                $parameters[$name] = array($parameters[$name], $value);
            }
        }

        return $parameters;
    }

    /**
     * 生成URL
     *
     * 过滤无意义的参数。
     * update by guya at 2009-08-17
     *
     * @param array $parameters URL参数数组
     * @return string
     */
    public static function encode_seo_parameters($parameters) {
        if (!$parameters) return;
        $string = 'W0';
        foreach($parameters as $name=>$value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    if($v) {
                        $string .= 'QQ' . urlencode($name) . 'Z' . urlencode($v);
                    }
                }
            } else {
                if($value || "0"==$value) {
                    $string .= 'QQ' . urlencode($name) . 'Z' . urlencode($value);
                }
            }
        }
        return $string=='W0' ? null : $string;
    }

    public function cutstr($string, $length, $dot = ' ...') {

        $charset = "utf-8";
        if(strlen($string) <= $length) {
            return $string;
        }

        $strcut = '';
        if(strtolower($charset) == 'utf-8') {

            $n = $tn = $noc = 0;
            while ($n < strlen($string)) {

                $t = ord($string[$n]);
                if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1; $n++; $noc++;
                } elseif(194 <= $t && $t <= 223) {
                    $tn = 2; $n += 2; $noc += 2;
                } elseif(224 <= $t && $t < 239) {
                    $tn = 3; $n += 3; $noc += 2;
                } elseif(240 <= $t && $t <= 247) {
                    $tn = 4; $n += 4; $noc += 2;
                } elseif(248 <= $t && $t <= 251) {
                    $tn = 5; $n += 5; $noc += 2;
                } elseif($t == 252 || $t == 253) {
                    $tn = 6; $n += 6; $noc += 2;
                } else {
                    $n++;
                }

                if ($noc >= $length) {
                    break;
                }

            }
            if ($noc > $length) {
                $n -= $tn;
            }

            $strcut = substr($string, 0, $n);

        } else {
            for($i = 0; $i < $length - 3; $i++) {
                $strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
            }
        }

        return $strcut.$dot;
    }

    public function cutchar($str,$lenth,$dot = "...")
    {
        $strlen = strlen($str);
        $charlen = 0;
        $cut = false;
        for($i=0;$i<$strlen;)
        {
            $charAt = ord($str[$i]);
            if(($charAt & 0xfe) == 0xfe)//占七个字节的汉字1111 1110
            {
                $i+=7;
                $charlen+=2;
            }
            else if(($charAt & 0xfc) == 0xfc)//占六个字节的汉字1111 1100
            {
                $i+=6;
                $charlen+=2;
            }
            else if(($charAt & 0xf8) == 0xf8)//占五个字节的汉字1111 1000
            {
                $i+=5;
                $charlen+=2;
            }
            else if(($charAt & 0xf0) == 0xf0)//占四个字节的汉字1111 0000
            {
                $i+=4;
                $charlen+=2;
            }
            else if(($charAt & 0xe0) == 0xe0)//占三个字节的汉字1110 0000
            {
                $i+=3;
                $charlen+=2;
            }
            else if(($charAt & 0xc0) == 0xc0)//占两个字节的汉字1100 0000
            {
                $i+=2;
                $charlen+=2;
            }
            else if(($charAt & 0x08) == 0x08)//
            {
                $i++;
                continue;
                //$charlen+=2;
            }
            else//($charAt < 128)//普通字符
            {
                $i++;
                $charlen ++;
                //echo $i." ".decbin($charAt)." | ";
            }

            if(($charlen+2) > $lenth*2)
            {
                $cut = true;
                break;
            }
            //echo "$i,";
        }
        if($cut)
        {
            return substr($str,0,$i).$dot;
        }
        else
        {
            return $str;
        }
    }
    
	public function cutmbstr($string, $length, $dot = ' ...') {
        if (mb_strlen($string, 'utf-8') >= $length) {
            return mb_substr($string, 0, $length, 'utf-8').$dot;
        } else {
            return $string;
        }
        $charset = "utf-8";
        if((strlen($string)+mb_strlen($string,'utf-8'))/2 <= $length) {
            return $string;
        }

        $strcut = '';
        if(strtolower($charset) == 'utf-8') {

            $n = $tn = $noc = 0;
            while ($n < strlen($string)) {

                $t = ord($string[$n]);
                if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1; $n++; $noc++;
                } elseif(194 <= $t && $t <= 223) {
                    $tn = 2; $n += 2; $noc += 2;
                } elseif(224 <= $t && $t < 239) {
                    $tn = 3; $n += 3; $noc += 2;
                } elseif(240 <= $t && $t <= 247) {
                    $tn = 4; $n += 4; $noc += 2;
                } elseif(248 <= $t && $t <= 251) {
                    $tn = 5; $n += 5; $noc += 2;
                } elseif($t == 252 || $t == 253) {
                    $tn = 6; $n += 6; $noc += 2;
                } else {
                    $n++;
                }

                if ($noc >= $length) {
                    break;
                }

            }
            if ($noc > $length) {
                $n -= $tn;
            }

            $strcut = substr($string, 0, $n);

        } else {
            for($i = 0; $i < $length - 3; $i++) {
                $strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
            }
        }

        return $strcut.$dot;
    }
}