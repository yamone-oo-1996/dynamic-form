<?php

namespace Tests\Unit;

use App\Helpers\StringHelper;
use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{
    public function testNormalizeDisplayNameKeepsNormalName()
    {
        $this->assertSame('John Doe1', StringHelper::normalizeDisplayName('John Doe1'));
    }

    public function testNormalizeDisplayNameTrimsLeadingAndTrailingWhitespace()
    {
        $this->assertSame('John Doe 1', StringHelper::normalizeDisplayName('  John Doe 1  '));
    }

    public function testNormalizeDisplayNameReturnsEmptyStringForEmptyInput()
    {
        $this->assertSame('', StringHelper::normalizeDisplayName(''));
    }

    public function testCollapseWhitespaceKeepsNormalName()
    {
        $this->assertSame('John Doe', StringHelper::collapseWhitespace('John Doe'));
    }

    public function testCollapseWhitespaceCollapsesMultipleInternalSpaces()
    {
        $this->assertSame('John Doe', StringHelper::collapseWhitespace('John    Doe'));
    }

    public function testCollapseWhitespaceCollapsesTabsAndNewlines()
    {
        $this->assertSame('John Doe', StringHelper::collapseWhitespace("  John\t\nDoe  "));
    }

    public function testNormalizeDisplayNameCollapsesRepeatedInternalWhitespace()
    {
        $this->assertSame('John Doe', StringHelper::normalizeDisplayName('John    Doe'));
    }
}
