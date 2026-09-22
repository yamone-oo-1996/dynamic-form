<?php

namespace Tests\Unit;

use App\Helpers\GreetingHelper;
use PHPUnit\Framework\TestCase;

class GreetingHelperTest extends TestCase
{
    public function testBuildGreetingForNormalName()
    {
        $this->assertSame('Hello, John Doe!', GreetingHelper::buildGreeting('John Doe'));
    }

    public function testBuildGreetingPreservesInternalSpacingFromNormalizeDisplayName()
    {
        $this->assertSame('Hello, John  Doe!', GreetingHelper::buildGreeting('  John  Doe  '));
    }
}
