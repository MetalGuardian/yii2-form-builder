<?php
/**
 * MainTest.php
 * @author Revin Roman http://phptime.ru
 */

namespace unit\builder;

use metalguardian\formBuilder\ActiveFormBuilder;
use unit\TestCase;
use yii\base\DynamicModel;
use yii\widgets\InputWidget;

/**
 * Class MainTest
 */
class MainTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $config = $this->loadConfig();
        $this->mockApplication($config);
    }

    public function testRenderFieldTextType()
    {
        $settings = [
            'type' => ActiveFormBuilder::INPUT_TEXT,
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>
<input type="text" id="dynamicmodel-name" class="form-control" name="DynamicModel[name]">

<div class="help-block"></div>
</div>
EOF
        , $this->renderField($settings));
    }

    public function testRenderFieldTextAreaType()
    {
        $settings = [
            'type' => ActiveFormBuilder::INPUT_TEXTAREA,
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>
<textarea id="dynamicmodel-name" class="form-control" name="DynamicModel[name]"></textarea>

<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));
    }

    public function testRenderFieldPasswordType()
    {
        $settings = [
            'type' => ActiveFormBuilder::INPUT_PASSWORD,
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>
<input type="password" id="dynamicmodel-name" class="form-control" name="DynamicModel[name]">

<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));
    }

    public function testRenderFieldFileType()
    {
        $settings = [
            'type' => ActiveFormBuilder::INPUT_FILE,
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>
<input type="hidden" name="DynamicModel[name]" value=""><input type="file" id="dynamicmodel-name" name="DynamicModel[name]">

<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));
    }

    public function testRenderFieldHiddenType()
    {
        $settings = [
            'type' => ActiveFormBuilder::INPUT_HIDDEN,
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>
<input type="hidden" id="dynamicmodel-name" class="form-control" name="DynamicModel[name]">

<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));
    }

    public function testRenderFieldDropDownListType()
    {
        $settings = [
            'type' => ActiveFormBuilder::INPUT_DROPDOWN_LIST,
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>
<select id="dynamicmodel-name" class="form-control" name="DynamicModel[name]">

</select>

<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));

        $settings = [
            'type' => ActiveFormBuilder::INPUT_DROPDOWN_LIST,
            'items' => [1 => 'One', 2 => 'Two'],
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>
<select id="dynamicmodel-name" class="form-control" name="DynamicModel[name]">
<option value="1">One</option>
<option value="2">Two</option>
</select>

<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));
    }

    public function testRenderFieldListBoxType()
    {
        $settings = [
            'type' => ActiveFormBuilder::INPUT_LIST_BOX,
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>
<input type="hidden" name="DynamicModel[name]" value=""><select id="dynamicmodel-name" class="form-control" name="DynamicModel[name]" size="4">

</select>

<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));

        $settings = [
            'type' => ActiveFormBuilder::INPUT_LIST_BOX,
            'items' => [1 => 'One', 2 => 'Two'],
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>
<input type="hidden" name="DynamicModel[name]" value=""><select id="dynamicmodel-name" class="form-control" name="DynamicModel[name]" size="4">
<option value="1">One</option>
<option value="2">Two</option>
</select>

<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));
    }

    public function testRenderFieldCheckBoxListType()
    {
        $settings = [
            'type' => ActiveFormBuilder::INPUT_CHECKBOX_LIST,
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>
<input type="hidden" name="DynamicModel[name]" value=""><div id="dynamicmodel-name"></div>

<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));

        $settings = [
            'type' => ActiveFormBuilder::INPUT_CHECKBOX_LIST,
            'items' => [1 => 'One', 2 => 'Two'],
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>
<input type="hidden" name="DynamicModel[name]" value=""><div id="dynamicmodel-name"><label><input type="checkbox" name="DynamicModel[name][]" value="1"> One</label>
<label><input type="checkbox" name="DynamicModel[name][]" value="2"> Two</label></div>

<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));
    }

    public function testRenderFieldRadioListType()
    {
        $settings = [
            'type' => ActiveFormBuilder::INPUT_RADIO_LIST,
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>
<input type="hidden" name="DynamicModel[name]" value=""><div id="dynamicmodel-name"></div>

<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));

        $settings = [
            'type' => ActiveFormBuilder::INPUT_RADIO_LIST,
            'items' => [1 => 'One', 2 => 'Two'],
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>
<input type="hidden" name="DynamicModel[name]" value=""><div id="dynamicmodel-name"><label><input type="radio" name="DynamicModel[name]" value="1"> One</label>
<label><input type="radio" name="DynamicModel[name]" value="2"> Two</label></div>

<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));
    }

    public function testRenderFieldCheckboxType()
    {
        $settings = [
            'type' => ActiveFormBuilder::INPUT_CHECKBOX,
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">

<input type="hidden" name="DynamicModel[name]" value="0"><label><input type="checkbox" id="dynamicmodel-name" name="DynamicModel[name]" value="1"> Name</label>

<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));
    }

    public function testRenderFieldRadioType()
    {
        $settings = [
            'type' => ActiveFormBuilder::INPUT_RADIO,
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">

<input type="hidden" name="DynamicModel[name]" value="0"><label><input type="radio" id="dynamicmodel-name" name="DynamicModel[name]" value="1"> Name</label>

<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));
    }

    public function testRenderFieldHTML5Type()
    {
        $settings = [
            'type' => ActiveFormBuilder::INPUT_HTML5,
            'html5type' => 'email',
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>
<input type="email" id="dynamicmodel-name" class="form-control" name="DynamicModel[name]">

<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));
    }

    public function testRenderFieldWidgetType()
    {
        $settings = [
            'type' => ActiveFormBuilder::INPUT_WIDGET,
            'widgetClass' => InputWidget::className(),
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>


<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));
    }

    public function testRenderFieldWidgetTypeException()
    {
        $this->setExpectedException('yii\base\InvalidConfigException', "A valid 'widgetClass' must be setup and extend from '\yii\widgets\InputWidget'.");
        $settings = [
            'type' => ActiveFormBuilder::INPUT_WIDGET,
        ];
        $this->renderField($settings);
    }

    public function testRenderFieldRawType()
    {
        $settings = [
            'type' => ActiveFormBuilder::INPUT_RAW,
            'value' => '<div>Content</div>',
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>
<div>Content</div>

<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));

        $settings = [
            'type' => ActiveFormBuilder::INPUT_RAW,
            'value' => function () {
                return '<div>Content2</div>';
            },
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>
<div>Content2</div>

<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));

        $settings = [
            'type' => ActiveFormBuilder::INPUT_RAW,
            'value' => new \stdClass(),
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>


<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));

        $settings = [
            'type' => ActiveFormBuilder::INPUT_RAW,
            'value' => 1000,
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>


<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));
    }

    public function testRenderFieldUnexpectedTypeException()
    {
        $type = 'OtherType';
        $this->setExpectedException('yii\base\InvalidConfigException', "Invalid input type '{$type}' configured for the attribute.");
        $settings = [
            'type' => $type,
        ];
        $this->renderField($settings);
    }

    public function renderField($settings)
    {
        $model = new DynamicModel(['name']);
        ob_start();
        $form = new ActiveFormBuilder(['action' => '/something']);
        ob_end_clean();

        return $form->renderField($model, 'name', $settings);
    }

    public function testRenderForm()
    {
        $model = new DynamicModel(['name']);
        ob_start();
        $form = new ActiveFormBuilder(['action' => '/something']);
        ob_end_clean();

        $config = [
            'name' => [
                'type' => ActiveFormBuilder::INPUT_TEXT,
            ]
        ];

        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>
<input type="text" id="dynamicmodel-name" class="form-control" name="DynamicModel[name]">

<div class="help-block"></div>
</div>
EOF
            , $form->renderForm($model, $config));
    }

    public function testRenderLabel()
    {
        $settings = [
            'type' => ActiveFormBuilder::INPUT_TEXT,
            'label' => 'Custom label',
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Custom label</label>
<input type="text" id="dynamicmodel-name" class="form-control" name="DynamicModel[name]">

<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));
    }

    public function testRenderHint()
    {
        $settings = [
            'type' => ActiveFormBuilder::INPUT_TEXT,
            'hint' => 'Field hint',
        ];
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<label class="control-label" for="dynamicmodel-name">Name</label>
<input type="text" id="dynamicmodel-name" class="form-control" name="DynamicModel[name]">
<div class="hint-block">Field hint</div>
<div class="help-block"></div>
</div>
EOF
            , $this->renderField($settings));
    }

    // Yii2 default Form tests
    public function assertEqualsWithoutLE($expected, $actual)
    {
        $expected = str_replace("\r\n", "\n", $expected);
        $actual = str_replace("\r\n", "\n", $actual);

        $this->assertEquals($expected, $actual);
    }

    public function testBooleanAttributes()
    {
        $o = ['template' => '{input}'];

        $model = new DynamicModel(['name']);
        ob_start();
        $form = new ActiveFormBuilder(['action' => '/something']);
        ob_end_clean();

        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<input type="email" id="dynamicmodel-name" class="form-control" name="DynamicModel[name]" required>
</div>
EOF
            , (string) $form->field($model, 'name', $o)->input('email', ['required' => true]));

        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<input type="email" id="dynamicmodel-name" class="form-control" name="DynamicModel[name]">
</div>
EOF
            , (string) $form->field($model, 'name', $o)->input('email', ['required' => false]));


        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-name">
<input type="email" id="dynamicmodel-name" class="form-control" name="DynamicModel[name]" required="test">
</div>
EOF
            , (string) $form->field($model, 'name', $o)->input('email', ['required' => 'test']));

    }

    public function testIssue5356()
    {
        $o = ['template' => '{input}'];

        $model = new DynamicModel(['categories']);
        $model->categories = 1;
        ob_start();
        $form = new ActiveFormBuilder(['action' => '/something']);
        ob_end_clean();

        // https://github.com/yiisoft/yii2/issues/5356
        $this->assertEqualsWithoutLE(<<<EOF
<div class="form-group field-dynamicmodel-categories">
<input type="hidden" name="DynamicModel[categories]" value=""><select id="dynamicmodel-categories" class="form-control" name="DynamicModel[categories][]" multiple size="4">
<option value="0">apple</option>
<option value="1" selected>banana</option>
<option value="2">avocado</option>
</select>
</div>
EOF
            , (string) $form->field($model, 'categories', $o)->listBox(['apple', 'banana', 'avocado'], ['multiple' => true]));
    }
}
