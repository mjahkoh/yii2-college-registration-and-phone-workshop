<?php
use kartik\detail\DetailView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Authors;
use yii\helpers\Url ;
/**/
// View file rendering the widget

    $bordered	 = true;
    $striped 	= false;
    $condensed 	= false;
    $responsive = true;
    $hover = true;
    $hAlign = 'right';	//left/center
    $vAlign = 'middle';	//top/bottom
    $fadeDelay = 800;	//0-1500


/**/

// DetailView Attributes Configuration
$attributes = [
    [
        'group'=>true,
        'label'=>'SECTION 1: Identification Information',
        'rowOptions'=>['class'=>'info']
    ],
    [
        'columns' => [
            [
                'attribute'=>'id', 
                'label'=>'Book #',
                'displayOnly'=>true,
                'valueColOptions'=>['style'=>'width:30%']
            ],
            [
                'attribute'=>'book_code', 
                'format'=>'raw', 
                'value'=>'<kbd>'.$model->book_code.'</kbd>',
                'valueColOptions'=>['style'=>'width:30%'], 
                'displayOnly'=>true
            ],
        ],
    ],
    [
        'columns' => [
            [
                'attribute'=>'name',
                'valueColOptions'=>['style'=>'width:30%'],
            ],
            [
                'attribute'=>'color', 
                'format'=>'raw', 
                'value'=>"<span class='badge' style='background-color: {$model->color}'> </span>  <code>" . $model->color . '</code>',
                'type'=>DetailView::INPUT_COLOR,
                'valueColOptions'=>['style'=>'width:30%'], 
            ],
        ],
    ],
    [
        'group'=>true,
        'label'=>'SECTION 2: Price / Valuation Amounts',
        'rowOptions'=>['class'=>'info'],
        //'groupOptions'=>['class'=>'text-center']
    ],
    [
        'attribute'=>'buy_amount',
        'label'=>'Buy Amount ($)',
        'format'=>['decimal', 2],
        'inputContainer' => ['class'=>'col-sm-6'],
    ],
    [
        'attribute'=>'sell_amount',
        'label'=>'Sale Amount ($)',
        'format'=>['decimal', 2],
        'inputContainer' => ['class'=>'col-sm-6'],
    ],
    [
        'label'=>'Difference ($)',
        'value'=>$model->buy_amount - $model->sell_amount,
        'format'=>['decimal', 2],
        'inputContainer' => ['class'=>'col-sm-6'],
        // hide this in edit mode by adding `kv-edit-hidden` CSS class
        'rowOptions'=>['class'=>'warning kv-edit-hidden', 'style'=>'border-top: 5px double #dedede'],
    ],
    [
        'group'=>true,
        'label'=>'SECTION 3: Book Details',
        'rowOptions'=>['class'=>'info'],
        //'groupOptions'=>['class'=>'text-center']
    ],
    [
        'columns' => [
            [
                'attribute'=>'publish_date', 
                'format'=>'date',
                'type'=>DetailView::INPUT_DATE,
                'widgetOptions' => [
                    'pluginOptions'=>['format'=>'yyyy-mm-dd']
                ],
                'valueColOptions'=>['style'=>'width:30%']
            ],
            [
                'attribute'=>'status', 
                'label'=>'Available?',
                'format'=>'raw',
                'value'=>$model->status ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>',
                'type'=>DetailView::INPUT_SWITCH,
                'widgetOptions' => [
                    'pluginOptions' => [
                        'onText' => 'Yes',
                        'offText' => 'No',
                    ]
                ],
                'valueColOptions'=>['style'=>'width:30%']
            ],
        ]
    ],
    [
        'columns' => [
            [
                'attribute'=>'author_id',
                'format'=>'raw',
                'value'=>Html::a('John Steinbeck', '#', ['class'=>'kv-author-link']),
                'type'=>DetailView::INPUT_SELECT2, 
                'widgetOptions'=>[
                    'data'=>ArrayHelper::map(Authors::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
                ],
                'valueColOptions'=>['style'=>'width:30%']
            ],
            [
                'attribute'=>'rememberMe', 
                'label'=>'Remember?',
                'format'=>'raw',
                'type'=>DetailView::INPUT_SWITCH,
                'widgetOptions' => [
                    'pluginOptions' => [
                        'onText' => 'Yes',
                        'offText' => 'No',
                    ]
                ],
                'value'=>$model->rememberMe ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>',
                'valueColOptions'=>['style'=>'width:30%']
            ],
        ]
    ],
    [
        'attribute'=>'synopsis',
        'format'=>'raw',
        'value'=>'<span class="text-justify"><em>' . $model->synopsis . '</em></span>',
        'type'=>DetailView::INPUT_TEXTAREA, 
        'options'=>['rows'=>4]
    ]
];
echo DetailView::widget([
    'model' => $model,
    'attributes' => $attributes,
    'mode' => 'view',
    'bordered' => $bordered,
    'striped' => $striped,
    'condensed' => $condensed,
    'responsive' => $responsive,
    'hover' => $hover,
    'hAlign'=>$hAlign,
    'vAlign'=>$vAlign,
    'fadeDelay'=>$fadeDelay,
    'deleteOptions'=>[ // your ajax delete parameters
        'params' => ['id' => 1000, 'kvdelete'=>true],
    ],
    'container' => ['id'=>'kv-demo'],
    'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
]);

