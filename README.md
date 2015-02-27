Yii2 Form Builder
=================
Small and easy form builder. You can store form configuration in form model.

[![Latest Stable Version](https://poser.pugx.org/metalguardian/yii2-form-builder/v/stable.svg)](https://packagist.org/packages/metalguardian/yii2-form-builder) 
[![Total Downloads](https://poser.pugx.org/metalguardian/yii2-form-builder/downloads.svg)](https://packagist.org/packages/metalguardian/yii2-form-builder) 
[![Latest Unstable Version](https://poser.pugx.org/metalguardian/yii2-form-builder/v/unstable.svg)](https://packagist.org/packages/metalguardian/yii2-form-builder) 
[![License](https://poser.pugx.org/metalguardian/yii2-form-builder/license.svg)](https://packagist.org/packages/metalguardian/yii2-form-builder)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist metalguardian/yii2-form-builder "*"
```

or add

```
"metalguardian/yii2-form-builder": "*"
```

to the require section of your `composer.json` file.


Usage
-----

First of all you need write form config (you can store it in model class)

    <?php
    
    namespace app\models;
    
    use metalguardian\formBuilder\ActiveFormBuilder;
    
    /**
     */
    class Example extends \yii\db\ActiveRecord
    {
        .....
        /**
         * @return array
         */
        public function getFormConfig()
        {
            return [
                'label' => [
                    'type' => ActiveFormBuilder::INPUT_TEXT,
                ],
                'content' => [
                    'type' => ActiveFormBuilder::INPUT_TEXTAREA,
                    'hint' => 'hint about field',
                ],
                'type' => [
                    'type' => ActiveFormBuilder::INPUT_DROPDOWN_LIST,
                    'items' => [1 => 'One', 2 => 'Two'],
                    'options' => [
                        'prompt' => 'select',
                    ],
                ],
                'published' => [
                    'type' => ActiveFormBuilder::INPUT_CHECKBOX,
                ],
                'redactor' => [
                    'type' => ActiveFormBuilder::INPUT_WIDGET,
                    'widgetClass' => \vova07\imperavi\Widget::className(),
                ],
                'raw_data' => [ // need to define attribute `raw_data` in model 
                    'type' => ActiveFormBuilder::INPUT_RAW,
                    'value' => 'raw html data',
                ],
            ];
        }
    }

Now in form view you can write something like this:

    .....
    <?php $form = \metalguardian\formBuilder\ActiveFormBuilder::begin(); ?>
    
    
    <?= $form->renderForm($model, $model->getFormConfig()) ?>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>
    
    <?php \metalguardian\formBuilder\ActiveFormBuilder::end(); ?>
    .....

Advanced Usage
--------------

You can define configuration of different elements in model

    <?php
    
    namespace app\helpers;
    
    use metalguardian\formBuilder\ActiveFormBuilder;
    
    /**
     */
    class Helper
    {
        .....
        /**
         * @return array
         */
        public static function getLabelConfig()
        {
            return [
                'type' => ActiveFormBuilder::INPUT_TEXT,
            ];
        }
        
        /**
         * @return array
         */
        public static function getContentConfig()
        {
            return [
                'type' => ActiveFormBuilder::INPUT_TEXTAREA,
            ];
        }
    }

Now you can use different models in one form

    .....
    <?php $form = \metalguardian\formBuilder\ActiveFormBuilder::begin(); ?>
    
    <?= $form->renderField('label', \app\helpers\Helper::getLabelConfig(), $model1); ?>
    <?= $form->renderField('content', \app\helpers\Helper::getContentConfig(), $model1); ?>
    
    <?= $form->renderField('label', \app\helpers\Helper::getLabelConfig(), $model2); ?>
    <?= $form->renderField('content', \app\helpers\Helper::getContentConfig(), $model2); ?>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>
    
    <?php \metalguardian\formBuilder\ActiveFormBuilder::end(); ?>
    .....
