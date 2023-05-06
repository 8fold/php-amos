<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests;

use Eightfold\Amos\Tests\TestCase;

use function Eightfold\Amos\real_path_for_dir;
use function Eightfold\Amos\real_path_for_file;
use function Eightfold\Amos\real_path_for_meta_file;

use function Eightfold\Amos\real_paths_for_files_named;

use function Eightfold\Amos\contents_of_file;

use function Eightfold\Amos\object_from_json_in_file;

use function Eightfold\Amos\meta_exists_in_dir;

use function Eightfold\Amos\meta_in_dir;

use function Eightfold\Amos\title_for_meta_in_dir;

use function Eightfold\Amos\real_path_for_public_dir;
use function Eightfold\Amos\real_path_for_public_file;
use function Eightfold\Amos\real_path_for_public_meta_file;

use function Eightfold\Amos\real_paths_for_public_files_named;
use function Eightfold\Amos\real_paths_for_public_meta_files;

use function Eightfold\Amos\contents_of_public_file;

use function Eightfold\Amos\object_from_json_in_public_file;

use function Eightfold\Amos\meta_exists_in_public_dir;

use function Eightfold\Amos\meta_in_public_dir;

use function Eightfold\Amos\title_for_meta_in_public_dir;

use StdClass;

class FunctionsTest extends TestCase
{
    private function contentRoot(): string
    {
        $content_root = __DIR__ . '/test-content';
        return $content_root;
    }

    /**
     * @test
     */
    public function can_get_root_path(): void
    {
        $expected = $this->contentRoot();

        $result = real_path_for_dir($expected);

        $this->assertSame(
            $expected,
            $result
        );

        $expected = '';

        $result = real_path_for_dir($this->contentRoot(), '/non-existent');

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_public_path(): void
    {
        $expected = $this->contentRoot() . '/public';

        $result = real_path_for_public_dir($this->contentRoot());

        $this->assertSame(
            $expected,
            $result
        );

        $expected = '';

        $result = real_path_for_public_dir($this->contentRoot(), '/non-existent');

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_filename_at_path(): void
    {
        $expected = $this->contentRoot() . '/public/deeper-page/meta.json';

        $result = real_path_for_file(
            $this->contentRoot(),
            'meta.json',
            '/public/deeper-page'
        );

        $this->assertSame(
            $expected,
            $result
        );

        $expected = '';

        $result = real_path_for_file(
            $this->contentRoot(),
            'meta.json',
            '/non-existent'
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_meta_file_at_root(): void
    {
        $expected = $this->contentRoot() . '/meta.json';

        $result = real_path_for_meta_file($this->contentRoot());

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_filename_at_public_path(): void
    {
        $expected = $this->contentRoot() . '/public/deeper-page/meta.json';

        $result = real_path_for_public_file(
            $this->contentRoot(),
            'meta.json',
            '/deeper-page'
        );

        $this->assertSame(
            $expected,
            $result
        );

        $expected = '';

        $result = real_path_for_public_file(
            $this->contentRoot(),
            'meta.json',
            '/non-existent'
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_public_meta_file(): void
    {
        $expected = $this->contentRoot() . '/public/deeper-page/meta.json';

        $result = real_path_for_public_meta_file($this->contentRoot(), '/deeper-page');

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_paths_for_files_based_on_name(): void
    {
        $expected = [
            $this->contentRoot() . '/public/deeper-page/meta.json',
            $this->contentRoot() . '/public/skipping-sitemap/meta.json',
            $this->contentRoot() . '/public/meta.json',
            $this->contentRoot() . '/meta.json'
        ];

        $result = real_paths_for_files_named($this->contentRoot(), 'meta.json');

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_paths_for_public_files_based_on_name(): void
    {
        $expected = [
            $this->contentRoot() . '/public/deeper-page/meta.json',
            $this->contentRoot() . '/public/skipping-sitemap/meta.json',
            $this->contentRoot() . '/public/meta.json'
        ];

        $result = real_paths_for_public_files_named(
            $this->contentRoot(),
            'meta.json'
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_paths_for_public_meta_files_based_on_name(): void
    {
        $expected = [
            $this->contentRoot() . '/public/deeper-page/meta.json',
            $this->contentRoot() . '/public/skipping-sitemap/meta.json',
            $this->contentRoot() . '/public/meta.json'
        ];

        $result = real_paths_for_public_meta_files($this->contentRoot());

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_file_contents(): void
    {
        $expected = <<<json
        {}
        json;

        $result = contents_of_file($this->contentRoot(), 'meta.json');

        $this->assertSame(
            $expected . "\n",
            $result
        );

        $expected = <<<json
        {
          "title": "Root test content"
        }
        json;

        $result = contents_of_file($this->contentRoot(), 'meta.json', '/public');

        $this->assertSame(
            $expected . "\n",
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_public_file_contents(): void
    {
        $expected = <<<json
        {
          "title": "Root test content"
        }
        json;

        $result = contents_of_public_file(
            $this->contentRoot(),
            'meta.json'
        );

        $this->assertSame(
            $expected . "\n",
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_json_object(): void
    {
        $expected = new StdClass();

        $result = object_from_json_in_file($this->contentRoot(), 'meta.json');

        $this->assertEquals(
            $expected,
            $result
        );

        $expected = new StdClass();
        $expected-> title = 'Root test content';

        $result = object_from_json_in_file(
            $this->contentRoot(),
            'meta.json',
            '/public'
        );

        $this->assertEquals(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_public_json_object(): void
    {
        $expected = new StdClass();
        $expected-> title = 'Root test content';

        $result = object_from_json_in_public_file(
            $this->contentRoot(),
            'meta.json'
        );

        $this->assertEquals(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_check_for_meta_files(): void
    {
        $result = meta_exists_in_dir(
            $this->contentRoot()
        );

        $this->assertTrue(
            $result
        );

        $result = meta_exists_in_dir(
            $this->contentRoot(),
            '/public'
        );

        $this->assertTrue(
            $result
        );

        $result = meta_exists_in_public_dir(
            $this->contentRoot()
        );

        $this->assertTrue(
            $result
        );

        $result = meta_exists_in_public_dir(
            $this->contentRoot(),
            '/deeper-page'
        );

        $this->assertTrue(
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_public_meta(): void
    {
        $expected = new StdClass();
        $expected->title = 'Root test content';

        $result = meta_in_public_dir(
            $this->contentRoot()
        );

        $this->assertEquals(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_get_public_meta_title(): void
    {
        $expected = 'Root test content';

        $result = title_for_meta_in_public_dir(
            $this->contentRoot()
        );

        $this->assertEquals(
            $expected,
            $result
        );
    }
}
