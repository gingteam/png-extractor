<?php

use GingTeam\Grammar;

test('regex test 1', function () {
    $this->assertCount(1, Grammar::extract(getFixture('test.png')));
});

test('regex test 2', function () {
    $this->assertCount(447, Grammar::extract(getFixture('HA3.bin')));
});

function getFixture(string $filename): string
{
    return file_get_contents(__DIR__.'/Fixtures/'.$filename) ?? '';
}
