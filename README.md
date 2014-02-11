yii2-highcharts-widget
======================

Highcharts widget for Yii 2 Framework


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist miloschuman/yii2-highcharts-widget "*"
```

or add

```
"miloschuman/yii2-highcharts-widget": "*"
```

to the require section of your `composer.json` file.


Usage
-----

To use this widget, you may insert the following code into a view file:
```php
use miloschuman\highcharts\Highcharts;

echo Highcharts::widget([
   'options' => [
      'title' => ['text' => 'Fruit Consumption'],
      'xAxis' => [
         'categories' => ['Apples', 'Bananas', 'Oranges']
      ],
      'yAxis' => [
         'title' => ['text' => 'Fruit eaten']
      ],
      'series' => [
         ['name' => 'Jane', 'data' => [1, 0, 4]],
         ['name' => 'John', 'data' => [5, 7, 3]]
      ]
   ]
]);
```
By configuring the `options` property, you may specify the options that need to be passed to the Highcharts JavaScript object. Please refer to the demo gallery and documentation on the [Highcharts website](http://www.highcharts.com/) for possible options.

Alternatively, you can use a valid JSON string in place of an associative array to specify options:
```php
Highcharts::widget([
   'options'=>'{
      "title": { "text": "Fruit Consumption" },
      "xAxis": {
         "categories": ["Apples", "Bananas", "Oranges"]
      },
      "yAxis": {
         "title": { "text": "Fruit eaten" }
      },
      "series": [
         { "name": "Jane", "data": [1, 0, 4] },
         { "name": "John", "data": [5, 7,3] }
      ]
   }'
]);
```

*Note:* You must provide a *valid* JSON string (e.g. double quotes) when using the second option. You can quickly validate your JSON string online using [JSONLint](http://jsonlint.com/).


Tips
----

* If you need to use JavaScript in any of your configuration options (e.g. inline functions), use the `js:` prefix. For instance:

  ```php
  ...
  'tooltip' => array(
       'formatter' => 'js:function(){ return this.series.name; }'
  ),
  ...
  ```
* Highcharts by default displays a small credits label in the lower right corner of the chart. This can be removed using the following top-level option.

  ```php
  ...
  'credits' => array('enabled' => false),
  ...
  ```
* Since version 3.0.2, all adapters, modules, themes, and supplementary chart types must be enabled through the top-level 'scripts' option.

  ```php
  ...
  'scripts' => array(
       'highcharts-more',   // enables supplementary chart types (gauge, arearange, columnrange, etc.)
       'modules/exporting', // adds Exporting button/menu to chart
       'themes/grid'        // applies global 'grid' theme to all charts
  ),
  ...
  ```
  Previous versions relied on auto-detection magic, but that became less reliable as Highcharts evolved. The new method
  more accurately follows the native process of including/excluding additional script files and gives the user some finer-grain control.
  For a list of available scripts, see the contents of `protected/extensions/highcharts/assets/`.
