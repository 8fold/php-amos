<?php

use Eightfold\Amos\Forms\FormControls\Select;

test('Text can be optional with counter', function() {
    expect(
        Select::create('Option group', 'groups')
        ->options(
            [
                'optgroup label',
                'value1 Option A',
                'value2 Option B'
            ],
            'value3 Option C'
        )->build()
    )->toBe(<<<html
        <div is="form-control"><label id="groups-label" for="groups">Option group</label><select id="groups" name="groups" required><optgroup label="optgroup label"><option value="value1">Option A</option><option value="value2">Option B</option></optgroup><option value="value3">Option C</option></select></div>
        html
    );
});
