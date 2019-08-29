<?php

namespace App\Core\Router;

use App\Core\Router\UrlSegment;
use PHPUnit\Framework\TestCase;

class UrlSegmentTest extends TestCase
{
    public function testMatches()
    {
        $urlSeg = new UrlSegment('assets');
        $this->assertEquals(true, $urlSeg->matches('assets'));
    }

    public function testDoesNotMatch()
    {
        $urlSeg = new UrlSegment('assets');
        $this->assertEquals(false, $urlSeg->matches('elements'));
    }
}