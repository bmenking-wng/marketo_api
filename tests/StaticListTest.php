<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use WorldNewsGroup\Marketo\Environment;
use WorldNewsGroup\Marketo\Model\StaticList;
use WorldNewsGroup\Marketo\Model\Lead;
use WorldNewsGroup\Marketo\Result;
use WorldNewsGroup\Marketo\Exception\ErrorException;

final class StaticListTest extends TestCase {
    public static function setUpBeforeClass(): void {
        Dotenv\Dotenv::createImmutable('.')->load();
        Environment::configure($_ENV['CLIENT_ID'], $_ENV['CLIENT_SECRET'], $_ENV['MUNCHKIN_ID']);
    }

    public function testGetListsAndGetListById() {
        $lists = StaticList::getLists([], [], [], [], 5);

        foreach($lists as $list) {
            $pulledList = StaticList::getListById($list->id);

            $this->assertIsArray($pulledList);

            foreach($pulledList as $l) {
                $this->assertInstanceOf(StaticList::class, $l);
            }
        }
    }

}