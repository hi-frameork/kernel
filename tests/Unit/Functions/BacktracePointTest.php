<?php

namespace Hi\Tests\Unit\Functions;

use PHPUnit\Framework\TestCase;

class BacktracePointTest extends TestCase
{
    public function testBacktraceDefault()
    {
        $trace = backtracePoint();

        // $this->assertSame([], $trace);

        $this->assertArrayHasKey('line', $trace);
        $this->assertArrayHasKey('function', $trace);
        $this->assertArrayHasKey('class', $trace);
        $this->assertArrayHasKey('file', $trace);
    }

    public function testBacktraceWithFunction()
    {
        $trace = stubTestBacktraceWithFunction();

        // $this->assertSame([], $trace);

        $this->assertArrayHasKey('line', $trace);
        $this->assertArrayHasKey('function', $trace);
        $this->assertArrayHasKey('file', $trace);
    }
}

function stubTestBacktraceWithFunction()
{
    return backtracePoint();
}
