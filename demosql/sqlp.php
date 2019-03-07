<?php

    testArray($_GET);
    testArray($_POST);
    testArray($_COOKIE);

    function testArray($array)
    {
        foreach ($array as $name => $value)
        {
            if(is_array($value) === true)
            {
                testArray($value);
            }
            else
            {
                testHelper($value);
            }
        }
    }

    function testHelper($varvalue)
    {
        $total = test($varvalue);
        echo '<h3 style="'.($total > 0 ? 'color:red;' : 'color:green;').'">'.nl2br($varvalue).'</h3>';
        echo '<span style="'.($total > 0 ? 'color:red;' : 'color:green;').'">';
        echo 'total = '.$total.'
';
        echo '</span><br />';
    }

    function test($varvalue, $_comment_loop=false)
    {
        $total = 0;
        $varvalue_orig = $varvalue;
        $quote_pattern = '\%27|\'|\%22|\"|\%60|`';
//      detect base64 encoding
        if(preg_match('/^[a-zA-Z0-9\/+]*={0,2}$/', $varvalue) > 0 && base64_decode($varvalue) !== false)
        {
            $varvalue = base64_decode($varvalue);
        }

//      detect and remove comments
        if(preg_match('!/\*.*?\*/!s', $varvalue) > 0)
        {
            if($_comment_loop === false)
            { 
                $total += test($varvalue_orig, true);
                $varvalue = preg_replace('!/\*.*?\*/!s', '', $varvalue);
            }
            else
            {
                $varvalue = preg_replace('!/\*.*?\*/!s', ' ', $varvalue);
            }
            $varvalue = preg_replace('/\n\s*\n/', "\n", $varvalue);
        }
        $varvalue = preg_replace('/((\-\-|\#)([^\\n]*))\\n/si', ' ', $varvalue);

//      detect and replace hex encoding
//      detect and replace decimal encodings
        if(preg_match_all('/&#x([0-9]{2});/', $varvalue, $matches) > 0 || preg_match_all('/&#([0-9]{2})/', $varvalue, $matches) > 0)
        {
//          replace numeric entities
            $varvalue = preg_replace('/&#x([0-9a-f]{2});?/ei', 'chr(hexdec("\\1"))', $varvalue);
            $varvalue = preg_replace('/&#([0-9]{2});?/e', 'chr("\\1")', $varvalue);
//          replace literal entities
            $trans_tbl = get_html_translation_table(HTML_ENTITIES);
            $trans_tbl = array_flip($trans_tbl);
            $varvalue = strtr($varvalue, $trans_tbl);
        }

        $and_pattern = '(\%41|a|\%61)(\%4e|n|%6e)(\%44|d|\%64)';
        $or_pattern = '(\%6F|o|\%4F)(\%72|r|\%52)';
        $equal_pattern = '(\%3D|=)';
        $regexes = array(
                '/(\-\-|\#|\/\*)\s*$/si',
                '/('.$quote_pattern.')?\s*'.$or_pattern.'\s*(\d+)\s*'.$equal_pattern.'\s*\\4\s*/si',
                '/('.$quote_pattern.')?\s*'.$or_pattern.'\s*('.$quote_pattern.')(\d+)\\4\s*'.$equal_pattern.'\s*\\5\s*/si',
                '/('.$quote_pattern.')?\s*'.$or_pattern.'\s*(\d+)\s*'.$equal_pattern.'\s*('.$quote_pattern.')\\4\\6?/si',
                '/('.$quote_pattern.')?\s*'.$or_pattern.'\s*('.$quote_pattern.')?(\d+)\\4?/si',
                '/('.$quote_pattern.')?\s*'.$or_pattern.'\s*('.$quote_pattern.')([^\\4]*)\\4\\5\s*'.$equal_pattern.'\s*('.$quote_pattern.')/si',
                '/((('.$quote_pattern.')\s*)|\s+)'.$or_pattern.'\s+([a-z_]+)/si',
                '/('.$quote_pattern.')?\s*'.$or_pattern.'\s+([a-z_]+)\s*'.$equal_pattern.'\s*(d+)/si',
                '/('.$quote_pattern.')?\s*'.$or_pattern.'\s+([a-z_]+)\s*'.$equal_pattern.'\s*('.$quote_pattern.')/si',
                '/('.$quote_pattern.')?\s*'.$or_pattern.'\s*('.$quote_pattern.')([^\\4]+)\\4\s*'.$equal_pattern.'\s*([a-z_]+)/si',
                '/('.$quote_pattern.')?\s*'.$or_pattern.'\s*('.$quote_pattern.')([^\\4]+)\\4\s*'.$equal_pattern.'\s*('.$quote_pattern.')/si',
                '/('.$quote_pattern.')?\s*\)\s*'.$or_pattern.'\s*\(\s*('.$quote_pattern.')([^\\4]+)\\4\s*'.$equal_pattern.'\s*('.$quote_pattern.')/si',
                '/('.$quote_pattern.'|\d)?(;|%20|\s)*(union|select|insert|update|delete|drop|alter|create|show|truncate|load_file|exec|concat|benchmark)((\s+)|\s*\()/ix',
                '/from(\s*)information_schema.tables/ix',
            );

        foreach ($regexes as $regex)
        {
            $total += preg_match($regex, $varvalue);
        }
        return $total;
    }