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
use yii\widgets\InputWidget;

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
     * @param string $attribute
     * @param array $settings
     * @param Model $model
     *
     * @return ActiveField
     * @throws InvalidConfigException
     */
    public function renderField(Model $model, $attribute, array $settings = [])
    {
        $fieldOptions = ArrayHelper::getValue($settings, 'fieldOptions', []);
        $field = $this->field($model, $attribute, $fieldOptions);

        if (($label = ArrayHelper::getValue($settings, 'label')) !== null) {
            $field->label($label, ArrayHelper::getValue($settings, 'labelOptions', []));
        }
        if (($hint = ArrayHelper::getValue($settings, 'hint')) !== null) {
            $field->hint($hint, ArrayHelper::getValue($settings, 'hintOptions', []));
        }

        $type = ArrayHelper::getValue($settings, 'type', static::INPUT_TEXT);
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
            case static::INPUT_TEXT:
            case static::INPUT_TEXTAREA:
            case static::INPUT_PASSWORD:
            case static::INPUT_FILE:
                $field->$type($options);
                break;

            case static::INPUT_DROPDOWN_LIST:
            case static::INPUT_LIST_BOX:
            case static::INPUT_CHECKBOX_LIST:
            case static::INPUT_RADIO_LIST:
                $items = ArrayHelper::getValue($settings, 'items', []);
                $field->$type($items, $options);
                break;

            case static::INPUT_CHECKBOX:
            case static::INPUT_RADIO:
                $enclosedByLabel = ArrayHelper::getValue($settings, 'enclosedByLabel', true);
                $field->$type($options, $enclosedByLabel);
                break;

            case static::INPUT_HTML5:
                $html5type = ArrayHelper::getValue($settings, 'html5type', 'text');
                $field->$type($html5type, $options);
                break;

            case static::INPUT_WIDGET:
                $widgetClass = $this->getWidgetClass($settings);
                $field->$type($widgetClass, $options);
                break;

            case static::INPUT_RAW:
                $field->parts['{input}'] = $this->getValue($settings);
                break;

            default:
                throw new InvalidConfigException("Invalid input type '{$type}' configured for the attribute.");
        }
    }

    /**
     * @param array $settings
     * @return mixed
     * @throws InvalidConfigException
     */
    protected function getWidgetClass(array $settings)
    {
        $widgetClass = ArrayHelper::getValue($settings, 'widgetClass');
        if (empty($widgetClass) && !$widgetClass instanceof InputWidget) {
            throw new InvalidConfigException(
                "A valid 'widgetClass' must be setup and extend from '\\yii\\widgets\\InputWidget'."
            );
        }
        return $widgetClass;
    }

    /**
     * @param array $settings
     * @return mixed|string
     */
    protected function getValue(array $settings)
    {
        $value = ArrayHelper::getValue($settings, 'value', '');
        if (is_callable($value)) {
            return call_user_func($value);
        } elseif (!is_string($value)) {
            return '';
        }
        return $value;
    }
}
