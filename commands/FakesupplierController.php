<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;

use app\models\Supplier as SupplierModel;
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class FakesupplierController extends Controller
{
    /**
     * This command make faked suppliers for project development
     * @param string $number the number of suppliers to be produced.
     * @return int Exit code
     */
    public function actionProduce($number = 100)
    {
        $locale = 'zh_CN';

        $faker = \Faker\Factory::create($locale);
        $number = (int) $number;

        if($number <= 0){
            echo 'Wrong parameter[number]'.PHP_EOL;
            return ExitCode::OK;
        }

        //供应商账期
        $ap_cfgs = [7, 15, 30, 60, 90];
        $ap_lens = count($ap_cfgs);

        //供应商-应付账款
        $ap_max = 10000.00;
        $ap_min = 0.00;

        for($i = 0; $i < $number; $i++){
            $_scode = $this->makeSupplierCode();//供应商代号
            $_sname = $faker->company;//供应商名称
            $_sprovince= $faker->state;//供应商所在省份
            $_scity = $faker->city;//供应商所在城市

            $_scode_chk = $this->checkSupplierCodeExists($_scode);
            if($_scode_chk == true){//确保能Fake到$number数量的供应商数据
                $i--;
                continue;
            }else{
                $_sname_chk = $this->checkSupplierNameExists($_sname);
                if($_sname_chk == true){
                    $i--;
                    continue;
                }
            }

            $_supplier = new SupplierModel();
            
            $_supplier->name = $_sname;
            $_supplier->code = $_scode;
            $_supplier->t_status = ($faker->boolean) ? 'OK' : 'HOLD';

            $_supplier->province = $_sprovince;
            $_supplier->city = $_scity;

            $_supplier->account_period = $ap_cfgs[rand(0, $ap_lens - 1)];
            $_supplier->account_payable_total = $this->makeSupplierAccountPayableTotal($ap_min, $ap_max);

            $res = $_supplier->save();
            if($res == true){
                echo 'Fake supplier['.$_scode.']'.($i + 1).'/'.$number.' success'.PHP_EOL;
            }else{
                echo 'Fake supplier['.$_scode.']'.($i + 1).'/'.$number.' failure'.PHP_EOL;
            }
        }

        return ExitCode::OK;
    }

    //生成供应商-代号
    private function makeSupplierCode(){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $lens = strlen($chars);
        $rand_code = '';

        for($i = 0; $i < 8; $i++){
            $rand_code .= $chars[mt_rand(0, $lens - 1)];
        }

        return strtoupper($rand_code);
    }
    
    //生成供应商-应付账款总额
    private function makeSupplierAccountPayableTotal($min = 0.00, $max = 0.00){
        $ap_total = $min + (mt_rand() / mt_getrandmax() * ($max - $min));

        return round($ap_total, 2);
    }

    //判断供应商代号-是否已存在
    private function checkSupplierCodeExists($supplier_code = ''){
        $_s = SupplierModel::find()->where(['code' => $supplier_code])->one();
        return ($_s == null) ? false : true;
    }

    //判断供应商名称-是否已存在
    private function checkSupplierNameExists($supplier_name = ''){
        $_s = SupplierModel::find()->where(['name' => $supplier_name])->one();
        return ($_s == null) ? false : true;
    }
}
