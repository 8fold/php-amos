<?php

declare(strict_types=1);

use Eightfold\Amos\Forms\Form;

use Eightfold\HTMLBuilder\Element as HtmlElement;

test('Form can have content and controls', function() {
    expect(
        Form::create()->content(
            HtmlElement::p('These are form instructions.')
        )->build()
    )->toBe(<<<html
        <form action="/" method="post"><p>These are form instructions.</p><button>Submit</button></form>
        html
    );

    expect(
        Form::create()->content()->build()
    )->toBe(<<<html
        <form action="/" method="post"><button>Submit</button></form>
        html
    );
})->group('focus');

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
        // // Presume Testbench token will always be empty to make testing easier.
        // $expected = '<form action="/" method="post"><input type="hidden" name="_token" value="testing"><button>Submit</button></form>';

        // AssertEquals::applyWith(
        //     $expected,
        //     "string",
        //     27.14, // 22.62, // 12.94,
        //     796 // 795
        // )->unfoldUsing(
        //     UIKit::form()
        // );

        // $expected = '<form id="form" action="/something" method="get"><input type="hidden" name="_token" value="testing"><button>Submit</button></form>';

        // AssertEquals::applyWith(
        //     $expected,
        //     "string",
        //     7.39, // 5.22, // 3.91, // 3.87, // 3.07,
        //     1
        // )->unfoldUsing(
        //     UIKit::form(
        //         "get /something"
        //     )->attr(
        //         "id form"
        //     )
        // );


        // $expected = '<form id="form" action="/request" method="get"><div is="form-control"><label id="select-label" for="select">Select</label><select id="select" name="select" required><option value="value1">Option A</option><option value="value2">Option B</option></select></div><input type="hidden" name="_token" value="testing"><button>Submit</button></form>';

        // AssertEquals::applyWith(
        //     $expected,
        //     "string",
        //     12.83, // 10.91, // 9.85, // 3.11,
        //     115 // 51
        // )->unfoldUsing(
        //     UIKit::form(
        //         "get /request",
        //         UIKit::select("Select", "select")->options(
        //             "value1 Option A",
        //             "value2 Option B"
        //         )
        //     )->attr("id form")
        // );

        // $expected = '<form action="/" method="post"><input type="hidden" name="_token" value="testing"><button>new label</button></form>';

        // AssertEquals::applyWith(
        //     $expected,
        //     "string",
        //     3.73, // 3.52, // 3.05, // 2.99,
        //     1
        // )->unfoldUsing(
        //     UIKit::form()->submitLabel("new label")
        // );


        // AssertEquals::applyWith(
        //     $expected,
        //     "string",
        //     5.25, // .15, // 3.94, // 3.76, // 3.62, // 3.55, // 3.52, // 2.92,
        //     1
        // )->unfoldUsing(
        //     UIKit::form()->attr("id form")
        // );
