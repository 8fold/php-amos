<?php

declare(strict_types=1);

use Eightfold\Amos\PageComponents\PageTitle;

use Eightfold\FileSystem\Item;

use Eightfold\Amos\Store;

beforeEach(function() {
    $this->root = Item::create(__DIR__)->up(2)->append('content-example')
        ->thePath();
});

test('Default page title', function() {
    $store = Store::create($this->root);

    expect(
        PageTitle::create($store)->build()
    )->toBe('Index page');

    $store = Store::create($this->root, '/subfolder');

    expect(
        PageTitle::create($store)->build()
    )->toBe('Subfolder content title | Index page');

    $store = Store::create($this->root, '/subfolder/sub');

    expect(
        PageTitle::create($store)->build()
    )->toBe('Sub | Subfolder content title | Index page');
});

test('Book-end page title style', function() {
    $store = Store::create($this->root, '/subfolder/sub');

    expect(
        PageTitle::create($store)->bookEnd()->build()
    )->toBe(
        'Sub | Index page'
    );
});

test('Reversed page title', function() {
    $store = Store::create($this->root, '/subfolder/sub');

    expect(
        PageTitle::create($store)->reversed()->build()
    )->toBe('Index page | Subfolder content title | Sub');
});
