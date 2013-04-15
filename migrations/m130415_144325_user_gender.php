<?php

class m130415_144325_user_gender extends CDbMigration
{
    const TABLE = 'User';
    public function up() {
        $this->addColumn(self::TABLE, 'gender', 'ENUM("male", "female") NULL DEFAULT NULL');
    }

    public function down() {
        $this->dropColumn(self::TABLE, 'gender');
    }
}