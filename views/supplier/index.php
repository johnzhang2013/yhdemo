<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
//use yii\grid\GridView;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '系统供应商';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::a('全部导出', "javascript:void(0);", ['class' => 'btn btn-success gridview']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'supplier_container'],
        'layout' => "{summary}\n{items}\n{pager}",
        'columns' => [
            [
                "class" => CheckboxColumn::className(),
                "name" => "id",
            ],            
            
            [
                'label' => '供应商ID',
                'headerOptions' => ['width' => '10%'],
                'filter' => true,
                'attribute' => 'id',
                'enableSorting' => false,
                'format' => 'text'
            ],

            [
                'label' => '供应商代号',
                'headerOptions' => ['width' => '10%'],
                'filter' => true,
                'attribute' => 'code',
                'enableSorting' => false,
                'format' => 'text'
            ],
            
            [
                'label' => '供应商名称',
                'headerOptions' => ['width' => '20%'],
                'attribute' => 'name',
                'format' => 'text'
            ],
            
            [
                'label' => '合作状态',
                'attribute' => 't_status',
                'filter' => app\models\Supplier::dropDownSelect('t_status'),
                'value' => function($model){
                    return app\models\Supplier::dropDownSelect('t_status', $model->t_status);
                }
            ],
            
            [
                'label' => '所在省份',
                'headerOptions' => ['width' => '15%'],
                'attribute' => 'province',
                'enableSorting' => false
            ],

            [
                'label' => '所在城市',
                'headerOptions' => ['width' => '10%'],
                'attribute' => 'city',
                'enableSorting' => false,
                'value' => function($model){
                    return "{$model->city}市";
                }
            ],

            [
                'label' => '账期[天]',
                'attribute' => 'account_period',
                'filter' => app\models\Supplier::dropDownSelect('account_period'),
                'value' => function($model){
                    return app\models\Supplier::dropDownSelect('account_period', $model->account_period);
                }
            ],

            [
                'label' => '应付账款',
                'attribute' => 'account_payable_total',
                'headerOptions' => ['style'=>'color:red'],
                'contentOptions' => ['style'=>'color:blue'],
                'value' => function($model){
                    return "￥{$model->account_payable_total}";
                }
            ],

            [
                'class' => ActionColumn::className(),
                'headerOptions' => ['width' => '10%'],
                'header' => '操作',
                'template'=> '{view} {update}',
                'urlCreator' => function ($action, app\models\Supplier $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ]
    ]);?>

    <?= ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
        'encoding' => 'gb2312',
        'dropdownOptions' => [
            'label' => '导出',
            'class' => 'btn btn-default'
        ],
        'exportConfig' => [
            ExportMenu::FORMAT_TEXT => false,
            ExportMenu::FORMAT_PDF => false,
            ExportMenu::FORMAT_EXCEL_X => false,
        ],
        'columnSelectorOptions'=>[
            'label' => '选择字段',
        ],
        'filename' => '供应商列表_'.date('Y-m-d'),
        'selectedColumns'=> [1, 2],//导出不选中#和操作栏
        'hiddenColumns'=>[0, 3], //隐藏#和操作栏
        ]);
    ?>    
</div>