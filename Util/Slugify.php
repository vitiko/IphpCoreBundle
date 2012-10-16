<?php
namespace Iphp\CoreBundle\Util;


class Slugify
{


    static function slugifyPreserveWords($str, $maxLen)
    {
        $str = Translit::translit($str);
        if (strlen($str) >= $maxLen) {
            $words = explode ('-', $str);

            //print_r ($words);

            do {
                $len = 0;
                $useWords = sizeof($words);
                $ok = true;
                foreach ($words as $p => $word) {
                   // print "\n".$word;
                    $len += mb_strlen($word)+1;
                  //  print ' '.$len;
                    if ($len > $maxLen)
                    {
                        $useWords = $p;
                      //  print '--'.$useWords;
                        break;
                    }

                }

                if ($useWords > 1 &&  strlen($words[$useWords-1]) < 2)
                {
                    unset($words[$useWords-1]);
                    $words = array_values ($words);
                    $ok = false;
                }
            }
            while (!$ok);

           // print "\n".$str.' - use words '.$useWords.', len=';

            $str = $useWords > 0 ? implode ('-', array_slice ($words,0,$useWords)) : substr ($str,0, $maxLen);

           // print "\n".$str.'('.strlen($str).')';
           // exit();
        }
        return $str;
    }
}