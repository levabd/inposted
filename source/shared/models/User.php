<?php
namespace shared\models;

/**
 * This is the model class for table "User".
 *
 * The followings are the available columns in table 'User':
 *
 * @property integer $id
 * @property string  $email
 * @property string  $password
 * @property string  $name
 * @property integer $active
 * @property string  $dateCreated
 * @property string  $dateAccessed
 * @property string  $note
 * @property bool    $verified
 *
 * @property string  $EID
 */
use base\Randomizr;
use base\ActiveRecord;

class User extends ActiveRecord
{
    public $active = 1;
    public $passwordRepeat;

    /**
     * Returns behaviors configuration.
     *
     * @return Array
     */

    public function behaviors() {
        return array(
            'timestamp' => array(
                'class'           => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'dateCreated',
                'updateAttribute' => null,
            ),
            'encodedid' => array(
                'class' => '\shared\behaviors\EncodedIdBehavior',
            )
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('password, name', 'required', 'on' => 'signup'),
            array('email', 'email', 'on' => 'signup'),
            array('email', 'unique', 'on' => 'signup'),
            array('email, name', 'length', 'max' => 255, 'on' => 'signup'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id'             => 'ID',
            'email'          => 'Email',
            'password'       => 'Password',
            'passwordRepeat' => 'Confirm Password',
            'active'         => 'Active',
            'dateCreated'    => 'Date Created',
            'dateAccessed'   => 'Date Accessed',
            'note'           => 'Note',
        );
    }

    public function beforeSave() {
        switch ($this->scenario) {
            case 'signup':
                $this->password = Randomizr::hashPassword($this->password);
                break;
            case 'profile':
                if ($this->newPassword) {
                    $this->password = Randomizr::hashPassword($this->newPassword);
                }
                break;
        }
        return parent::beforeSave();
    }

    public function validatePassword($password) {
        return $this->password === Randomizr::hashPassword($password, $this->password);
    }

    public function resetPassword($password = null) {
        if (!$password) {
            $password = Randomizr::generateRandomString(8);
        }
        $this->password = Randomizr::hashPassword($password);
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

    public function setAccessed() {
        $this->dateAccessed = new \CDbExpression('NOW()');
        return $this->update('dateAccessed');
    }

    public function setVerified() {
        $this->verified = true;
        return $this->update('verified');
    }
}