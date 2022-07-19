<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use WorldNewsGroup\Marketo\Environment;
use WorldNewsGroup\Marketo\Model\Lead;
use WorldNewsGroup\Marketo\Model\ProgramMember;
use WorldNewsGroup\Marketo\Exception\ErrorException;

final class LeadCreateTest extends TestCase {

    public static function setUpBeforeClass(): void {
        Dotenv\Dotenv::createImmutable('.')->load();
        Environment::configure($_ENV['CLIENT_ID'], $_ENV['CLIENT_SECRET'], $_ENV['MUNCHKIN_ID']);
    }

    /**
     * @covers \Lead
     */
    public function testCreateLead() {
        $leads = [
            [
                'firstName'=>'Ben',
                'lastName'=>'Menking',
                'email'=>'test99676@wng.org'
            ],
            [
                'firstName'=>'Patrick',
                'lastName'=>'Menking',
                'email'=>'test99525@wng.org'
            ]
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