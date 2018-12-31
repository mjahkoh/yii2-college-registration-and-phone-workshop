<?php
namespace app\helpers;

//use app\models\User;
use Yii;

/**
 * Css helper class.
 */
class Common
{
    /**
     * checks wether the string is AlphaNumeric .
     * returns true, false.
     *
     * @param  string .
     * .
     */
    public static function isAlphaNumeric($string)
    {
		if (!preg_match('/[^0-9A-Za-z]/', $string)) {
			return false;
		}

        return true;     
    }


	public function isNumeric($string)
	{
		if (!preg_match('/^[0-9]/', $string)) {
			return false;
		}
		return true;     
	}
	

}