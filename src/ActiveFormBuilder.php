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

    /** @var Model|null */
    protected $model;

    /**
     * @param Model|null $model
     * @param array $config
     *
     * @return null|string
     */
    public function renderForm(Model $model = null, array $config)
    {
        $this->model = $model;

        $form = null;
        foreach ($config as $fieldName => $fieldConfig) {
            $form .= $this->renderField($fieldName, $fieldConfig);
        }

        return $form;
    }


    /**
     * @param $attribute
     * @param array $settings
     * @param Model|null $model
     *
     * @return ActiveField
     * @throws InvalidConfigException
     */
    public function renderField($attribute, array $settings = [], $model = null)
    {
        if (!$model) {
            $model = $this->model;
        }
        $type = ArrayHelper::getValue($settings, 'type', static::INPUT_TEXT);

        if (!in_array($type, static::$validInputTypes, true)) {
            throw new InvalidConfigException(
                "Invalid input type '{$type}' configured for the attribute '{$attribute}'.'"
            );
        }

        $fieldOptions = ArrayHelper::getValue($settings, 'fieldOptions', []);
        $label = ArrayHelper::getValue($settings, 'label', null);
        $labelOptions = ArrayHelper::getValue($settings, 'labelOptions', []);
        $hint = ArrayHelper::getValue($settings, 'hint', null);
        $hintOptions = ArrayHelper::getValue($settings, 'hintOptions', []);
        $options = ArrayHelper::getValue($settings, 'options', []);

        $field = $this->field($model, $attribute, $fieldOptions);

        switch ($type) {
            case static::INPUT_HIDDEN:
            case static::INPUT_TEXT:
            case static::INPUT_TEXTAREA:
            case static::INPUT_PASSWORD:
            case static::INPUT_FILE:
                $field = static::genInput($field->$type($options), $label, $labelOptions, $hint, $hintOptions);
                break;
            case static::INPUT_DROPDOWN_LIST:
            case static::INPUT_LIST_BOX:
            case static::INPUT_CHECKBOX_LIST:
            case static::INPUT_RADIO_LIST:
                $items = ArrayHelper::getValue($settings, 'items', []);
                $field = static::genInput($field->$type($items, $options), $label, $labelOptions, $hint, $hintOptions);
                break;
            case static::INPUT_CHECKBOX:
            case static::INPUT_RADIO:
                $enclosedByLabel = ArrayHelper::getValue($settings, 'enclosedByLabel', true);
                $field = static::genInput(
                    $field->$type($options, $enclosedByLabel),
                    $label,
                    $labelOptions,
                    $hint,
                    $hintOptions
                );
                break;
            case static::INPUT_HTML5:
                $html5type = ArrayHelper::getValue($settings, 'html5type', 'text');
                $field = static::genInput(
                    $field->$type($html5type, $options),
                    $label,
                    $labelOptions,
                    $hint,
                    $hintOptions
                );
                break;
            case static::INPUT_WIDGET:
                $widgetClass = ArrayHelper::getValue($settings, 'widgetClass', []);
                if (empty($widgetClass) && !$widgetClass instanceof \yii\widgets\InputWidget) {
                    throw new InvalidConfigException(
                        "A valid 'widgetClass' for '{$attribute}' must be setup and extend from '\\yii\\widgets\\InputWidget'."
                    );
                }
                $field = static::genInput(
                    $field->$type($widgetClass, $options),
                    $label,
                    $labelOptions,
                    $hint,
                    $hintOptions
                );
                break;
            case static::INPUT_RAW:
                $field = static::genInput($field, $label, $labelOptions, $hint, $hintOptions);
                $value = ArrayHelper::getValue($settings, 'value', '');
                if ($value instanceof \Closure) {
                    $value = call_user_func($value);
                } elseif (!is_string($value)) {
                    $value = '';
                }
                $field->parts['{input}'] = $value;
                break;
        }

        return $field;
    }

    /**
     * @param ActiveField $field
     * @param string|null $label
     * @param array $labelOptions
     * @param string|null $hint
     * @param array $hintOptions
     *
     * @return ActiveField|static
     */
    public static function genInput(ActiveField $field, $label = null, $labelOptions = [], $hint = null, $hintOptions = [])
    {
        if ($label !== null) {
            $field->label($label, $labelOptions);
        }
        if ($hint !== null) {
            $field->hint($hint, $hintOptions);
        }

        return $field;
    }
}
