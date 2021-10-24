<?php

use Eightfold\Amos\Forms\FormControls\Text;

test('Text can be textarea', function() {
    expect(
        Text::create(
            'Message',
            'message'
        )->long()->props(
            "maxlength 500",
            'aria-describedby contact-counter',
            'onkeyup countCharacters(this)'
        )->build()
    )->toBe(<<<html
        <div is="form-control"><label id="message-label" for="message">Message</label><textarea id="message" name="message" aria-describedby="contact-counter" maxlength="500" onkeyup="countCharacters(this)" required></textarea></div>
        html
    );
});

test('Text can have placeholder text', function() {
    expect(
        Text::create('Email address to reply to', 'email')->email()
            ->placeholder('admin@8fold.pro')->build()
    )->toBe(<<<html
        <div is="form-control"><label id="email-label" for="email">Email address to reply to</label><input id="email" type="email" name="email" aria-describedby="email-label" maxlength="254" placeholder="admin@8fold.pro" required></div>
        html
    );
});

test('Text can be optional with counter', function() {
    expect(
        Text::create("A text field with a counter", "name")
            ->hasCounter()
            ->optional()
            ->build()
    )->toBe(<<<html
        <div is="form-control"><label id="name-label" for="name">A text field with a counter</label><input id="name" type="text" name="name" aria-describedby="name-label" maxlength="254"><span id="name-counter" aria-live="polite"><i>254</i> characters remaining</span></div>
        html
    );
});
