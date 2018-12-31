<?php

namespace app\models;
use kartik\builder\TabularForm;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "book".
 *
 * @property integer $id
 * @property string $book_code
 * @property string $synopsis
 * @property string $color
 * @property string $publish_date
 * @property string $sell_amount
 * @property string $buy_amount
 * @property integer $status
 * @property integer $author_id
 * @property string $name
 *
 * @property Authors $author
 */
class Book extends \yii\db\ActiveRecord
{
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['name', 'buy_amount', 'author_id', 'sell_amount' , 'publish_date'], 'required'],
            [['synopsis'], 'string'],
            [['publish_date', 'name', 'buy_amount','status'], 'safe'],
            [['sell_amount', 'buy_amount'], 'number', 'min'=>0, 'max'=>5000],
            [['buy_amount', 'sell_amount'], 'number'],
			[['status', 'author_id'], 'integer'],
            [['book_code', 'name'], 'string', 'max' => 30],
            [['color'], 'string', 'max' => 10],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Authors::className(), 'targetAttribute' => ['author_id' => 'author_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'book_code' => Yii::t('app', 'Book Code'),
            'synopsis' => Yii::t('app', 'Synopsis'),
            'color' => Yii::t('app', 'Color'),
            'publish_date' => Yii::t('app', 'Publish Date'),
            'sell_amount' => Yii::t('app', 'Sell Amount'),
            'buy_amount' => Yii::t('app', 'Buy Amount'),
            'status' => Yii::t('app', 'Status'),
            'author_id' => Yii::t('app', 'Author'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasOne(Authors::className(), ['author_id' => 'author_id']);
    }

    /**
     * @inheritdoc
     * @return BookQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BookQuery(get_called_class());
    }
	
	/*example 1 start*/
	public function getFormAttribs() {
		return [
			// primary key column
			'id'=>[ // primary key attribute
				'type'=>TabularForm::INPUT_HIDDEN, 
				'columnOptions'=>['hidden'=>true]
			], 
			'name'=>['type'=>TabularForm::INPUT_TEXT],
			'publish_date'=>[
				'type' => function($model, $key, $index, $widget) {
					//return ($key % 2 === 0) ? TabularForm::INPUT_HIDDEN : TabularForm::INPUT_WIDGET;
					return TabularForm::INPUT_WIDGET;
				},
				'widgetClass'=>\kartik\widgets\DatePicker::classname(), 
				'options'=> function($model, $key, $index, $widget) {
					return ($key % 2 === 0) ? [] :
					[ 
						'pluginOptions'=>[
							'format'=>'yyyy-mm-dd',
							'todayHighlight'=>true, 
							'autoclose'=>true
						]
					];
				},
				'columnOptions'=>['width'=>'170px']
			],
			'color'=>[
				'type'=>TabularForm::INPUT_WIDGET, 
				'widgetClass'=>\kartik\widgets\ColorInput::classname(), 
				'options'=>[ 
					'showDefaultPalette'=>false,
					'pluginOptions'=>[
						'preferredFormat'=>'name',
						'palette'=>[
							[
								"white", "black", "grey", "silver", "gold", "brown", 
							],
							[
								"red", "orange", "yellow", "indigo", "maroon", "pink"
							],
							[
								"blue", "green", "violet", "cyan", "magenta", "purple", 
							],
						]
					]
				],
				'columnOptions'=>['width'=>'150px'],
			],
			
			'author_id'=>[
				'type'=>TabularForm::INPUT_DROPDOWN_LIST, 
				'items'=>ArrayHelper::map(Authors::find()->orderBy('name')->asArray()->all(), 'author_id', 'name'),
				'columnOptions'=>['width'=>'185px']
			],
			
			'buy_amount'=>[
				'type'=>TabularForm::INPUT_TEXT, 
				'label'=>'Buy',
				'options'=>['class'=>'form-control text-right'], 
				'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT, 'width'=>'90px']
			],
			'sell_amount'=>[
				'type'=>TabularForm::INPUT_STATIC, 
				'label'=>'Sell',
				'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT, 'width'=>'90px']
			],
			
			'status'=>[
				'type'=>TabularForm::INPUT_CHECKBOX , 
				'label'=>'Status',
			],			
			/**/
		];
	}
	
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			$this->publish_date = Yii::$app->formatter->asDate($this->publish_date,'yyyy-MM-dd');
			return true;
		} else {
			return false;
		}
	}
	
}
