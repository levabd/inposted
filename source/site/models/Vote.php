<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\models;
class Vote extends \base\ActiveRecord{

    const TYPE_LIKE = 'like';
    const TYPE_ABUSE = 'abuse';
    const TYPE_SPAM = 'spam';
    const TYPE_IRRELEVANT = 'irrelevant';
    const TYPE_NONSENSE = 'nonsense';
    const TYPE_DUPLICATE = 'duplicate';
}
