<?php

use Eightfold\Amos\Forms\FormControls\Select;

test('Select can be radio', function() {
    expect(
        Select::create(
            'I am interested in:',
            'interested-in'
        )->radio()->options(
            'interested-in-trainer An Agile Trainer',
            'interested-in-org-change Organizational Change Management',
            'interested-in-facilitator A Facilitator',
            'interested-in-coaching Enterprise or Team Coaching',
            'interested-in-group-coaching Group Coaching Offerings',
            'interested-in-speaker A Speaker',
            'interested-in-other Other'
        )->build()
    )->toBe(<<<html
        <fieldset is="form-control"><legend id="interested-in-legend">I am interested in:</legend><ul><li><label for="interested-in-trainer">An Agile Trainer</label><input id="interested-in-trainer" type="radio" name="interested-in" value="interested-in-trainer" required></li><li><label for="interested-in-org-change">Organizational Change Management</label><input id="interested-in-org-change" type="radio" name="interested-in" value="interested-in-org-change" required></li><li><label for="interested-in-facilitator">A Facilitator</label><input id="interested-in-facilitator" type="radio" name="interested-in" value="interested-in-facilitator" required></li><li><label for="interested-in-coaching">Enterprise or Team Coaching</label><input id="interested-in-coaching" type="radio" name="interested-in" value="interested-in-coaching" required></li><li><label for="interested-in-group-coaching">Group Coaching Offerings</label><input id="interested-in-group-coaching" type="radio" name="interested-in" value="interested-in-group-coaching" required></li><li><label for="interested-in-speaker">A Speaker</label><input id="interested-in-speaker" type="radio" name="interested-in" value="interested-in-speaker" required></li><li><label for="interested-in-other">Other</label><input id="interested-in-other" type="radio" name="interested-in" value="interested-in-other" required></li></ul></fieldset>
        html
    );
});

test('Select can have option groups', function() {
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
