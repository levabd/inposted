<?php
namespace site\models;
use base\Randomizr;

class User extends \shared\models\User
{
    use InterestRelationTrait;

    public $password;
    public $newPassword;

    public function rules() {
        return array_merge(
            parent::rules(),
            [
            ['nickname', 'validateNickname'],
            ['newPassword', 'required', 'on' => 'signup-1'],
            ['newPassword', 'length', 'min' => 6],
            ['newPassword', 'compare', 'operator' => '!=', 'compareAttribute' => 'email'],
            ['newPassword', 'compare', 'operator' => '!=', 'compareAttribute' => 'nickname'],
            ['newPassword', 'site\validators\Password'],

            //            ['password', 'required', 'on' => 'settings'],
            ['newPassword', 'checkPassword', 'on' => 'settings'],
            ['password', 'safe'],
            ]
        );
    }

    public function validateNickname($attribute) {
        if (is_numeric(mb_substr($this->$attribute, 0, 1, Yii()->charset))) {
            $this->addError(
                $attribute,
                \Yii::t(
                    'inposted', '{attribute} can not start with digit',
                    ['{attribute}' => \Yii::t('inposted', $this->getAttributeLabel($attribute))]
                )
            );
        }
    }

    public function checkPassword($attribute, $params = []) {
        extract(array_merge(['passwordAttribute' => 'password', 'message' => 'Invalid password', 'skipEmpty' => true], $params));
        /** @var $passwordAttribute string */
        /** @var $message string */
        /** @var $skipEmpty bool */
        if ($this->$attribute || !$skipEmpty) {
            if (!$this->validatePassword($this->$passwordAttribute)) {
                $this->addError($passwordAttribute, $message);
            }
        }
    }

    public function beforeSave() {
        if ($this->newPassword) {
            $this->hashedPassword = Randomizr::hashPassword($this->newPassword);
        }

        //TODO: setup timezone according to selected country

        return parent::beforeSave();
    }

    public function generateAvatarName($extension) {
        if (!$this->isNewRecord) {
            $this->avatar = $this->id . '-' . Randomizr::generateRandomString(5);
            if ($extension) {
                $this->avatar .= ".$extension";
            }

            $this->update(['avatar']);
        }
    }

    public function vote($id, $type) {
        try {
            $vote = new Vote();
            $vote->User_id = $this->id;
            $vote->Post_id = $id;
            $vote->type = $type;
            $vote->save();
        } catch (\Exception $e) {
        }
    }

    public function canVote($id) {
        if (is_object($id)) {
            $id = $id->id;
        }
        return !Vote::model()->countByAttributes(['User_id' => $this->id, 'Post_id' => $id]);
    }

    public function getVote($post) {
        return Vote::model()->findByAttributes(['User_id' => $this->id, 'Post_id' => $post->id]);
    }

    public function isFavorite($id) {
        if ($id instanceof Post) {
            $id = $id->id;
        }

        return in_array($id, \CHtml::listData($this->favorites, 'id', 'id'));
    }

    public function addFavorite($id) {
        if ($id instanceof Post) {
            $id = $id->id;
        }

        try {
            return $this->dbConnection->createCommand()->insert(self::FAVORITES_RELATION_TABLE, ['User_id' => $this->id, 'Post_id' => $id]);
        } catch (\CDbException $e) {
            if ($this->dbConnection->isDuplicateException($e)) {
                return true;
            }
            throw $e;
        }
    }

    public function deleteFavorite($id) {
        if ($id instanceof Post) {
            $id = $id->id;
        }

        return $this->dbConnection->createCommand()->delete(
            self::FAVORITES_RELATION_TABLE,
            'User_id = :userId AND Post_id = :postId',
            [
            'userId' => $this->id,
            'postId' => $id
            ]
        );
    }

    public function toggleFavorite($id, $value = null){
        if($value === null){
            $value = $this->isFavorite($id);
        }
        return $value ? $this->addFavorite($id) : $this->deleteFavorite($id);
    }

    public function getRestAttributes() {
        return [
            'id' => $this->id,
            'name' => $this->firstName,
            'nickname' => $this->nickname,
            'url' => Yii()->createUrl('/user/view', ['nickname' => $this->nickname]),
            'avatarUrls' => [
                56 => Yii()->avatarStorage->getAvatarUrl($this, 56)
            ],
];
    }
}
