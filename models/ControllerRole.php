<?php
 
namespace app\models;

use Yii;
use yii\base\Model;
/**
 * Password reset request form
 */
class ControllerRole extends Model
{
    public $controllerName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['controllerName'], 'string', 'max' => 100],
			['controllerName', 'required'],
        ];
    }

}
