<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */

namespace site\controllers;
use site\components\RestTrait;
use site\components\WidgetController;
use site\models\PrivateMessage;
use site\models\User;

class PmController extends WidgetController
{
    use RestTrait;

    protected $restActions = ['send'];

    public function actionIndex() {
        $this->author = Yii()->user->model;
        $this->render('index');
    }

    public function actionWidget() {
        $this->renderPartial('widget');
    }

    public function actionSend(array $to, $topic = '', $body) {
        $receiver = User::model()->findByNickname($to['nickname']);
        if ($receiver) {
            $pm = new PrivateMessage();
            $pm->setAttribute('User_id_to', $receiver->id);
            $pm->topic = $topic;
            $pm->body = $body;

            $pm->save();
            $this->renderModels($pm);
        } else {
            throw new \CHttpException(404, "User $to does not exist");
        }
    }

    public function actionUnreadCount() {
        $this->renderJson(
            intval(
                PrivateMessage::model()->countByAttributes(
                    [
                    'User_id_to' => Yii()->user->id,
                    'read'       => false,
                    ]
                )
            )
        );
    }

    public function actionQuery($read = true) {
        $pms = Yii()->user->model->privateMessages;
        if ($read) {
            foreach ($pms as $pm) {
                $pm->read = true;
                $pm->update(['read']);
            }
        }
        $this->renderModels($pms);
    }
}