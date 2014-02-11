<?php

/**
 * Highcharts class file.
 *
 * @author Milo Schuman <miloschuman@gmail.com>
 * @link https://github.com/miloschuman/yii-highcharts/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @version 3.0.5
 */

namespace miloschuman\highcharts;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\web\JsExpression;

/**
 * Highcharts encapsulates the {@link http://www.highcharts.com/ Highcharts}
 * charting library's Chart object.
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * use miloschuman\highcharts\Highcharts;
 * 
 * Highcharts::widget([
 *    'options' => [
 *       'title' => ['text' => 'Fruit Consumption'],
 *       'xAxis' => [
 *          'categories' => ['Apples', 'Bananas', 'Oranges']
 *       ],
 *       'yAxis' => [
 *          'title' => ['text' => 'Fruit eaten']
 *       ],
 *       'series' => [
 *          ['name' => 'Jane', 'data' => [1, 0, 4]],
 *          ['name' => 'John', 'data' => [5, 7, 3]]
 *       ]
 *    ]
 * ]);
 * </pre>
 *
 * By configuring the {@link $options} property, you may specify the options
 * that need to be passed to the Highcharts JavaScript object. Please refer to
 * the demo gallery and documentation on the {@link http://www.highcharts.com/
 * Highcharts website} for possible options.
 *
 * Alternatively, you can use a valid JSON string in place of an associative
 * array to specify options:
 *
 * <pre>
 * use miloschuman\highcharts\Highcharts;
 * 
 *  Highcharts::widget([
 *    'options'=>'{
 *       "title": { "text": "Fruit Consumption" },
 *       "xAxis": {
 *          "categories": ["Apples", "Bananas", "Oranges"]
 *       },
 *       "yAxis": {
 *          "title": { "text": "Fruit eaten" }
 *       },
 *       "series": [
 *          { "name": "Jane", "data": [1, 0, 4] },
 *          { "name": "John", "data": [5, 7,3] }
 *       ]
 *    }'
 * ]);
 * </pre>
 *
 * Note: You must provide a valid JSON string (e.g. double quotes) when using
 * the second option. You can quickly validate your JSON string online using
 * {@link http://jsonlint.com/ JSONLint}.
 *
 * Note: You do not need to specify the <code>chart->renderTo</code> option as
 * is shown in many of the examples on the Highcharts website. This value is
 * automatically populated with the id of the widget's container element. If you
 * wish to use a different container, feel free to specify a custom value.
 */
class Highcharts extends \yii\base\Widget
{

	protected $constr = 'Chart';
	protected $baseScript = 'highcharts';
	
	public $options = array();
	public $htmlOptions = array();
	public $setupOptions = array();
	public $scripts = array();


	/**
	 * Renders the widget.
	 */
	public function run()
	{
		// determine the ID of the container element
		if (isset($this->htmlOptions['id'])) {
			$id = $this->htmlOptions['id'];
		} else {
			$id = $this->htmlOptions['id'] = $this->getId();
		}

		// render the container element
		echo Html::tag('div', '', $this->htmlOptions);

		// check if options parameter is a json string
		if (is_string($this->options)) {
			$this->options = Json::decode($this->options);
		}

		// merge options with default values
		$defaultOptions = ['chart' => ['renderTo' => $id]];
		$this->options = ArrayHelper::merge($defaultOptions, $this->options);
		array_unshift($this->scripts, $this->baseScript);

		// prepare script
		$jsOptions = Json::encode($this->options);
		$setupOptions = Json::encode($this->setupOptions);
		$js = "Highcharts.setOptions($setupOptions); var chart = new Highcharts.{$this->constr}($jsOptions);";
		$key = __CLASS__ . '#' . $id;
		
		HighchartsAsset::register($this->view);
		$this->view->registerJs($js, View::POS_LOAD, $key);
		
		parent::run();
	}
	
	/**
	 * Registers the needed assets
	 */
	public function registerAssets()
	{
		
	}


	/**
	 * Publishes and registers the necessary script files.
	 *
	 * @param string the id of the script to be inserted into the page
	 * @param string the embedded script to be inserted into the page
	 */
	protected function registerScripts($id, $embeddedScript)
	{
		$basePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
		$baseUrl = Yii::app()->getAssetManager()->publish($basePath, false, 1, YII_DEBUG);

		$cs = Yii::app()->clientScript;
		$cs->registerCoreScript('jquery');

		// register additional scripts
		foreach ($this->scripts as $script) {
			if (YII_DEBUG && file_exists(realpath("$basePath/$script.src.js"))) {
				$cs->registerScriptFile("$baseUrl/$script.src.js");
			} else {
				$cs->registerScriptFile("$baseUrl/$script.js");
			}
		}

		$cs->registerScript($id, $embeddedScript, CClientScript::POS_LOAD);
	}

}
