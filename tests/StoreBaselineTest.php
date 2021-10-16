<?php

declare(strict_types=1);

use Eightfold\Amos\Store;

use Eightfold\FileSystem\Item;

beforeEach(function() {
    $this->root = Item::create(__DIR__)->up()->append('content-example')
        ->thePath();

    $this->store = Store::create($this->root);
});

test('Properties', function() {
    expect(
        $this->store->root()
    )->toBe(
        $this->root
    );

    expect(
        $this->store->path()
    )->toBe(
        ''
    );

    expect(
        $this->store->isRoot()
    )->toBeTrue();

    expect(
        Store::create(
            $this->store->root()
        )->media($this->store->path())->hasfile('poster.png')
    )->toBeTrue();

    expect(
        Store::create(
            $this->store->root()
        )->media($this->store->path())->hasfile('something.png')
    )->toBeFalse();

    expect(
        Store::create(
            $this->store->root()
        )->assets($this->store->path())->hasfile('poster.png')
    )->toBeTrue();

    expect(
        Store::create(
            $this->store->root()
        )->assets($this->store->path())->hasfile('something.png')
    )->toBeFalse();
});

test('Markdown', function() {
    expect(
        $this->store->markdown()->title()
    )->toBe('Index page');

    expect(
        Store::create($this->root, '/subfolder')->markdown()->title()
    )->toBe('Subfolder content title');

    expect(
        Store::create($this->root, '/subfolder/sub')->markdown()->content()
    )->toBe(<<<md
        # A heading

        This would be the body copy.

        md
    );

    expect(
        Store::create($this->root, '/subfolder/sub')->markdown()->html()
    )->toBe(<<<md
        <h1>A heading</h1>
        <p>This would be the body copy.</p>

        md
    );
});

test('Folder content', function() {
    $this->assertEquals(
        Store::create($this->root)->folderContent('.navigation'),
        [
            Item::create($this->root)->append('.navigation', 'footer.md'),
            Item::create($this->root)->append('.navigation', 'primary.md'),
            Item::create($this->root)->append('.navigation', 'tiered.md'),
        ]
    );
});

test('Navigation shorthand', function() {
    expect(
        Store::create($this->root)->navigation('primary.md')
    )->toBe([
        '/subfolder Link text',
        '/subfolder/sub Link 2 text'
    ]);

    expect(
        Store::create($this->root)->navigation('footer.md')
    )->toBe([
        '/subfolder Some other link text',
        '/subfolder/sub Yet another link text'
    ]);

    expect(
        Store::create($this->root)->navigation('tiered.md')
    )->toBe([
        '/subfolder Some other link text',
        ['/subfolder/sub Yet another link text']
    ]);
});
