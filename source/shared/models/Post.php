<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace shared\models;
/**
 * @property User     $author
 * @property Interest[] $interests
 */
class Post extends \base\ActiveRecord
{
    const INTEREST_RELATION_TABLE = 'Interest_Post';
    const MAX_POST_SIZE = 250;

    public $id;
    public $User_id;
    public $dateSubmitted;
    public $content;
    public $htmlContent;

    public function rules() {
        return [
            'content-length' => ['content', 'length', 'max' => self::MAX_POST_SIZE],
            ['interests', 'required'],
        ];
    }

    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->dateSubmitted = new \CDbExpression('NOW()');
        }
        return parent::beforeSave();
    }

    public function relations() {
        return [
            'author'    => [self::BELONGS_TO, $this->ns('User'), 'User_id'],
            'interests' => [self::MANY_MANY, $this->ns('Interest'), self::INTEREST_RELATION_TABLE . '(Post_id, Interest_id)'],
        ];
    }


}
