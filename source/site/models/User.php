<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dima
 * Date: 12/16/11
 * Time: 8:46 PM
 */

namespace site\models;

use shared\interfaces\RestRecord;

class User extends \shared\models\User implements RestRecord
{
    public function getPublicAttributes() {
    }
}
