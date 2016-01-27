# Yii2 Highstock Widget Examples #

## Area Spline with AJAX Data ##

This example is ported from the Highstock ["Area Spline" demo](http://www.highcharts.com/stock/demo/areaspline).
The `'callback'` option allows you to initialize the chart after an AJAX request or
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

## Compare Series with Multiple AJAX Requests ##

This example is ported from the Highstock ["Compare Multiple Series" demo](http://www.highcharts.com/stock/demo/compare).
The `'callback'` option allows you to initialize the chart after an AJAX request or
other preprocessing.

```php
use miloschuman\highcharts\Highstock;
use yii\web\JsExpression;

$js = <<<MOO
    $(function () {
        var seriesOptions = [],
            seriesCounter = 0,
            names = ['MSFT', 'AAPL', 'GOOG'];

        $.each(names, function(i, name) {

            $.getJSON('http://www.highcharts.com/samples/data/jsonp.php?filename='+ name.toLowerCase() +'-c.json&callback=?',	function(data) {

                seriesOptions[i] = {
                    name: name,
                    data: data
                };

                // As we're loading the data asynchronously, we don't know what order it will arrive. So
                // we keep a counter and create the chart when all the data is loaded.
                seriesCounter++;

                if (seriesCounter == names.length) {
                    createChart(seriesOptions);
                }
            });
        });
    });
MOO;

$this->registerJs($js);

echo Highstock::widget([
    // The highcharts initialization statement will be wrapped in a function
    // named 'createChart' with one parameter: data.
    'callback' => 'createChart',
    'options' => [
        'rangeSelector' => [
            'selected' => 4
        ],
        'yAxis' => [
            'labels' => [
                'formatter' => new JsExpression("function () {
                    return (this.value > 0 ? ' + ' : '') + this.value + '%';
                }")
            ],
            'plotLines' => [[
                'value' => 0,
                'width' => 2,
                'color' => 'silver'
            ]]
        ],
        'plotOptions' => [
            'series' => [
                'compare' => 'percent'
            ]
        ],
        'tooltip' => [
            'pointFormat' => '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.change}%)<br/>',
            'valueDecimals' => 2
        ],
        'series' => new JsExpression('data'), // Here we use the callback parameter, data
    ]
]);
```