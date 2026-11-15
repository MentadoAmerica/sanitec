<?php

use yii\db\Migration;

class m261114_230428_add_pin_to_alumnos extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            '{{%alumnos_servicio_social}}',
            'pin',
            $this->string(10)->null()->after('matricula')
        );

        $this->createIndex(
            'idx_alumnos_pin',
            '{{%alumnos_servicio_social}}',
            'pin',
            true
        );
    }

    public function safeDown()
    {
        $this->dropIndex('idx_alumnos_pin', '{{%alumnos_servicio_social}}');
        $this->dropColumn('{{%alumnos_servicio_social}}', 'pin');
    }
}