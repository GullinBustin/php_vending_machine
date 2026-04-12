<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/app.php';

class DummyFunctionTest extends TestCase
{
    public function testDummyFunctionReturnsDummyResult()
    {
        $this->assertEquals('dummy result', dummyFunction());
    }
}
