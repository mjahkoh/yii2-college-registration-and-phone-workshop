<?php
//use kartik\builder\TabularForm;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\editable\Editable;
use kartik\editable\EditableColumn;
use kartik\widgets\ColorInput;
use yii\helpers\ArrayHelper;
use app\models\Authors;
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;
use kartik\widgets\ActiveForm;
use kartik\widgets\DepDrop;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Books');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode("How to setup an editable column to manipulate records in Yii2 grid view") ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Book'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

<?php 

Pjax::begin();
$form = ActiveForm::begin();

/*example 2 start*/
// the grid columns setup (only two column entries are shown here
// you can add more column entries you need for your use case)

// the grid columns setup (only two column entries are shown here
// you can add more column entries you need for your use case)
$gridColumns = [
// the name column configuration
[
    'class'=>'kartik\grid\EditableColumn',
    'attribute'=>'name',
    'pageSummary'=>true,
    'editableOptions'=> function ($model, $key, $index) {
        return [
            'header'=>'Name',
            'size'=>'md',
            'afterInput'=>function ($form, $widget) use ($model, $index) {
                return $form->field($model, "color")->widget(\kartik\widgets\ColorInput::classname(), [
                    'showDefaultPalette'=>false,
                    'options'=>['id'=>"color-{$index}"],
                    'pluginOptions'=>[
                        'showPalette'=>true,
                        'showPaletteOnly'=>true,
                        'showSelectionPalette'=>true,
                        'showAlpha'=>false,
                        'allowEmpty'=>false,
                        'preferredFormat'=>'name',
                        'palette'=>[
                            ["white", "black", "grey", "silver", "gold", "brown"],
                            ["red", "orange", "yellow", "indigo", "maroon", "pink"],
                            ["blue", "green", "violet", "cyan", "magenta", "purple"],
                        ]
                    ],
                ]);
            }
        ];
    }
],
// the buy_amount column configuration
[
    'class'=>'kartik\grid\EditableColumn',
    'attribute'=>'buy_amount',
    /**/
	'editableOptions'=>[
        'header'=>'Buy Amount',
        'inputType'=>\kartik\editable\Editable::INPUT_SPIN,
        'options'=>['pluginOptions'=>['min'=>0, 'max'=>5000]]
    ],
    'hAlign'=>'right',
    'vAlign'=>'middle',
    'width'=>'100px',
    'format'=>['decimal', 2],
    'pageSummary'=>true
],
];

// the GridView widget (you must use kartik\grid\GridView)
echo kartik\grid\GridView::widget([
    'dataProvider'=>$dataProvider,
    'filterModel'=>$searchModel,
    'columns'=>$gridColumns,
	'export'=>false,
	'pjax'=>true,
]);
/*example 2 end*/


// Add other fields if needed or render your submit button
echo '<div class="text-right">' . 
     Html::submitButton('Submit', ['class'=>'btn btn-primary']) .
     '<div>';

ActiveForm::end();

Pjax::end(); 

?>

</div>
