# Yii2 Highcharts Widget Examples #

## Combination Chart ##

This example is ported from the Highcharts ["Column, Line and Pie" demo](https://www.highcharts.com/demo/combo/grid-light)
with optional exporting module and Grid Light theme enabled.

```php
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;

echo Highcharts::widget([
    'scripts' => [
        'modules/exporting',
        'themes/grid-light',
    ],
    'options' => [
        'title' => [
            'text' => 'Combination chart',
        ],
        'xAxis' => [
            'categories' => ['Apples', 'Oranges', 'Pears', 'Bananas', 'Plums'],
        ],
        'labels' => [
            'items' => [
                [
                    'html' => 'Total fruit consumption',
                    'style' => [
                        'left' => '50px',
                        'top' => '18px',
                        'color' => new JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || "black"'),
                    ],
                ],
            ],
        ],
        'series' => [
            [
                'type' => 'column',
                'name' => 'Jane',
                'data' => [3, 2, 1, 3, 4],
            ],
            [
                'type' => 'column',
                'name' => 'John',
                'data' => [2, 3, 5, 7, 6],
            ],
            [
                'type' => 'column',
                'name' => 'Joe',
                'data' => [4, 3, 3, 9, 0],
            ],
            [
                'type' => 'spline',
                'name' => 'Average',
                'data' => [3, 2.67, 3, 6.33, 3.33],
                'marker' => [
                    'lineWidth' => 2,
                    'lineColor' => new JsExpression('Highcharts.getOptions().colors[3]'),
                    'fillColor' => 'white',
                ],
            ],
            [
                'type' => 'pie',
                'name' => 'Total consumption',
                'data' => [
                    [
                        'name' => 'Jane',
                        'y' => 13,
                        'color' => new JsExpression('Highcharts.getOptions().colors[0]'), // Jane's color
                    ],
                    [
                        'name' => 'John',
                        'y' => 23,
                        'color' => new JsExpression('Highcharts.getOptions().colors[1]'), // John's color
                    ],
                    [
                        'name' => 'Joe',
                        'y' => 19,
                        'color' => new JsExpression('Highcharts.getOptions().colors[2]'), // Joe's color
                    ],
                ],
                'center' => [100, 80],
                'size' => 100,
                'showInLegend' => false,
                'dataLabels' => [
                    'enabled' => false,
                ],
            ],
        ],
    ]
]);
```

## Gantt Chart ##

This example is ported from the Highcharts ["Simple Gantt Chart" example](https://www.highcharts.com/docs/gantt/getting-started-gantt)

```php
use miloschuman\highcharts\GanttChart;
echo GanttChart::widget([
    'options' => [
        'title' => ['text' => 'Simple Gantt Chart'],
        'series' => [
            [
                'name' => 'Project 1',
                'data' => [
                    [
                        'id' => 's',
                        'name' => 'Start prototype',
                        'start' => new JsExpression('Date.UTC(2014, 10, 18)'),
                        'end' => new JsExpression('Date.UTC(2014, 10, 20)'),
                    ],
                    [
                        'id' => 'b',
                        'name' => 'Develop',
                        'start' => new JsExpression('Date.UTC(2014, 10, 20)'),
                        'end' => new JsExpression('Date.UTC(2014, 10, 25)'),
                        'dependency' => 's',
                    ],
                    [
                        'id' => 'a',
                        'name' => 'Run acceptance tests',
                        'start' => new JsExpression('Date.UTC(2014, 10, 23)'),
                        'end' => new JsExpression('Date.UTC(2014, 10, 26)'),
                    ],
                    [
                        'name' => 'Test prototype',
                        'start' => new JsExpression('Date.UTC(2014, 10, 27)'),
                        'end' => new JsExpression('Date.UTC(2014, 10, 29)'),
                        'dependency' => [
                            'a',
                            'b',
                        ],
                    ],
                ],
            ],
        ],
    ],
]);
```