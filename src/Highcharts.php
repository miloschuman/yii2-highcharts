<?php

/**
 * Highcharts class file.
 *
 * @author Milo Schuman <miloschuman@gmail.com>
 * @link https://github.com/miloschuman/yii2-highcharts/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @version 4.2.3
 */

namespace miloschuman\highcharts;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/**
 * Highcharts encapsulates the {@link http://www.highcharts.com/ Highcharts}
 * charting library's Chart object.
 *
 * To use this widget, you can insert the following code in a view:
 * ```php
 * echo \miloschuman\highcharts\Highcharts::widget([
 *     'options' => [
 *         'title' => ['text' => 'Fruit Consumption'],
 *         'xAxis' => [
 *             'categories' => ['Apples', 'Bananas', 'Oranges']
 *         ],
 *         'yAxis' => [
 *             'title' => ['text' => 'Fruit eaten']
 *         ],
 *         'series' => [
 *             ['name' => 'Jane', 'data' => [1, 0, 4]],
 *             ['name' => 'John', 'data' => [5, 7, 3]]
 *         ]
 *     ]
 * ]);
 * ```
 *
 * By configuring the {@link $options} property, you may specify the options
 * that need to be passed to the Highcharts JavaScript object. Please refer to
 * the demo gallery and documentation on the {@link https://www.highcharts.com/
 * Highcharts website} for possible options.
 *
 * Note: You do not need to specify the <code>chart->renderTo</code> option as
 * is shown in many of the examples on the Highcharts website. This value is
 * automatically populated with the id of the widget's container element. If you
 * wish to use a different container, feel free to specify a custom value.
 */
class Highcharts extends Widget
{

    protected $constr = 'Chart';
    protected $baseScript = 'highcharts';
    public $options = [];
    public $htmlOptions = [];
    public $setupOptions = [];
    public $scripts = [];
    public $callback = false;

    /**
     * Renders the widget.
     */
    public function run()
    {
        // determine the ID of the container element
        if (isset($this->htmlOptions['id'])) {
            $this->id = $this->htmlOptions['id'];
        } else {
            $this->id = $this->htmlOptions['id'] = $this->getId();
        }

        // render the container element
        echo Html::tag('div', '', $this->htmlOptions);

        // check if options parameter is a json string
        if (is_string($this->options)) {
            $this->options = Json::decode($this->options);
        }

        // merge options with default values
        $defaultOptions = ['chart' => ['renderTo' => $this->id]];
        $this->options = ArrayHelper::merge($defaultOptions, $this->options);
        array_unshift($this->scripts, $this->baseScript);

        $this->registerAssets();

        parent::run();
    }

    /**
     * Registers required assets and the executing code block with the view
     */
    protected function registerAssets()
    {
        // register the necessary script files
        HighchartsAsset::register($this->view)->withScripts($this->scripts);

        // prepare and register JavaScript code block
        $jsOptions = Json::encode($this->options);
        $setupOptions = Json::encode($this->setupOptions);
        $js = "Highcharts.setOptions($setupOptions); new Highcharts.{$this->constr}($jsOptions);";
        $key = __CLASS__ . '#' . $this->id;
        if (is_string($this->callback)) {
            $js = "function {$this->callback}(data) {{$js}}";
        }

        $this->view->registerJs($js, View::POS_READY, $key);
    }

}
