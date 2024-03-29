<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\FileSystem;

use Eightfold\Amos\Tests\TestCase as BaseTestCase;

use Eightfold\Amos\FileSystem\Path;

use SplFileInfo;

class PathTest extends BaseTestCase
{
    private const TEST_PATH = DIRECTORY_SEPARATOR . 'test' .
        DIRECTORY_SEPARATOR . 'relative' .
        DIRECTORY_SEPARATOR . 'path';

    /**
     * @test
     */
    public function can_accept_uri_path(): void
    {
        $uri = parent::request(self::TEST_PATH)->getUri();

        $expected = self::TEST_PATH;

        $result = (string) Path::fromUri($uri);

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function can_cast_to_string(): void
    {
        $expected = self::TEST_PATH;

        $result = (string) Path::fromString($expected);

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function removes_trailing_slash(): void
    {
        $expected = self::TEST_PATH;

        $result = Path::fromString($expected . DIRECTORY_SEPARATOR)
            ->toString();

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function starts_with_slahs(): void
    {
        $expected = self::TEST_PATH;

        $use = 'test' . DIRECTORY_SEPARATOR .
            'relative' . DIRECTORY_SEPARATOR . 'path';

        $result = Path::fromString($use)->toString();

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function is_expected_path(): void
    {
        $expected = self::TEST_PATH;

        $result = Path::fromString($expected)->toString();
        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function is_expected_base_path(): void
    {
        $this->assertSame(
            '/',
            Path::fromString()->toString()
        );
    }
}
