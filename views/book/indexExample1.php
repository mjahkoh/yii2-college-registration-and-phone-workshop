<?php
use kartik\builder\TabularForm;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\grid\EditableColumn;
use kartik\editable\Editable;
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

    <h1><?= Html::encode('TabularForm usage  with ActiveForm') ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Book'), ['create-index-example1'], ['class' => 'btn btn-success']) ?>
    </p>
<?php 

Pjax::begin(); 

$form = ActiveForm::begin();
$attribs = $searchModel->formAttribs;
unset($attribs['color']);	//$attribs['attributes']['status'] = [
/*
$attribs['status'] = [					//$attribs['status'] = [
    'type'=>TabularForm::INPUT_WIDGET, 
    'widgetClass'=>\kartik\widgets\SwitchInput::classname()
];*/
/**/

/*example 3 start*/
echo TabularForm::widget([
    'dataProvider'=>$dataProvider,
    'form'=>$form,
    'attributes'=>$attribs,
    'gridSettings'=>[
        'floatHeader'=>true,
        'panel'=>[
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Manage Books</h3>',
            'type' => GridView::TYPE_PRIMARY,
            'after'=> Html::a('<i class="glyphicon glyphicon-plus"></i> Add New', '#', ['class'=>'btn btn-success']) . ' ' . 
                    Html::a('<i class="glyphicon glyphicon-remove"></i> Delete', '#', ['class'=>'btn btn-danger']) . ' ' .
                    Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Save', ['class'=>'btn btn-primary'])
        ]
    ]	
]);

// Add other fields if needed or render your submit button
echo '<div class="text-right">' . 
     Html::submitButton('Submit', ['class'=>'btn btn-primary']) .
     '<div>';

ActiveForm::end();
Pjax::end(); 
?>
</div>
