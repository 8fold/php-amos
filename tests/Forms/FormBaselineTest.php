<?php

declare(strict_types=1);

use Eightfold\Amos\Forms\Form;

use Eightfold\HTMLBuilder\Element as HtmlElement;

use Eightfold\Amos\Forms\FormControls\Text;
use Eightfold\Amos\Forms\FormControls\Select;

test('Form can have content and controls', function() {
    expect(
        Form::create()->content(
            HtmlElement::p('These are form instructions.'),
            Text::create('Label text', 'input-name')
        )->build()
    )->toBe(<<<html
        <form action="/" method="post"><p>These are form instructions.</p><div is="form-control"><label id="input-name-label" for="input-name">Label text</label><input id="input-name" type="text" name="input-name" aria-describedby="input-name-label" maxlength="254" required></input></div><button>Submit</button></form>
        html
    );

    expect(
        Form::create()->content()->build()
    )->toBe(<<<html
        <form action="/" method="post"><button>Submit</button></form>
        html
    );
});

test('Form exists and is configurable', function() {
    expect(
        Form::create()->build()
    )->toBe(<<<html
        <form action="/" method="post"><button>Submit</button></form>
        html
    );

    expect(
        Form::create('get /some/path')->build()
    )->toBe(<<<html
        <form action="/some/path" method="get"><button>Submit</button></form>
        html
    );

    expect(
        Form::create('get /some/path')
            ->submitButtonDetails('Go!', ['id button'])->build()
    )->toBe(<<<html
        <form action="/some/path" method="get"><button id="button">Go!</button></form>
        html
    );

    expect(
        Form::create('get /some/path')
            ->props('id something', 'class health-survey')
            ->submitButtonDetails('Go!', ['id button'])->build()
    )->toBe(<<<html
        <form id="something" class="health-survey" action="/some/path" method="get"><button id="button">Go!</button></form>
        html
    );

    expect(
        Form::create('get /some/path')
            ->props('id something', 'class health-survey')
            ->submitButtonDetails('Go!', ['id button'])
            ->csrfTokenDetails('testing')->build()
    )->toBe(<<<html
        <form id="something" class="health-survey" action="/some/path" method="get"><input type="hidden" name="_token" value="testing"><button id="button">Go!</button></form>
        html
    );
});

test('Form is speedy', function() {
    $startMem = memory_get_usage();

    $start = hrtime(true);

    $result = Form::create('get /something')->props('id form')->content(
        Text::create('Label text', 'input-name'),
        Select::create("Select", "select")->options(
            "value1 Option A",
            "value2 Option B"
        )
    )->build();

    $end = hrtime(true);

    $endMem = memory_get_usage();

    expect(
        $result
    )->toBe(<<<html
        <form id="form" action="/something" method="get"><div is="form-control"><label id="input-name-label" for="input-name">Label text</label><input id="input-name" type="text" name="input-name" aria-describedby="input-name-label" maxlength="254" required></input></div><div is="form-control"><label id="select-label" for="select">Select</label><select id="select" name="select" required><option value="value1">Option A</option><option value="value2">Option B</option></select></div><button>Submit</button></form>
        html
    );

    $elapsed = $end - $start;
    $ms      = $elapsed/1e+6;

    expect($ms)->toBeLessThan(0.31); // previous 12.83ms

    $used = $endMem - $startMem;
    $kb   = round($used/1024.2);

    expect($kb)->toBeLessThan(30); // previous 115kb
});
