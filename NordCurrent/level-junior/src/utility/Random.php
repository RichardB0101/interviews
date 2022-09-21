<?php 
declare(strict_types=1);
namespace Example\Utility;

final class Random
{
    public static function generateNumbers(int $length) : int
    {
        $random = '';

        for ($i=0;$i<$length;$i++)
        {
            if ($i==0){$random.=rand(1, 9);}
            else {$random.=rand(0, 9);}
        }

        return intval($random);
    }

    public static function generateCharacters(int $length) : string
    {
        $random = '';
        $max = ceil($length / 40);

        for ($i = 0; $i < $max; $i ++) {
          $random .= sha1(microtime(true).mt_rand(10000,90000));
        }

        return substr($random, 0, $length);
    }
    //Borrowed for a bit switch case from your source code and put it here for later use, hopefully I don't break the interview task rules :)
    public static function generateRandomVersion() : string{
        {
            $ran = rand(1,3);
            switch($ran)
            {
                case 1:{
                    return "1.0";
                    break;
                }
                case 2:{
                    return "1.1";
                    break;
                }
                case 3:{
                    return "1.2";
                    break;
                }
                default :{
                    return "1.0";
                }
            }
        }
    }
    //Created method to generate random region
    public static function generateRandomRegion() : string{
        {
            $ran = rand(1,4);
            switch($ran)
            {
                case 1:{
                    return "lt";
                    break;
                }
                case 2:{
                    return "en";
                    break;
                }
                case 3:{
                    return "us";
                    break;
                }
                case 4:{
                    return "fr";
                    break;
                }
                default :{
                    return "lt";
                }
            }
        }
    }
}