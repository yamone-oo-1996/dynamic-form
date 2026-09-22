<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * DEMO-ONLY TEST — DO NOT USE AS A REAL TESTING PATTERN.
 *
 * This test is intentionally non-deterministic: it passes or fails at
 * random (~50/50) on every run, purely to produce inconsistent pass/fail
 * signal across repeated CI executions so that Mergify Test Insights can
 * be validated as correctly classifying it as a flaky test.
 *
 * It is fully isolated from application code and does not exercise any
 * production behavior. Remove this file once the Mergify Test Insights
 * flaky-test detection demo/validation is complete.
 */
class FlakyDemoTest extends TestCase
{
    public function testIntentionallyFlakyAssertion()
    {
        $randomlyTrue = random_int(0, 1) === 1;

        $this->assertTrue(
            $randomlyTrue,
            'Intentional demo flake for Mergify Test Insights validation — not a real failure.'
        );
    }
}
