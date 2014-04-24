<?php

/**
 * HighchartsAsset class file.
 *
 * @author Milo Schuman <miloschuman@gmail.com>
 * @link https://github.com/miloschuman/yii2-highcharts-widget/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @version 4.0.1
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

        // make sure that at either the highcharts or highstock base file is
        // included. If both are included, make sure that highstock comes first.
        $hasHighcharts = in_array("highcharts.$ext", $this->js);
        $hasHighstock = in_array("highstock.$ext", $this->js);
        if (!$hasHighcharts && !$hasHighstock) {
            array_unshift($this->js, "highcharts.$ext");
        } else {
            if ($hasHighcharts) {
                array_unshift($this->js, "highcharts.$ext");
            }
            if ($hasHighstock) {
                array_unshift($this->js, "highstock.$ext");
            }
        }

        return $this;
    }
}
