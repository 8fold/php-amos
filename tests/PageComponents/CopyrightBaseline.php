<?php

declare(strict_types=1);

use Eightfold\Amos\PageComponents\Copyright;

test('Copyright is expected', function() {
    $year = date('Y');

    expect(
        Copyright::create('My Name')->build()
    )->toBe(<<<html
        <p>Copyright © {$year} My Name. All rights reserved.</p>
        html
    );

    expect(
        Copyright::create('My Name', 2000)->build()
    )->toBe(<<<html
        <p>Copyright © 2000–{$year} My Name. All rights reserved.</p>
        html
    );

    expect(
        Copyright::create('My Name', '2000')->build()
    )->toBe(<<<html
        <p>Copyright © 2000–{$year} My Name. All rights reserved.</p>
        html
    );
});
