# Yii2 Highstock Widget Examples #

## Area Spline with AJAX Data ##

This example is ported from the Highstock ["Area Spline" demo](http://www.highcharts.com/stock/demo/areaspline).
The `'callback'` option allows you to initialize the chart after an AJAX call or
other preprocessing.

```php
use miloschuman\highcharts\Highstock;
use yii\web\JsExpression;

$this->registerJs('$.getJSON("//www.highcharts.com/samples/data/jsonp.php?filename=aapl-c.json&callback=?", myCallbackFunction);');

echo Highstock::widget([
    // The highcharts initialization statement will be wrapped in a function
    // named 'mycallbackFunction' with one parameter: data.
    'callback' => 'myCallbackFunction',
    'options' => [
        'rangeSelector' => [
            'inputEnabled' => new JsExpression('$("#container").width() > 480'),
            'selected' => 1
        ],
        'title' => [
            'text' => 'AAPL Stock Price'
        ],
        'series' => [
            [
                'name' => 'AAPL Stock Price',
                'data' => new JsExpression('data'), // Here we use the callback parameter, data
                'type' => 'areaspline',
                'threshold' => null,
                'tooltip' => [
                    'valueDecimals' => 2
                ],
                'fillColor' => [
                    'linearGradient' => [
                        'x1' => 0,
                        'y1' => 0,
                        'x2' => 0,
                        'y2' => 1
                    ],
                    'stops' => [
                        [0, new JsExpression('Highcharts.getOptions().colors[0]')],
                        [1, new JsExpression('Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get("rgba")')]
                    ]
                ]
            ]
        ]
    ]
]);
```
