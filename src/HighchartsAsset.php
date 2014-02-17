<?php

/**
 * HighchartsAsset class file.
 * 
 * @author Milo Schuman <miloschuman@gmail.com>
 * @link https://github.com/miloschuman/yii2-highcharts-widget/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @version 3.0.9
 */

namespace miloschuman\highcharts;

use yii\web\AssetBundle;

/**
 * Asset bundle for Highcharts and Highstock widget.
 */
class HighchartsAsset extends AssetBundle
{

	public $sourcePath = '@vendor/miloschuman/yii2-highcharts-widget/assets';
	public $depends = [
		'yii\web\JqueryAsset',
	];

	/**
	 * inheritdoc
	 */
	public function init()
	{
		// Make sure highstock comes first. Otherwise, we get errors.
		$ext = YII_DEBUG ? 'src.js' : 'js';
		$hasHighcharts = in_array("highcharts.$ext", $this->js);
		$hasHighstock = in_array("highstock.$ext", $this->js);
		
		if(!hasHighcharts && !$hasHighstock) {
			array_unshift($this->js, "highcharts.$ext");
		} else {
			if($hasHighcharts) {
				array_unshift($this->js, "highcharts.$ext");
			}
			if($hasHighstock) {
				array_unshift($this->js, "highstock.$ext");
			}
		}
		
//		if(in_array("highcharts.$ext", $this->js)) {
//			array_unshift($this->js, "highcharts.$ext");
//		}
		parent::init();
	}

	/**
	 * Registers additional JavaScript files required by the widget. 
	 * @param array $scripts list of additional JavaScript files to register.
	 */
	public function withScripts($scripts = ['highcharts'])
	{
		foreach ($scripts as $script) {
			$js = YII_DEBUG ? "$script.src.js" : "$script.js";
			if (!in_array($js, $this->js)) {
				$this->js[] = $js;
			}
		}
	}

}
