<?php

beforeEach(function() {
    $this->grammar = new GingTeam\Grammar();
});

test('regex test 1', function () {
    $this->assertCount(1, $this->grammar->findAll(getFixture('test.png')));
});

test('regex test 2', function () {
    $this->assertCount(447, $this->grammar->findAll(getFixture('HA3.bin')));
});

function getFixture(string $filename): string {
    return file_get_contents(__DIR__.'/Fixtures/'.$filename) ?? '';
}
