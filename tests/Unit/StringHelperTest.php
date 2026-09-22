<?php

namespace Tests\Unit;

use App\Helpers\StringHelper;
use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{
    public function testNormalizeDisplayNameKeepsNormalName()
    {
        $this->assertSame('John Doe', StringHelper::normalizeDisplayName('John Doe'));
    }

    public function testNormalizeDisplayNameTrimsLeadingAndTrailingWhitespace()
    {
        $this->assertSame('John Doe', StringHelper::normalizeDisplayName('  John Doe  '));
    }

    public function testNormalizeDisplayNameReturnsEmptyStringForEmptyInput()
    {
        $this->assertSame('', StringHelper::normalizeDisplayName(''));
    }

    public function testNormalizeDisplayNameCollapsesRepeatedInternalWhitespace()
    {
        $this->assertSame('John Doe', StringHelper::normalizeDisplayName('John    Doe'));
    }
}
