<?php

namespace app\controllers;

use Yii;

use app\models\Supplier;
use app\models\SupplierSearch;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\grid\CheckboxColumn;
use yii\grid\ActionColumn;
use yii\helpers\Url;

/**
 * SupplierController implements the CRUD actions for Supplier model.
 */
class SupplierController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Supplier models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SupplierSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $dataProvider->setSort(false);        

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'gvColumns' => $this->makeGridViewColumns()
        ]);
    }

    /**
     * Displays a single Supplier model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Supplier model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Supplier();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Supplier model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Supplier model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Supplier model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Supplier the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Supplier::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function makeGridViewColumns(){
        return [
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
                'filter' => Supplier::dropDownSelect('t_status'),
                'value' => function($model){
                    return Supplier::dropDownSelect('t_status', $model->t_status);
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
                'filter' => Supplier::dropDownSelect('account_period'),
                'value' => function($model){
                    return Supplier::dropDownSelect('account_period', $model->account_period);
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
                'urlCreator' => function ($action, Supplier $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ];
    }
}
