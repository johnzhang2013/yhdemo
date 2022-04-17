<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%supplier_basic}}`.
 */
class m220416_031309_create_supplier_basic_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    protected $tableName = 'supplier';
    public function safeUp()
    {
        $tableOptions = null;
        if($this->db->driverName == 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $this->createTable(
            $this->tableName, 
            [
                'id' => $this->primaryKey(),
                'code' => $this->char(16)->notNull()->unique()->comment('供应商代号'),
                'name' => $this->string(50)->notNull()->comment('供应商名称'),

                'province' => $this->string(32)->null()->comment('供应商所在省份'),
                'city' => $this->string(32)->null()->comment('供应商所在城市'),

                'account_period' => $this->tinyInteger()->null()->defaultValue(0)->comment('账期(天)'),
                'account_payable_total' => $this->decimal(10, 2)->null()->defaultValue(0.00)->comment('应付账款-总额')                
            ],
            $tableOptions
        );

        //Yii貌似不能像上面那样直接定义enum类型的字段，故只能采用addColumn方式
        $this->addColumn($this->tableName, 't_status', "enum('OK','HOLD') CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT 'OK' COMMENT '供应商状态 OK=正常,HOLD=冻结'");

        $this->createIndex('s_province', $this->tableName, 'province');
        $this->createIndex('s_city', $this->tableName, 'city');
        $this->createIndex('s_account_period', $this->tableName, 'account_period');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
