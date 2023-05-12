<?php
namespace tests\unit\models\declarecampaignusn;

use app\models\DeclareCampaignUsnChart;
use app\tests\fixtures\DeclareCampaignUsnFixture;

class DeclareCampaignUsnChartTest extends \Codeception\Test\Unit
{       

    /**
     * @var \UnitTester
     */
    public $tester;

    /**
     * {@inheritdoc}
     */
    public function _fixtures()
    {        
        return [            
            'declares' => DeclareCampaignUsnFixture::class,
        ];
    }

    public function testGenerateDataToChart()
    {
        $data = DeclareCampaignUsnChart::generateDataToChart('8601');
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
        $this->assertArrayHasKey('labels', $data);
        $this->assertArrayHasKey('series', $data);

        $countLabels = count($data['labels']);
        foreach($data['series'] as $serie) {
            $this->assertArrayHasKey('name', $serie);
            $this->assertArrayHasKey('data', $serie);
            $this->assertEquals($countLabels, count($serie['data']));
            foreach($serie['data'] as $value) {
                $this->assertIsInt((int) $value);
            }
        }
        
    }



}