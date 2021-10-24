<?php

declare(strict_types=1);

use Eightfold\Amos\Store;

use Eightfold\FileSystem\Item;

// beforeEach(function() {
//     $this->store = Store::create()->withPath(__DIR__)->up()
//         ->appendPath('content-example');
// });

test('Can go up and down', function() {
    $dir = explode('/', __DIR__);
    array_pop($dir);
    $dir[] = 'tests';
    $dir[] = 'content-example';
    $dir = implode('/', $dir);

    expect(
        Store::create(__DIR__)->up()->appendPath('tests', 'content-example')
            ->getAbsolutePath()
    )->toBe(
        $dir
    );
});

test('Can append path', function() {
    $dir = explode('/', __DIR__);
    $dir[] = 'Extensions';
    $dir = implode('/', $dir);

    expect(
        Store::create(__DIR__)->appendPath('Extensions')
            ->getAbsolutePath()
    )->toBe(
        $dir
    );
});

test('Can go up', function() {
    $dir = explode('/', __DIR__);
    array_pop($dir);
    $dir = implode('/', $dir);

    expect(
        Store::create(__DIR__)->up()->getAbsolutePath()
    )->toBe(
        $dir
    );
});

// test('Markdown', function() {
//     expect(
//         $this->store->markdown()->title()
//     )->toBe('Index page');

//     expect(
//         Store::create($this->root, '/subfolder')->markdown()->title()
//     )->toBe('Subfolder content title');

//     expect(
//         Store::create($this->root, '/subfolder/sub')->markdown()->content()
//     )->toBe(<<<md
//         # A heading

//         This would be the body copy.

//         md
//     );

//     expect(
//         Store::create($this->root, '/subfolder/sub')->markdown()->html()
//     )->toBe(<<<md
//         <h1>A heading</h1>
//         <p>This would be the body copy.</p>

//         md
//     );
// });

// test('Navigation shorthand', function() {
//     expect(
//         $this->store->navigationWithFileName('primary.md')
//     )->toBe([
//         '/subfolder Link text',
//         '/subfolder/sub Link 2 text'
//     ]);

//     expect(
//         $this->store->navigationWithFileName('footer.md')
//     )->toBe([
//         '/subfolder Some other link text',
//         '/subfolder/sub Yet another link text'
//     ]);

//     expect(
//         $this->store->navigationWithFileName('tiered.md')
//     )->toBe([
//         '/subfolder Some other link text',
//         ['/subfolder/sub Yet another link text']
//     ]);
// });
