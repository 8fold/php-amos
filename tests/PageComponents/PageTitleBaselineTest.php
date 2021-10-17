<?php

declare(strict_types=1);

use Eightfold\Amos\PageComponents\PageTitle;

use Eightfold\FileSystem\Item;

use Eightfold\Amos\Store;

beforeEach(function() {
    $this->root = Item::create(__DIR__)->up(2)->append('content-example')
        ->thePath();
});

test('Can add arbitrary title parts', function() {
    $store = Store::create($this->root)->append('subfolder');

    // When working with Events there are URLs with no corresponding folder with
    // content. However, we don't want all the event-related URLs to just say
    // "Events". So, with this method, we can add title parts.
    expect(
        PageTitle::create($store)
            ->buildWithAdditions('May', '2020', 'Events')
    )->toBe(
        'May | 2020 | Events | Subfolder content title | Index page'
    );

    expect(
        PageTitle::create($store)
            ->reversed()
            ->buildWithAdditions('May', '2020', 'Events')
    )->toBe(
        'Index page | Subfolder content title | Events | 2020 | May'
    );
});

test('Default page title', function() {
    $store = Store::create($this->root);

    expect(
        PageTitle::create($store)->build()
    )->toBe('Index page');

    expect(
        PageTitle::create($store)->buildBookend()
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

test('Bookend page title style', function() {
    $store = Store::create($this->root, '/subfolder/sub');

    expect(
        PageTitle::create($store)->buildBookend()
    )->toBe(
        'Sub | Index page'
    );
});

test('Reversed page title', function() {
    $store = Store::create($this->root, '/subfolder/sub');

    expect(
        PageTitle::create($store)->build()
    )->toBe('Sub | Subfolder content title | Index page');

    expect(
        PageTitle::create($store)->reversed()->build()
    )->toBe('Index page | Subfolder content title | Sub');
});

$ms = function($start, $end) {
    $time = $end - $start;
    $ms   = $time/1e+6;
    return $ms;
};

test('Performant and cached', function() use ($ms) {
    $store = Store::create($this->root, '/subfolder/sub');

    $start = hrtime(true);

    $pageTitle = PageTitle::create($store);

    $start1 = hrtime(true);

    $build1 = $pageTitle->build();

    $end1 = hrtime(true);

    $start2 = hrtime(true);

    $build2 = $pageTitle->buildBookend();

    $end2 = hrtime(true);

    // Total elapsed time
    $leadTime = $ms($start, $end2);

    expect($leadTime)->toBeLessThan(0.53);

    // build call
    $cycleTime1 = $ms($start1, $end1);

    expect($cycleTime1)->toBeLessThan(0.41);

    // build bookend call
    $cycleTime2 = $ms($start2, $end2);

    expect($cycleTime2)->toBeLessThan(0.003);
});
