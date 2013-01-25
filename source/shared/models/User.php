<?php
namespace shared\models;

/**
 * This is the model class for table "User".
 *
 * The followings are the available columns in table 'User':
 *
 * @property integer $id
 * @property string  $email
 * @property string  $hashedPassword
 * @property string  $name
 * @property string  $nickname
 * @property integer $active
 * @property string  $dateCreated
 * @property string  $dateAccessed
 * @property string  $note
 * @property bool    $verified
 * @property string  $avatar
 * @property string  $avatarUrl
 * @property string  $reputation
 * @property string  $level
 *
 * @property string  $EID
 * @property string  $firstName
 */
use base\Randomizr;
use base\ActiveRecord;

class User extends ActiveRecord
{
    public $active = 1;

    /**
     * Returns behaviors configuration.
     *
     * @return Array
     */

    public function behaviors() {
        return array(
            'timestamp' => [
                'class'           => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'dateCreated',
                'updateAttribute' => null,
            ],
            'encodedid' => ['class' => '\shared\behaviors\EncodedIdBehavior'],

        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return [
            ['nickname, email', 'required'],
            [
                'nickname', 'in', 'not' => true,
                'range'                 => ['restore', 'register', 'profile', 'settings'],
                'message'               => '{attribute} not allowed'
            ],

            ['email, nickname', 'unique'],
            ['email', 'email'],
            ['email, name, nickname', 'length', 'max' => 255],
            ['homepage', 'length', 'max' => 1024],
            ['Country_id', 'exist', 'className' => $this->ns('Country')]
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return [
            'country' => [self::BELONGS_TO, $this->ns('Country'), 'Country_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return [
            'id'             => 'ID',
            'email'          => 'Email',
            'password'       => 'Password',
            'passwordRepeat' => 'Confirm Password',
            'active'         => 'Active',
            'dateCreated'    => 'Date Created',
            'dateAccessed'   => 'Date Accessed',
            'note'           => 'Note',
            'Country_id'     => 'Country'
        ];
    }


    public function validatePassword($password) {
        return $this->hashedPassword === Randomizr::hashPassword($password, $this->hashedPassword);
    }

    public function resetPassword($password = null) {
        if (!$password) {
            $password = Randomizr::generateRandomString(8);
        }
        $this->hashedPassword = Randomizr::hashPassword($password);
        return $password;
    }

    /**
     * @param string $email
     * @param string $condition
     * @param array  $params
     *
     * @return User
     */
    public function findByEmail($email, $condition = '', $params = array()) {
        return parent::findByAttributes(compact('email'), $condition, $params);
    }

    public function markAccessed() {
        $this->dateAccessed = new \CDbExpression('NOW()');
        return $this->update(['dateAccessed']);
    }

    public function markVerified() {
        $this->verified = true;
        return $this->update(['verified']);
    }

    public function getAvatarFile() {
        $storage = Yii()->params->itemAt('avatars-storage');
        return path($storage, $this->formatIdPath(), $this->avatar);
    }

    public function getAvatarUrl() {
        $config = Yii()->params->itemAt('avatars-baseUrl');
        list($appId, $baseUrl) = explode(':', $config);
        return Yii()->urlManager->getBaseUrl($appId) . "/$baseUrl/{$this->formatIdPath()}/$this->avatar";
    }

    protected function formatIdPath() {
        return number_format($this->id, 0, null, '/');
    }

    protected function getFirstName() {
        extract($this->parseName());
        /** @var $first string */
        return $first ? : $this->nickname;
    }

    protected function getLastName() {
        extract($this->parseName());
        /** @var $last string */
        return $last;
    }

    protected function parseName() {
        $parts = array_filter(explode(' ', $this->name));

        return ['first' => array_path($parts, 0), 'last' => array_path($parts, 1)];

    }
}