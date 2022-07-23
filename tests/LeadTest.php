<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use WorldNewsGroup\Marketo\Environment;
use WorldNewsGroup\Marketo\Model\Lead;
use WorldNewsGroup\Marketo\Exception\ErrorException;

final class LeadTest extends TestCase {

    public static function setUpBeforeClass(): void {
        Dotenv\Dotenv::createImmutable('.')->load();
        Environment::configure($_ENV['CLIENT_ID'], $_ENV['CLIENT_SECRET'], $_ENV['MUNCHKIN_ID']);
    }

    /**
     * @covers \Lead::getLeadById
     */
    public function testGetLeadByIdInvalidId() {
        $this->expectException(ErrorException::class);
        Lead::getLeadById('test@foo.bar');
    }

    /**
     * @depends testGetLeadByIdInvalidId
     * @covers \Lead::getLeadById
     */
    public function testGetLeadByIdValidId() {
        $result = Lead::getLeadById($_ENV['TEST_LEAD']);

        $this->assertIsObject($result);
    }

    /**
     * @covers \LeadField
     */
    public function testGetLeadParitions() {
        $result = Lead::getLeadPartitions();

        $this->assertInstanceOf("\WorldNewsGroup\Marketo\Result", $result);

        $partitions = $result->partitions();

        $this->assertNotCount(0, $partitions);
    }    

    /**
     * @covers \LeadField
     */
    public function testGetLeadFields() {
        $result = Lead::getLeadFields(2);

        $fields = $result->fields();

        $this->assertCount(2, $fields, "Asked for 2 fields, returned " . count($fields) . ".  This may not be a problem if no fields exist in database");

        $this->assertTrue($result->getMoreResult() );

        if( $result->getMoreResult() ) {
            $result = Lead::getLeadFields(2, $result->getNextPageToken());

            $this->assertCount(2, $fields, "Asked for 2 fields, returned " . count($fields) . ".  This may not be a problem if no fields exist in database");
        }
    }    

    /**
     * @covers \Lead
     */
    public function testCreateLead() {
        $leads = [
            [
                'firstName'=>'Tigger',
                'lastName'=>'Pooh',
                'email'=>str_shuffle('abcdefghijkl123') . '@test.org'
            ],
            [
                'firstName'=>'Christopher',
                'lastName'=>'Robin',
                'email'=>str_shuffle('mnopqrstuv567') . '@test.org'            ]
        ];

        $result = Lead::syncLeads($leads);

        $leads = [];

        foreach($result->getResults() as $actionTaken) {
            $this->assertEquals('created', $actionTaken['status']);

            if( $actionTaken['status'] == 'created' ) {
                $leads[] = [
                    'id'=>$actionTaken['id']
                ];
            }
        }

        $result = Lead::deleteLeads($leads);

        foreach($result->getResults() as $actionTaken) {
            $this->assertEquals('deleted', $actionTaken['status']);
        }
    }

}