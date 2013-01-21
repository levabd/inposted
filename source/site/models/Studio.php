<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dima
 * Date: 12/16/11
 * Time: 8:46 PM
 */

namespace site\models;

class Studio extends \shared\models\Studio
{
    public $filterCompany;

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new \CDbCriteria;

        $criteria->compare('account.company', $this->filterCompany, true);

        return new \CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getApiServer(){
        return Yii()->createUrl('studioapi:api/index');
    }
}
