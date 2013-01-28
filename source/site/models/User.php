<?php
namespace site\models;
use base\Randomizr;

class User extends \shared\models\User
{
    public $password;
    public $passwordRepeat;
    public $avatarUpload;

    const PASSWORD_MAX_SAME_CHARS = 3;

    public function rules() {
        return array_merge(
            parent::rules(),
            [
            ['password', 'required', 'on' => 'signup-1'],
            ['password', 'length', 'min' => 6],
            ['password', 'compare', 'operator' => '!=', 'compareAttribute' => 'email'],
            ['password', 'compare', 'operator' => '!=', 'compareAttribute' => 'nickname'],
            ['password', 'passwordValidator'],
            ['avatarUpload', 'file', 'types' => 'jpg, gif, png', 'allowEmpty' => true],
            ]
        );
    }

    public function passwordValidator($attribute) {
        if (!$this->$attribute) {
            return true;
        }

        $password = $this->$attribute;
        $encoding = Yii()->charset;
        $length = mb_strlen($password, $encoding);

        $error = null;

        if (mb_strtolower($password, $encoding) == $password || mb_strtoupper($password, $encoding) == $password) {
            $error = '{attribute} should contain letters in different case';
        } else {
            $charCounts = [];
            for ($i = 0; $i < $length; $i++) {
                $char = mb_substr($password, $i, 1, $encoding);
                if (!isset($charCounts[$char])) {
                    $charCounts[$char] = 1;
                } else {
                    $charCounts[$char]++;
                    if ($charCounts[$char] > self::PASSWORD_MAX_SAME_CHARS) {
                        $error = '{attribute} can not contain more than {num} same characters.';
                        break;
                    }
                }
            }
        }

        if ($error) {
            $this->addError(
                $attribute,
                \Yii::t(
                    'inposted', $error,
                    [
                    '{attribute}' => \Yii::t('inposted', $this->getAttributeLabel($attribute)),
                    '{num}'       => self::PASSWORD_MAX_SAME_CHARS,
                    ]
                )
            );
            return false;
        }

        return true;
    }

    public function beforeSave() {
        switch ($this->scenario) {
            case 'signup-1':
                $this->hashedPassword = Randomizr::hashPassword($this->password);
                break;
            case 'profile':
                if ($this->newPassword) {
                    $this->password = Randomizr::hashPassword($this->newPassword);
                }
                break;
        }
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

    public function addInterest(Interest $interest) {
        $result = true;
        $transaction = $this->dbConnection->beginTransaction();
        if (!$this->hasInterest($interest)) {
            $result = $this->dbConnection->createCommand(
                "INSERT INTO `Interest_User`
                SET `User_id` = :userId, `Interest_id` = :interestId"
            )->execute(['userId' => $this->id, 'interestId' => $interest->id]);
        }
        $transaction->commit();
        return $result;
    }

    public function hasInterest(Interest $interest) {
        return (bool)$this->dbConnection->createCommand(
            "SELECT COUNT(*)
            FROM `Interest_User`
            WHERE `User_id` = :userId AND `Interest_id` = :interestId"
        )->queryScalar(['userId' => $this->id, 'interestId' => $interest->id]);
    }

    public function removeInterest($interest) {
        return $this->dbConnection->createCommand(
            "DELETE FROM `Interest_User`
            WHERE `User_id` = :userId AND `Interest_id` = :interestId"
        )->query(['userId' => $this->id, 'interestId' => $interest->id]);
    }
}
