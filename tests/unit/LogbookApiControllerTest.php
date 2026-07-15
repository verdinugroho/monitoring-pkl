<?php

use App\Controllers\Api\LogbookApiController;
use PHPUnit\Framework\TestCase;

class LogbookApiControllerTest extends TestCase
{
    public function testParseInputDataAcceptsJsonPayload(): void
    {
        $request = new class {
            public function getJSON($assoc = false)
            {
                return ['aktivitas' => 'Aktivitas baru', 'hasil' => 'Hasil baru'];
            }

            public function getRawInput()
            {
                return '';
            }

            public function getVar()
            {
                return [];
            }
        };

        $this->assertSame(
            ['aktivitas' => 'Aktivitas baru', 'hasil' => 'Hasil baru'],
            LogbookApiController::parseInputData($request)
        );
    }

    public function testParseInputDataFallsBackToRawJsonString(): void
    {
        $request = new class {
            public function getJSON($assoc = false)
            {
                return null;
            }

            public function getRawInput()
            {
                return '{"aktivitas":"Aktivitas baru","hasil":"Hasil baru"}';
            }

            public function getVar()
            {
                return [];
            }
        };

        $this->assertSame(
            ['aktivitas' => 'Aktivitas baru', 'hasil' => 'Hasil baru'],
            LogbookApiController::parseInputData($request)
        );
    }
}
