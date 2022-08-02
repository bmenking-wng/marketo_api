<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use WorldNewsGroup\Marketo\Environment;
use WorldNewsGroup\Marketo\Model\BulkExportLead;
use WorldNewsGroup\Marketo\Exception\ErrorException;

final class BulkLeadExportTest extends TestCase {

    public static function setUpBeforeClass(): void {
        Dotenv\Dotenv::createImmutable('.')->load();
        Environment::configure($_ENV['CLIENT_ID'], $_ENV['CLIENT_SECRET'], $_ENV['MUNCHKIN_ID']);
    }

    public function testCreateBulkExport() {
        $result = BulkExportLead::createExportLeadJob(['smartListName'=>'All People']);

        print_r($result);
    }
}