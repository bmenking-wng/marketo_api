<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use WorldNewsGroup\Marketo\Environment;
use WorldNewsGroup\Marketo\Model\Opportunities;
use WorldNewsGroup\Marketo\Exception\ErrorException;

final class OpportunitiesTest extends TestCase {
    public static function setUpBeforeClass(): void {
        Dotenv\Dotenv::createImmutable('.')->load();
        Environment::configure($_ENV['CLIENT_ID'], $_ENV['CLIENT_SECRET'], $_ENV['MUNCHKIN_ID']);
    }

    public function testGetOpportunityFields() {
        $result = Opportunities::getOpportunityFields();

        $this->assertNotCount(0, $result->getResults());
    }

    /**
     * @depends testGetOpportunityFields
     */
    public function testGetOpportunityFieldByName() {
        $opFields = Opportunities::getOpportunityFields(5);

        foreach($opFields->getResults() as $field) {
            $result = Opportunities::getOpportunityFieldByName($field['name']);
        }

        $this->assertTrue(true);
    }

    /**
     * @depends testGetOpportunityFieldByName
     */
    public function testDescribeOpportunity() {
        $result = Opportunities::describeOpportunity();

        print_r($result);

        $this->assertTrue(true);
    }
}