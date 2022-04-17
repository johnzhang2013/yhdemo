<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class SupplierSearch extends Supplier
{
	public function rules(){
        //声明该模型可搜索的字段
        return [
            [['id'], 'integer'],
            [['code'], 'string'],
            [['name'], 'string'],
            [['province'], 'string'],
            [['city'], 'string'],
            [['account_period'], 'integer'],
            [['t_status'], 'string'],
        ];
    }
 
    public function scenarios()
    {
        return Model::scenarios();
    }
 
    public function search($params)
    {
        $query = Supplier::find();
 
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
 
        //从参数的数据中加载过滤条件，并验证
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
 
        //增加过滤条件来调整查询对象
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['code' => $this->code]);
        $query->andFilterWhere(['province' => $this->province]);
        $query->andFilterWhere(['city' => $this->city]);
        $query->andFilterWhere(['account_period' => $this->account_period]);
        $query->andFilterWhere(['t_status' => $this->t_status]);
        $query->andFilterWhere(['like', 'name', $this->name]);
 
        return $dataProvider;
    }
}