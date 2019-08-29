<?php

namespace App\Core\Router;

use PHPUnit\Framework\TestCase;

class DynamicUrlSegmentTest extends TestCase
{
    public function testMatches()
    {
        $dynamicSeg = new DynamicUrlSegment('{id}');
        $this->assertEquals(true, $dynamicSeg->matches(4));
    }

    public function testGetParametersReturnOriginalUrl()
    {
        $dynamicSeg = new DynamicUrlSegment('{id}');
        $this->assertEquals(['id' => 5], $dynamicSeg->getParameters(5));
    }
}