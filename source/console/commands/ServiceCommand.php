<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */

class ServiceCommand extends CConsoleCommand
{
    public function actionCleanDb() {

        $db = Yii()->db->createCommand('SELECT DATABASE()')->queryScalar();
        $user = Yii()->db->username;
        $password = Yii()->db->password;
        $dump = Yii()->runtimePath . '/db-' . date("Y-m-d+H_i_s") . '.sql';

        $command = sprintf(
            'mysqldump --user=%s --password=%s %s > %s',
            $user, $password, $db, $dump
        );

        echo "Dumping db to $dump file\n";
        passthru($command);

        Yii()->db->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $tables = Yii()->db->createCommand('SHOW TABLES')->queryColumn();

        $tables = array_filter(
            $tables,
            function ($table) {
                return substr($table, 0, 3) != 'Yii';
            }
        );

        foreach ($tables as $table) {
            echo "Trancating $table\n";
            Yii()->db->createCommand("TRUNCATE `$table`")->execute();
        }

        Yii()->db->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}