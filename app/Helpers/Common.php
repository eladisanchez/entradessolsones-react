<?php
namespace App\Helpers;

use Carbon\Carbon;
use DB;

class Common
{

    public static function seat($seient) {

        if (!is_object($seient)) {
            if ( strlen($seient) < 5 ) {
                

                return __('Fila').' '.substr($seient,0,-2).' '.__('Seient').' '.(int)substr($seient,-2);

            }
            $seient = json_decode($seient);
            
        } 
        if(empty($seient->f) || $seient->f==0) {
            return __('Localitat').' '.$seient->s;
        }
        return __('Fila').' '.$seient->f.' '.__('Seient').' '.$seient->s;
        
    }

    public static function seientSmall($seient) {
        if($seient->f==0) {
            return __('Localitat').' '.$seient->s;
        }
        return 'F'.$seient->f.'/S'.$seient->s;
    }

    public static function optext($key)
    {
        $text = DB::table('options')->select('value')->where('key',$key)->first();
        if(!$text) {
            return '';
        }
		return $text->value;
    }

    public static function data($carbon)
    {
        $carbondia = Carbon::parse($carbon);
        //$carbondia = Carbon::createFromFormat('Y-m-d', $carbon);
        return trans('calendar.'.$carbondia->format('l')).' '.$carbondia->format('d').' '.trans('calendar.'.$carbondia->format('F')).' '.$carbondia->format('Y');
    }

    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
     }


    public static function qrbase64($uniqid,$count)
    {
        $path = 'https://entradessolsones.com/qr/';
        $qr = base64_encode($uniqid.$count);
        $content = \QRCode::text($path.$qr)->png();
        return base64_encode($content);
    }

}