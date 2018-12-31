<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "companies_files".
 *
 * @property int $id
 * @property string $filename this is the code entered by the user
 * @property int $company_id
 */
class CompaniesFiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'companies_files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id'], 'integer'],
            [['filename'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filename' => 'Filename',
            'company_id' => 'Company ID',
        ];
    }

    public function getCompanies()
    {
        return $this->hasOne(Companies::className(), ['id' => 'company_id']);
    }


    /**
     * {@inheritdoc}
     * @return CompaniesFilesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CompaniesFilesQuery(get_called_class());
    }
}
