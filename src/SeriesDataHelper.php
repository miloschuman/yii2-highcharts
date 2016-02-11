<?php

/**
 * SeriesDataHelper class file.
 *
 * @author Milo Schuman <miloschuman@gmail.com>
 * @link https://github.com/miloschuman/yii2-highcharts/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @version 4.2.3
 */

namespace miloschuman\highcharts;

use JsonSerializable;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\data\ArrayDataProvider;
use yii\data\BaseDataProvider;
use yii\helpers\ArrayHelper;

/**
 * SeriesDataHelper makes it easier to get data from your database/data provider into your chart.
 *
 * It includes a set of built-in formatters to handle common preprocessing tasks (like converting
 * datetime strings to JavaScript timestamps) and provides an extensible column configuration, which
 * allows you to add custom parser/formatter handlers.
 *
 * Basic usage:
 * ```php
 * use miloschuman\highcharts\Highstock;
 * use miloschuman\highcharts\SeriesDataHelper;
 *
 * Highstock::widget([
 *     'options' => [
 *         'series' => [
 *             [
 *                 'type' => 'candlestick',
 *                 'name' => 'Stock',
 *                 'data' => new SeriesDataHelper($dataProvider, ['date:datetime', 'open', 'high', 'low', 'close']),
 *             ],
 *             [
 *                 'type' => 'column',
 *                 'name' => 'Volume',
 *                 'data' => new SeriesDataHelper($dataProvider, ['date:datetime', 'volume:int']),
 *             ],
 *         ]
 *     ]
 * ]);
 * ```
 *
 * @property-write BaseDataProvider|array $data
 * @property-write array $columns
 */
class SeriesDataHelper extends Component implements JsonSerializable
{

    /**
     * @var array column configuration
     */
    protected $columns;

    /**
     * @var BaseDataProvider|array the underlying data source
     */
    protected $data;


    /**
     * Constructor
     * 
     * @param BaseDataProvider|array $data the underlying data source
     * @param array $columns column configuration
     * @param array $config for future use
     * @see setColumns()
     * @see setData()
     */
    public function __construct($data, $columns, $config = [])
    {
        parent::__construct($config);

        $this->setData($data);
        $this->setColumns($columns);
    }


    /**
     * Sets the underlying data source.
     * 
     * @param BaseDataProvider|array $data the data source
     * @throws InvalidParamException
     */
    public function setData($data)
    {
        if ($data instanceof BaseDataProvider) {
            $this->data = $data;
        } elseif (is_array($data)) {
            $this->data = new ArrayDataProvider([
                'allModels' => $data,
            ]);
        } else {
            throw new InvalidParamException('Data must be an array or extend BaseDataProvider');
        }
    }


    /**
     * Sets the column configuration.
     *
     * Each element can be either an array `['column', 'format']` or a shortcut string
     * `'column:format'`.
     *
     * Column can be a string representing the attribute, field, or key from the
     * source data. It can even be an integer if the source data uses numeric keys.
     *
     * Format can be one of the built-in formatters ('datetime', 'float', 'int', 'raw', 'string', or
     * 'timestamp') or a callable that is used to prepare each data value. If format is omitted, the
     * raw value will be passed to the chart.
     *
     * Example showing different ways to specify a column:
     *
     * ```php
     * [
     *     ['date_measured', 'datetime'],
     *     'open',
     *     'high:float',
     *     ['low', 'float'],
     *     ['close', function($value) {
     *         return ceil($value);
     *     }]
     * ]
     * ```
     *
     * @param array $columns
     * @throws InvalidParamException
     */
    public function setColumns($columns)
    {
        if (!is_array($columns) || !count($columns)) {
            throw new InvalidParamException('Columns must be an array with at least one element.');
        }

        $this->columns = $columns;
    }


    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this->process();
    }


    /**
     * Processes the source data and returns the result.
     *
     * @return array the processed data
     */
    public function process()
    {
        if (empty($this->data)) {
            throw new InvalidConfigException('Missing required "data" property.');
        }

        $this->normalizeColumns();

        // return simple array for single-column configs
        if (count($this->columns) === 1) {
            $column = $this->columns[0];
            $data = ArrayHelper::getColumn($this->data->models, $column[0]);
            return array_map($column[1], $data);
        }

        // use two-dimensional array for multi-column configs
        $data = [];
        foreach ($this->data->models as $model) {
            $row = [];
            foreach ($this->columns as $column) {
                $row[] = call_user_func($column[1], $model[$column[0]]);
            }

            $data[] = $row;
        }

        return $data;
    }


    /**
     * Prepares the [[$columns]] for use by [[process()]].
     */
    protected function normalizeColumns()
    {
        $formatters = $this->getFormatters();

        foreach ($this->columns as $index => $column) {

            // convert shorthand string and int configs to array
            if (is_string($column)) {
                $column = explode(':', $column);
            } elseif (is_int($column)) {
                $column = [$column];
            }

            // default to 'raw' formatter if none is specified
            if (!isset($column[1])) {
                $column[1] = 'raw';
            }

            // assign built-in formatters
            if (!is_callable($column[1])) {
                if (array_key_exists($column[1], $formatters)) {
                    $column[1] = $formatters[$column[1]];
                } else {
                    throw new InvalidConfigException("Invalid formatter for column: {$column[0]}.");
                }
            }

            $this->columns[$index] = $column;
        }
    }


    /**
     * Built-in formatters, which can be used in the [[$columns]] configuration.
     *
     * @return callable[]
     */
    protected function getFormatters()
    {
        return [
            'datetime' => function ($val) { return (float) strtotime($val) * 1000; },
            'int' => 'intval',
            'float' => 'floatval',
            'raw' => function ($val) { return $val; },
            'string' => 'strval',
            'timestamp' => function ($val) { return (float) $val * 1000; },
        ];
    }
}
