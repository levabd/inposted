<?php
namespace shared\models;

/**
 * This is the model class for table "User".
 *
 * The followings are the available columns in table 'User':
 *
 * @property integer    $id
 * @property string     $email
 * @property string     $hashedPassword
 * @property string     $name
 * @property string     $nickname
 * @property integer    $active
 * @property string     $dateCreated
 * @property string     $dateAccessed
 * @property string     $note
 * @property bool       $verified
 * @property string     $avatar
 * @property string     $avatarUrl
 * @property string     $reputation
 * @property string     $level
 * @property string     $info
 * @property bool       $enabledHints
 * @property bool       $enabledNotifications
 * @property float      $timezone
 * @property int        $birthYear
 * @property string     $gender
 *
 * @property Interest[] $interests
 * @property Post[]     $posts
 * @property Post[]     $favorites
 *
 * @property string     $EID
 * @property string     $firstName
 */
use base\Randomizr;
use base\ActiveRecord;

class User extends ActiveRecord
{
    const INTEREST_RELATION_TABLE = 'Interest_User';
    const FAVORITES_RELATION_TABLE = 'Favorites';

    public $active = 1;

    /**
     * @var \CUploadedFile
     */
    public $avatarUpload;

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

    protected function afterFind() {
        if (intval($this->timezone) == $this->timezone) {
            $this->timezone = intval($this->timezone);
        }
        parent::afterFind();
    }

    protected function beforeValidate() {
        $this->avatarUpload = \CUploadedFile::getInstance($this, 'avatarUpload');
        return parent::beforeValidate();
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
            ['Country_id', 'exist', 'className' => $this->ns('Country'), 'attributeName' => 'id'],
            ['timezone', 'numerical', 'min' => -12, 'max' => 12],
            ['info, enabledHints, enabledNotifications', 'safe'],
            ['homepage', 'url'],

            ['avatarUpload', 'file', 'types' => 'jpg, jpeg, gif, png, bmp', 'allowEmpty' => true],
            ['avatarUpload', 'validImage'],
            ['birthYear', 'numerical', 'integerOnly' => true, 'min' => 1900, 'max' => date('Y'), 'allowEmpty' => true],
            ['gender', 'in', 'range' => ['male', 'female']],
            ['birthYear,gender', 'default', 'setOnEmpty' => true, 'value' => null],
        ];
    }

    public function validImage($attribute) {
        if ($this->$attribute instanceof \CUploadedFile) {
            try {
                new \Imagick($this->$attribute->tempName);
            } catch (\ImagickException $e) {
                $this->addError($attribute, 'Неверный файл изображения');
            }
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return [
            'country'   => [self::BELONGS_TO, $this->ns('Country'), 'Country_id'],
            'interests' => [self::MANY_MANY, $this->ns('Interest'), self::INTEREST_RELATION_TABLE . '(User_id, Interest_id)'],
            'posts'     => [self::HAS_MANY, $this->ns('Post'), 'User_id'],
            'favorites' => [self::MANY_MANY, $this->ns('Post'), self::FAVORITES_RELATION_TABLE . '(User_id, Post_id)'],
            'privateMessages' => [self::HAS_MANY, $this->ns('PrivateMessage'), 'User_id_to', 'order' => 'privateMessages.date DESC'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return [
            'id'                   => 'ID',
            'name'                 => 'Полное Имя',
            'email'                => 'E-Mail',
            'password'             => 'Текущий пароль',
            'active'               => 'Активный',
            'dateCreated'          => 'Дата создания',
            'dateAccessed'         => 'Дата захода',
            'note'                 => 'Запись',
            'Country_id'           => 'Страна',
            'nickname'             => 'Логин',
            'homepage'             => 'Веб-сайт',
            'enabledHints'         => 'Показывать подсказки',
            'enabledNotifications' => 'Уведомлять о всех новых постах',
            'birthYear' => 'Год рождения',
            'gender' => 'Пол',
            'info' => 'О себе'
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

    /**
     * @param string $nickname
     * @param string $condition
     * @param array  $params
     *
     * @return User
     */
    public function findByNickname($nickname, $condition = '', $params = array()) {
        return parent::findByAttributes(compact('nickname'), $condition, $params);
    }

    public function markAccessed() {
        $this->dateAccessed = new \CDbExpression('NOW()');
        return $this->update(['dateAccessed']);
    }

    public function markVerified() {
        $this->verified = true;
        return $this->update(['verified']);
    }

    public function getAvatarUrl($size = null) {
        return Yii()->avatarStorage->getAvatarUrl($this, $size);
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