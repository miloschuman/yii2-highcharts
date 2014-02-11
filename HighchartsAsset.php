<?php

/**
 * @author Milo Schuman <miloschuman@gmail.com>
 * @link https://github.com/miloschuman/yii2-highcharts-widget/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace miloschuman\highcharts;

use yii\web\AssetBundle;

/**
 * Asset bundle for Highcharts widget
 */
class HighchartsAsset extends AssetBundle
{

	public $sourcePath = '@vendor/miloschuman/yii-highcharts-widget/assets';
//	public $css = [
//		'css/bootstrap.css',
//	];
	public $depends = [
		'yii\web\JqueryAsset',
	];
	
	public $scripts = [];

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		$this->sourcePath = __DIR__ . '/assets';
		
		// register additional scripts
		foreach ($this->scripts as $script) {
			if (YII_DEBUG && file_exists(realpath("{$this->sourcePath}/$script.src.js"))) {
				$this->cs[] = "$script.src.js";
			} else {
				$this->cs[] = "$script.js";
			}
		}
//		$this->css = YII_DEBUG ? ['css/activeform.css'] : ['css/activeform.min.css'];
		parent::init();
	}

}
