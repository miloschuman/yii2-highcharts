# SeriesDataHelper Examples #

## Preparing Stock Data ##

Here is a basic example, which uses SeriesDataHelper to extract and process two sets of data from
the same data provider.

```php
use miloschuman\highcharts\Highstock;
use miloschuman\highcharts\SeriesDataHelper;

$data = [
    ['date' => '2006-05-14T20:00:00-0400', 'open' => 67.37, 'high' => 68.38, 'low' => 67.12, 'close' => 67.79, 'volume' => 18921051],
    ['date' => '2006-05-15T20:00:00-0400', 'open' => 68.1, 'high' => 68.25, 'low' => 64.75, 'close' => 64.98, 'volume' => 33470860],
    ['date' => '2006-05-16T20:00:00-0400', 'open' => 64.7, 'high' => 65.7, 'low' => 64.07, 'close' => 65.26, 'volume' => 26941146],
    ['date' => '2006-05-17T20:00:00-0400', 'open' => 65.68, 'high' => 66.26, 'low' => 63.12, 'close' => 63.18, 'volume' => 23524811],
    ['date' => '2006-05-18T20:00:00-0400', 'open' => 63.26, 'high' => 64.88, 'low' => 62.82, 'close' => 64.51, 'volume' => 35221586],
    ['date' => '2006-05-21T20:00:00-0400', 'open' => 63.87, 'high' => 63.99, 'low' => 62.77, 'close' => 63.38, 'volume' => 25680800],
    ['date' => '2006-05-22T20:00:00-0400', 'open' => 64.86, 'high' => 65.19, 'low' => 63, 'close' => 63.15, 'volume' => 24814061],
    ['date' => '2006-05-23T20:00:00-0400', 'open' => 62.99, 'high' => 63.65, 'low' => 61.56, 'close' => 63.34, 'volume' => 32722949],
    ['date' => '2006-05-24T20:00:00-0400', 'open' => 64.26, 'high' => 64.45, 'low' => 63.29, 'close' => 64.33, 'volume' => 16563319],
    ['date' => '2006-05-25T20:00:00-0400', 'open' => 64.31, 'high' => 64.56, 'low' => 63.14, 'close' => 63.55, 'volume' => 15464811],
];

$dataProvider = new \yii\data\ArrayDataProvider(['allModels' => $data]);

echo Highstock::widget([
    'options' => [
        'title' => ['text' => 'Basic Example'],
        'yAxis' => [
            ['title' => ['text' => 'OHLC'], 'height' => '60%'],
            ['title' => ['text' => 'Volume'], 'top' => '65%', 'height' => '35%', 'offset' => 0],
        ],
        'series' => [
            [
                'type' => 'candlestick',
                'name' => 'OHLC',
                'data' => new SeriesDataHelper($dataProvider, ['date:datetime', 'open', 'high', 'low', 'close']),
            ],
            [
                'type' => 'column',
                'name' => 'Volume',
                'data' => new SeriesDataHelper($dataProvider, ['date:datetime', 'volume:int']),
                'yAxis' => 1,
            ],
        ]
    ]
]);
```

### Using Numerically Indexed Data ###

Your source data needn't have traditional column names to take advantage of SeriesDataHelper. Just
use the numerical index in place of the string key.

```php
use miloschuman\highcharts\Highstock;
use miloschuman\highcharts\SeriesDataHelper;

// this is the same data as above but without string keys and using a Unix timestamp for the date
$data = [
    [1147651200,67.37,68.38,67.12,67.79,18921051],
    [1147737600,68.10,68.25,64.75,64.98,33470860],
    [1147824000,64.70,65.70,64.07,65.26,26941146],
    [1147910400,65.68,66.26,63.12,63.18,23524811],
    [1147996800,63.26,64.88,62.82,64.51,35221586],
    [1148256000,63.87,63.99,62.77,63.38,25680800],
    [1148342400,64.86,65.19,63.00,63.15,24814061],
    [1148428800,62.99,63.65,61.56,63.34,32722949],
    [1148515200,64.26,64.45,63.29,64.33,16563319],
    [1148601600,64.31,64.56,63.14,63.55,15464811],
];

echo Highstock::widget([
    'options' => [
        'title' => ['text' => 'Numerically Indexed'],
        'yAxis' => [
            ['title' => ['text' => 'OHLC'], 'height' => '60%'],
            ['title' => ['text' => 'Volume'], 'top' => '65%', 'height' => '35%', 'offset' => 0],
        ],
        'series' => [
            [
                'type' => 'candlestick',
                'name' => 'OHLC',
                // just like before, only now the columns are referenced by array offset
                'data' => new SeriesDataHelper($data, ['0:timestamp', 1, 2, 3, 4]),
            ],
            [
                'type' => 'column',
                'name' => 'Volume',
                'data' => new SeriesDataHelper($data, ['0:timestamp', '5:int']),
                'yAxis' => 1,
            ],
        ]
    ]
]);
```


### Custom Formatters ###

In addition to the built-in formatters, SeriesDataHelper allows you to use your own custom
formatters. These can be any [PHP callable](https://php.net/manual/en/language.types.callable.php),
including PHP built-in functions, user defined functions, class methods, and closures.

```php
use miloschuman\highcharts\Highstock;
use miloschuman\highcharts\SeriesDataHelper;

// back to the original data from the first example
$data = [
    ['date' => '2006-05-14T20:00:00-0400', 'open' => 67.37, 'high' => 68.38, 'low' => 67.12, 'close' => 67.79, 'volume' => 18921051],
    ['date' => '2006-05-15T20:00:00-0400', 'open' => 68.1, 'high' => 68.25, 'low' => 64.75, 'close' => 64.98, 'volume' => 33470860],
    ['date' => '2006-05-16T20:00:00-0400', 'open' => 64.7, 'high' => 65.7, 'low' => 64.07, 'close' => 65.26, 'volume' => 26941146],
    ['date' => '2006-05-17T20:00:00-0400', 'open' => 65.68, 'high' => 66.26, 'low' => 63.12, 'close' => 63.18, 'volume' => 23524811],
    ['date' => '2006-05-18T20:00:00-0400', 'open' => 63.26, 'high' => 64.88, 'low' => 62.82, 'close' => 64.51, 'volume' => 35221586],
    ['date' => '2006-05-21T20:00:00-0400', 'open' => 63.87, 'high' => 63.99, 'low' => 62.77, 'close' => 63.38, 'volume' => 25680800],
    ['date' => '2006-05-22T20:00:00-0400', 'open' => 64.86, 'high' => 65.19, 'low' => 63, 'close' => 63.15, 'volume' => 24814061],
    ['date' => '2006-05-23T20:00:00-0400', 'open' => 62.99, 'high' => 63.65, 'low' => 61.56, 'close' => 63.34, 'volume' => 32722949],
    ['date' => '2006-05-24T20:00:00-0400', 'open' => 64.26, 'high' => 64.45, 'low' => 63.29, 'close' => 64.33, 'volume' => 16563319],
    ['date' => '2006-05-25T20:00:00-0400', 'open' => 64.31, 'high' => 64.56, 'low' => 63.14, 'close' => 63.55, 'volume' => 15464811],
];

function myUnnecessaryFloorCallback($val)
{
    if (true) {
        return floor($val);
    } else {
        die('I am Bender. Please insert girder.');
    }
}

echo Highstock::widget([
    'options' => [
        'title' => ['text' => 'Oy vey! This chart again?!'],
        'yAxis' => [
            ['title' => ['text' => 'OHLC'], 'height' => '60%'],
            ['title' => ['text' => 'Volume'], 'top' => '65%', 'height' => '35%', 'offset' => 0],
        ],
         'series' => [
            [
                'type' => 'candlestick',
                'name' => 'OHLC',
                'data' => new SeriesDataHelper($data, [
                    'date:datetime', // nothing new here
                    // the following 4 columns' formatters are functionally equivalent
                    'open:floor', // PHP built-in function floor()
                    ['high', 'floor'], // ditto
                    ['low', function($val) { return floor($val); }], // wrapped in a closure
                    'close:myUnnecessaryFloorCallback', // fantastic
                ]),
            ],
            [
                'type' => 'column',
                'name' => 'Volume',
                'data' => new SeriesDataHelper($data, [
                    ['date', 'datetime'], // still nothing new here
                    ['volume', 'myUnnecessaryFloorCallback'], // mmmmm
                ]),
                'yAxis' => 1,
            ],
        ]
    ]
]);
```
