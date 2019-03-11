<?php

namespace Tests\Unit;

/**
 * Class ExampleTest
 * @package Tests\Unit
 */
class CreatesAppTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testCreatesApplication(): void
    {
        $request = $this->tester->getMockRequest();
        $response = $this->tester->processRequest($request);
    }
}