<?php

use yii\db\Migration;

class m261029_000002_create_solicitudes_abastecimiento extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%solicitudes_abastecimiento}}', [
            'id_solicitud' => $this->primaryKey(),
            'id_reporte' => $this->integer()->null(),
            'id_material' => $this->integer()->notNull(),
            'id_usuario_solicita' => $this->integer()->notNull(),
            'cantidad_solicitada' => $this->decimal(10, 2)->notNull(),
            'fecha_solicitud' => $this->dateTime()->notNull(),
            'estado' => $this->string(30)->notNull()->defaultValue('Pendiente'),
            'observaciones' => $this->text()->null(),
        ]);

        $this->createIndex('idx_solicitudes_reporte', '{{%solicitudes_abastecimiento}}', 'id_reporte');
        $this->createIndex('idx_solicitudes_material', '{{%solicitudes_abastecimiento}}', 'id_material');
        $this->createIndex('idx_solicitudes_usuario', '{{%solicitudes_abastecimiento}}', 'id_usuario_solicita');
        $this->addForeignKey(
            'fk_solicitudes_reportes',
            '{{%solicitudes_abastecimiento}}',
            'id_reporte',
            '{{%reportes}}',
            'id_reporte',
            'SET NULL',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_solicitudes_materiales',
            '{{%solicitudes_abastecimiento}}',
            'id_material',
            '{{%materiales}}',
            'id_material',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_solicitudes_usuarios',
            '{{%solicitudes_abastecimiento}}',
            'id_usuario_solicita',
            '{{%usuarios}}',
            'id_usuario',
            'RESTRICT',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_solicitudes_usuarios', '{{%solicitudes_abastecimiento}}');
        $this->dropForeignKey('fk_solicitudes_materiales', '{{%solicitudes_abastecimiento}}');
        $this->dropForeignKey('fk_solicitudes_reportes', '{{%solicitudes_abastecimiento}}');
        $this->dropTable('{{%solicitudes_abastecimiento}}');
    }
}
