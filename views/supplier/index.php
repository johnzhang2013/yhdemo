<?php

use yii\helpers\Html;

use kartik\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '系统供应商';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="supplier-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gvColumns,
        'encoding' => 'gb2312',
        'dropdownOptions' => [
            'label' => '导出',
            'class' => 'btn btn-default'
        ],
        'exportConfig' => [
            ExportMenu::FORMAT_HTML => false,
            ExportMenu::FORMAT_TEXT => false,
            ExportMenu::FORMAT_PDF => false,
            ExportMenu::FORMAT_EXCEL_X => false,
        ],
        'columnSelectorOptions'=>[
            'label' => '选择字段',
            'class' => 'btn btn-danger'
        ],
        'filename' => '供应商列表_'.date('Y-m-d'),
        'selectedColumns'=> [1,2,3,4,5,6,7,8],//导出不选中#和操作栏
        'hiddenColumns'=>[0, 9], //隐藏#和操作栏
        'fontAwesome' => true,
    ]);
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'supplier_container'],
        'layout' => "{summary}\n{items}\n{pager}",
        'columns' => $gvColumns,
        'export' => false
    ]);?>    
</div>