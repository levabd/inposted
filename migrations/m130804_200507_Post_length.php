<?php

class m130804_200507_Post_length extends CDbMigration
{
    public function up() {
        $this->alterColumn('Post', 'content', 'text');
    }

    public function down() {
        $this->alterColumn('Post', 'content', 'varchar(500) NOT NULL');
    }
}