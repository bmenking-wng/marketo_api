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
}