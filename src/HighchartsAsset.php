<?php

/**
 * HighchartsAsset class file.
 *
 * @author Milo Schuman <miloschuman@gmail.com>
 * @link https://github.com/miloschuman/yii2-highcharts/
 * @license https://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace miloschuman\highcharts;

use yii\web\AssetBundle;

/**
 * Asset bundle for Highcharts, Highstock, and Highmaps widgets.
 */
class HighchartsAsset extends AssetBundle
{
    public $sourcePath = '@bower/highcharts';
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

        // make sure the highcharts base file is included first
        array_unshift($this->js, "highcharts.$ext");

        return $this;
    }
}
