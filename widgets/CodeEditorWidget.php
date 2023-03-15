<?php

namespace app\widgets;

use eluhr\aceeditor\widgets\AceEditor;
use yii\base\InvalidConfigException;
use yii\bootstrap5\Widget;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;

/**
 * Виджет привязки редактора кода AceEditor к полям формы
 * 
 * Свойства:
 *     aceMode - тип редактора (javascript, html, css, php, ...)
 *     aceModel - модель формы
 *     aceAtrribute - аттрибут формы
 * 
 * Пример:
 * CodeEditorWidget::widget([
 *     'id' => 'collapse_beginner',
 *     'items' => [                
 *         'javascript' => [
 *             'aceMode' => 'javascript', 
 *             'aceModel' => $model, 
 *             'aceAttribute' => 'js',
 *         ],
 *         'css' => [
 *             'aceMode' => 'css', 
 *             'aceModel' => $model, 
 *             'aceAttribute' => 'css',
 *         ],
 *     ],
 * ]);
 * 
 * @author toatall
 */
class CodeEditorWidget extends Widget
{
    /**
     * 
     * @var array 
     */
    public $items;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        echo Html::beginTag('div', $this->options) . PHP_EOL;            
            echo Html::beginTag('div', ['class' => 'border shadow-sm']) . PHP_EOL;   
                echo $this->renderItems() . PHP_EOL;            
            echo Html::endTag('div') . PHP_EOL;
        echo Html::endTag('div') . PHP_EOL;
    }

    /**
     * @return string
     */
    private function renderItems()
    {
        $items = [];
        $index = 0;        
        foreach ($this->items as $header => $item) {
            $options = ArrayHelper::getValue($item, 'options', []);
            Html::addCssClass($options, 'card rounded-0 m-0 shadow-0 border-0');
            $items[] = Html::tag('div', $this->renderItem($header, $item, ++$index), $options);
        }

        return implode("\n", $items);
    }

    /**
     * @param string $header
     * @param array $item
     * @param string $index
     * @return string
     */
    public function renderItem($header, $item, $index)
    {        
        $this->checkExistsProperties(['aceMode', 'aceModel', 'aceAttribute'], $item);

        $aceMode = $item['aceMode'];
        $aceModel = $item['aceModel'];
        $aceAttribute = $item['aceAttribute'];
        $aceTheme = $item['aceTheme'] ?? 'chrome';

        $aceEditor = AceEditor::widget([
            'model' => $aceModel,
            'attribute' => $aceAttribute,
            'mode' => $aceMode,
            'theme' => $aceTheme,
        ]);

        $id = $this->options['id'] . '-collapse' . $index;
        $options = ArrayHelper::getValue($item, 'contentOptions', []);
        $options['id'] = $id;
        Html::addCssClass($options, 'collapse');

        $headerToggle = Html::a($header, '#' . $id, [
            'class' => 'text-decoration-none text-black',
            'data-bs-toggle' => 'collapse',
            'aria-controls' => $this->options['id']
        ]) . PHP_EOL;

        $header = Html::tag('strong', $headerToggle);

        $content = Html::tag('div', $aceEditor, ['class' => 'card-body']) . PHP_EOL;       
            
        $group = [];

        $group[] = Html::tag('div', $header, ['class' => 'card-header']);
        $group[] = Html::tag('div', $content, $options);

        return implode(PHP_EOL, $group);
    }

    /**
     * @param array $properties
     * @param string $item
     */
    private function checkExistsProperties($properties, $item)
    {
        foreach($properties as $property) {
            if (!isset($item[$property])) {
                throw new InvalidConfigException("The \"{$property}\" option is required.");
            }
        }
    }

}