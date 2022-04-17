<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "supplier".
 *
 * @property int $id
 * @property string $name 供应商名称
 * @property string $code 供应商代号
 * @property string|null $t_status 供应商状态 OK=正常,HOLD=冻结
 */
class Supplier extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supplier';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['t_status'], 'string'],
            [['name'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 8],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code' => 'Code',
            't_status' => 'T Status',
        ];
    }

    public static function dropDownSelect($column = '', $value = null){
        //下面这类配置项-更好做法是放到config下
        $drops = [
            't_status' => ['OK' => '正常', 'HOLD' => '冻结'],
            'account_period' => ['7' => '7天', '15' => '15天', '30' => '30天', '60' => '60天', '90' => '90天']
        ];

        if($value !== null){
            return array_key_exists($column, $drops) ? $drops[$column][$value] : false;
        }else{
            return array_key_exists($column, $drops) ? $drops[$column] : false;
        }
    }
}
