<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use WorldNewsGroup\Marketo\Environment;
use WorldNewsGroup\Marketo\Model\Lead;
use WorldNewsGroup\Marketo\Exception\ErrorException;

final class LeadFieldTest extends TestCase {

    public static function setUpBeforeClass(): void {
        Dotenv\Dotenv::createImmutable('.')->load();
        Environment::configure($_ENV['CLIENT_ID'], $_ENV['CLIENT_SECRET'], $_ENV['MUNCHKIN_ID']);
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
}