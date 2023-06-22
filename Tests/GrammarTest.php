<?php

use GingTeam\Grammar;

test('extract 1', function () {
    $this->assertCount(1, png_extract('u00.spr'));
});

test('extract 2', function () {
    $this->assertCount(447, png_extract('HA3.bin'));
});

/**
 * @return array<string>
 */
function png_extract(string $filename)
{
    return iterator_to_array(Grammar::extract(__DIR__.'/Fixtures/'.$filename));
}
