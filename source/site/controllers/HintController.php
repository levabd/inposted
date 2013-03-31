<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */

namespace site\controllers;


use shared\models\Hint;
use site\components\Controller;
use site\components\RestTrait;

class HintController extends Controller
{
    use RestTrait;

    public function actionTemplate() {
        $this->renderPartial('template');
    }

    public function actionQuery() {
        $this->renderModels(Hint::model()->findAll());
    }
}