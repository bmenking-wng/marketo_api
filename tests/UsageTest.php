<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use WorldNewsGroup\Marketo\Environment;
use WorldNewsGroup\Marketo\Model\Usage;
use WorldNewsGroup\Marketo\Model\Errors;
use WorldNewsGroup\Marketo\Exception\ErrorException;

final class UsageTest extends TestCase {
    public static function setUpBeforeClass(): void {
        Dotenv\Dotenv::createImmutable('.')->load();
        Environment::configure($_ENV['CLIENT_ID'], $_ENV['CLIENT_SECRET'], $_ENV['MUNCHKIN_ID']);
    }

    public function testGetDailyUsage() {
        $result = Usage::getDailyUsage();

        $this->assertIsArray($result);

        foreach($result as $obj) {
            $this->assertInstanceOf(Usage::class, $obj);
        }
    }

    public function testGetWeeklyUsage() {
        $result = Usage::getWeeklyUsage();

        $this->assertIsArray($result);

        foreach($result as $obj) {
            $this->assertInstanceOf(Usage::class, $obj);
        }
    } 

    public function testGetDailyErrors() {
        $result = Usage::getDailyErrors();

        $this->assertIsArray($result);

        foreach($result as $obj) {
            $this->assertInstanceOf(Errors::class, $obj);
        }
    }

    public function testGetWeeklyErrors() {
        $result = Usage::getWeeklyErrors();

        $this->assertIsArray($result);

        foreach($result as $obj) {
            $this->assertInstanceOf(Errors::class, $obj);
        }
    } 
}