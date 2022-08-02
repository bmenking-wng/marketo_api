<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use WorldNewsGroup\Marketo\Environment;
use WorldNewsGroup\Marketo\Model\SmartList;
use WorldNewsGroup\Marketo\Exception\ErrorException;

final class SmartListTest extends TestCase {

    public static function setUpBeforeClass(): void {
        Dotenv\Dotenv::createImmutable('.')->load();
        Environment::configure($_ENV['CLIENT_ID'], $_ENV['CLIENT_SECRET'], $_ENV['MUNCHKIN_ID']);
    }

    public function testGetSmartLists() {
        $result = SmartList::getSmartLists();

        print_r($result);

        $this->assertTrue(true);
    }
}