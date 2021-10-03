<?php


$grammar = new GingTeam\Grammar();

test('regex test 1', function () use ($grammar) {
    $data = file_get_contents(__DIR__.'/Fixtures/test.png');
    $this->assertCount(1, $grammar->findAll($data));
});

test('regex test 2', function () use ($grammar) {
    $data = file_get_contents(__DIR__.'/Fixtures/HA3.bin');
    $this->assertCount(447, $grammar->findAll($data));
});
