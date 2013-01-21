<?php
/**
 * @author: Yurko Fedoriv <yurko.fedoriv@gmail.com>
 */

namespace base;
use Yii, CHttpException, CHtml;

/**
 * Controller to handle external exceptions.
 *
 * @author yurko
 */
class ErrorController extends \CController
{
    public $defaultAction = 'error';

    public function actionError() {
        $errorHandler = Yii::app()->errorHandler;

        if (($error = $errorHandler->error)) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            }
            else {
                /** @noinspection PhpUndefinedFieldInspection CErrorHandler::getVersionInfo() is protected getter, but it is reachable as undocumented property */
                $error['version'] = $errorHandler->versionInfo;
                $error['time'] = time();
                $error['admin'] = $errorHandler->adminInfo;

                if (($this->getViewFile($view = $error['code'])) || ($this->getViewFile($view = 'default'))) {
                    $this->render($view, $error);
                }
                else { //fallback to Yii's internal view
                    $this->renderYii($error);
                }
            }
        }
        else {
            throw new CHttpException(404, 'Page Not Found');
        }
    }

    /**
     * Determines which internal view file should be used and renders it
     * Compilation of protected and though unreachable from here
     * CErrorHandler::getViewFile and CErrorHandler::getViewFileInternal
     *
     * @param array $error error info
     */
    protected function renderYii($error) {
        /** @var $app \CWebApplication */
        $app = Yii::app();

        if ($app->getTheme()) {
            $viewPaths[$app()->getTheme()->getSystemViewPath()] = null;
        }
        $viewPaths[$app->getSystemViewPath()] = null;
        $viewPaths[YII_PATH . DIRECTORY_SEPARATOR . 'views'] = 'en_us';

        foreach ($viewPaths as $viewPath => $srcLanguage)
        {
            if (
                is_file(
                    $viewFile = $app->findLocalizedFile(
                        $viewPath . DIRECTORY_SEPARATOR . "error{$error['code']}.php", $srcLanguage
                    )
                )
                || is_file(
                    $viewFile = $app->findLocalizedFile(
                        $viewPath . DIRECTORY_SEPARATOR . 'error.php', $srcLanguage
                    )
                )
            ) {
                $this->renderFile($viewFile, array('data' => $error));
                break;
            }
        }
    }
}
