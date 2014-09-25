<?php

/**
 * HighchartsAsset class file.
 *
 * @author Milo Schuman <miloschuman@gmail.com>
 * @link https://github.com/miloschuman/yii2-highcharts/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @version 4.0.4
 */

namespace miloschuman\highcharts;

use yii\web\AssetBundle;

/**
 * Asset bundle for Highcharts and Highstock widget.
 */
class HighchartsAsset extends AssetBundle
{

    public $sourcePath = '@vendor/miloschuman/yii2-highcharts-widget/src/assets';
    public $depends = ['yii\web\JqueryAsset'];

    /**
     * Registers additional JavaScript files required by the widget.
     *
     * @param array $scripts list of additional JavaScript files to register.
     * @return $this
     */
    public function withScripts($scripts = ['highcharts'])
    {
        // use unminified files when in debug mode
        $ext = YII_DEBUG ? 'src.js' : 'js';

        // add files
        foreach ($scripts as $script) {
            $this->js[] = "$script.$ext";
        }

        // make sure that either highcharts or highstock base file is included.
        array_unshift($this->js, "highcharts.$ext");
        $hasHighstock = in_array("highstock.$ext", $this->js);
        if ($hasHighstock) {
            array_unshift($this->js, "highstock.$ext");
            // remove highcharts if highstock is used on page
            $this->js = array_diff($this->js, ["highcharts.$ext"]);
        }

        return $this;
    }
}
