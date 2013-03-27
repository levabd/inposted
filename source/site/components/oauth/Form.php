<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */

namespace site\components\oauth;


class Form extends \CForm
{
    /**
     * Loads the submitted data into the associated model(s) to the form.
     * This method will go through all models associated with this form and its sub-forms
     * and massively assign the submitted data to the models.
     *
     * @see submitted
     */
    public function loadData() {
        $model = $this->getModel(false);
        if ($model !== null) {
            $class = method_exists($model, 'formName') ? $model->formName() : get_class($model);
            if (strcasecmp($this->getRoot()->method, 'get')) {
                if (isset($_POST[$class])) {
                    $model->setAttributes($_POST[$class]);
                }
            } elseif (isset($_GET[$class])) {
                $model->setAttributes($_GET[$class]);
            }
        }
        foreach ($this->getElements() as $element) {
            if ($element instanceof self) {
                $element->loadData();
            }
        }
    }
}