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
    public function testGetLeadFieldByName() {
        $result = Lead::getLeadFieldByName('createdAt');

        print_r($result);

        $this->assetTrue(true);
    }
}