<?php
namespace miloschumanunit\highcharts;

use miloschuman\highcharts\SeriesDataHelper;
use yii\data\ArrayDataProvider;

/**
 * SeriesDataHelperTest.php
 */
class SeriesDataHelperTest extends BaseTestCase
{
    /**
     * test basic data processing using current setup
     */
    public function testBasicProcessing()
    {
        $data = [
            [
                'date_measured' => '2016-03-01 03:00:00',
                'open'          => 3.14,
            ],
            [
                'date_measured' => '2016-03-02 03:00:00',
                'open'          => 4.14,
            ]
        ];
        $columns      = [
            [ 'date_measured', 'datetime' ],
            'open:int',
        ];

        $dataProvider = $this->setupDataProvider($data);

        $helper  = new SeriesDataHelper( $dataProvider, $columns );
        $results = $helper->process();
        $this->assertEquals( strtotime('2016-03-01 03:00:00') * 1000, $results[0][0] );
        $this->assertEquals( 3, $results[0][1] );
        $this->assertEquals( strtotime('2016-03-02 03:00:00') * 1000, $results[1][0] );
        $this->assertEquals( 4, $results[1][1] );
    }

    private function setupDataProvider($data)
    {
        return new ArrayDataProvider( [
            'allModels' => $data,
            'sort' => false,
            'pagination' => false,
        ] );
    }
}