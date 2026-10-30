<?php

use yii\db\Migration;

class m261029_000001_add_service_social_verification_details extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%verificaciones}}', 'checklist_json', $this->text()->null());
        $this->addColumn('{{%verificaciones}}', 'evidencia_foto', $this->string(500)->null());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%verificaciones}}', 'evidencia_foto');
        $this->dropColumn('{{%verificaciones}}', 'checklist_json');
    }
}
