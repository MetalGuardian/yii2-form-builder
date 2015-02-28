<?php
/**
 * Author: metalguardian
 */

namespace metalguardian\formBuilder;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

/**
 * Class ActiveFormBuilder
 * @package metalguardian\formBuilder
 */
class ActiveFormBuilder extends ActiveForm
{
    const INPUT_HIDDEN = 'hiddenInput';
    const INPUT_TEXT = 'textInput';
    const INPUT_TEXTAREA = 'textarea';
    const INPUT_PASSWORD = 'passwordInput';
    const INPUT_DROPDOWN_LIST = 'dropdownList';
    const INPUT_LIST_BOX = 'listBox';
    const INPUT_CHECKBOX = 'checkbox';
    const INPUT_RADIO = 'radio';
    const INPUT_CHECKBOX_LIST = 'checkboxList';
    const INPUT_RADIO_LIST = 'radioList';
    const INPUT_FILE = 'fileInput';
    const INPUT_HTML5 = 'input';
    const INPUT_WIDGET = 'widget';
    const INPUT_RAW = 'raw';

    /**
     * Valid input types
     * @var array
     */
    protected static $validInputTypes = [
        self::INPUT_HIDDEN,
        self::INPUT_TEXT,
        self::INPUT_TEXTAREA,
        self::INPUT_PASSWORD,
        self::INPUT_DROPDOWN_LIST,
        self::INPUT_LIST_BOX,
        self::INPUT_CHECKBOX,
        self::INPUT_RADIO,
        self::INPUT_CHECKBOX_LIST,
        self::INPUT_RADIO_LIST,
        self::INPUT_FILE,
        self::INPUT_HTML5,
        self::INPUT_WIDGET,
        self::INPUT_RAW
    ];

    /**
     * @param Model $model
     * @param array $config
     *
     * @return null|string
     */
    public function renderForm(Model $model, array $config)
    {
        $form = null;
        foreach ($config as $attribute => $options) {
            $form .= $this->renderField($model, $attribute, $options);
        }

        return $form;
    }


    /**
     * @param $attribute
     * @param array $settings
     * @param Model $model
     *
     * @return ActiveField
     * @throws InvalidConfigException
     */
    public function renderField($model, $attribute, array $settings = [])
    {
        $type = $this->getType($settings);

        $fieldOptions = ArrayHelper::getValue($settings, 'fieldOptions', []);
        $label = ArrayHelper::getValue($settings, 'label', null);
        $labelOptions = ArrayHelper::getValue($settings, 'labelOptions', []);
        $hint = ArrayHelper::getValue($settings, 'hint', null);
        $hintOptions = ArrayHelper::getValue($settings, 'hintOptions', []);

        $field = $this->field($model, $attribute, $fieldOptions);

        if ($label !== null) {
            $field->label($label, $labelOptions);
        }
        if ($hint !== null) {
            $field->hint($hint, $hintOptions);
        }

        $this->prepareField($field, $type, $settings);

        return $field;
    }

    /**
     * @param ActiveField $field
     * @param $type
     * @param array $settings
     * @throws InvalidConfigException
     */
    protected function prepareField($field, $type, array $settings)
    {
        $options = ArrayHelper::getValue($settings, 'options', []);
        switch ($type) {
            case static::INPUT_HIDDEN:
                $field->hiddenInput($options);
                break;
            case static::INPUT_TEXT:
                $field->textInput($options);
                break;
            case static::INPUT_TEXTAREA:
                $field->textarea($options);
                break;
            case static::INPUT_PASSWORD:
                $field->passwordInput($options);
                break;
            case static::INPUT_FILE:
                $field->fileInput($options);
                break;
            case static::INPUT_DROPDOWN_LIST:
                $items = ArrayHelper::getValue($settings, 'items', []);
                $field->dropDownList($items, $options);
                break;
            case static::INPUT_LIST_BOX:
                $items = ArrayHelper::getValue($settings, 'items', []);
                $field->listBox($items, $options);
                break;
            case static::INPUT_CHECKBOX_LIST:
                $items = ArrayHelper::getValue($settings, 'items', []);
                $field->checkboxList($items, $options);
                break;
            case static::INPUT_RADIO_LIST:
                $items = ArrayHelper::getValue($settings, 'items', []);
                $field->radioList($items, $options);
                break;
            case static::INPUT_CHECKBOX:
                $enclosedByLabel = ArrayHelper::getValue($settings, 'enclosedByLabel', true);
                $field->checkbox($options, $enclosedByLabel);
                break;
            case static::INPUT_RADIO:
                $enclosedByLabel = ArrayHelper::getValue($settings, 'enclosedByLabel', true);
                $field->radio($options, $enclosedByLabel);
                break;
            case static::INPUT_HTML5:
                $html5type = ArrayHelper::getValue($settings, 'html5type', 'text');
                $field->input($html5type, $options);
                break;
            case static::INPUT_WIDGET:
                $widgetClass = $this->getWidgetClass($settings);
                $field->widget($widgetClass, $options);
                break;
            case static::INPUT_RAW:
                $value = $this->getValue($settings);
                $field->parts['{input}'] = $value;
                break;
        }
    }

    /**
     * @param array $settings
     * @return mixed
     * @throws InvalidConfigException
     */
    protected function getWidgetClass(array $settings)
    {
        $widgetClass = ArrayHelper::getValue($settings, 'widgetClass', []);
        if (empty($widgetClass) && !$widgetClass instanceof \yii\widgets\InputWidget) {
            throw new InvalidConfigException(
                "A valid 'widgetClass' must be setup and extend from '\\yii\\widgets\\InputWidget'."
            );
        }
        return $widgetClass;
    }

    /**
     * @param array $settings
     * @return mixed
     * @throws InvalidConfigException
     */
    protected function getType(array $settings)
    {
        $type = ArrayHelper::getValue($settings, 'type', static::INPUT_TEXT);

        if (!in_array($type, static::$validInputTypes, true)) {
            throw new InvalidConfigException(
                "Invalid input type '{$type}' configured for the attribute."
            );
        }
        return $type;
    }

    /**
     * @param array $settings
     * @return mixed|string
     */
    protected function getValue(array $settings)
    {
        $value = ArrayHelper::getValue($settings, 'value', '');
        if ($value instanceof \Closure) {
            $value = call_user_func($value);
            return $value;
        } elseif (!is_string($value)) {
            $value = '';
            return $value;
        }
        return $value;
    }
}
