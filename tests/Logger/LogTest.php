<?php
declare(strict_types=1);

namespace Eightfold\Amos\Tests\Logger;

use Eightfold\Amos\Tests\TestCase;

use Eightfold\Amos\Logger\Log;

use StdClass;

class LogTest extends TestCase
{
    /**
     * @test
     */
    public function can_have_extras(): void
    {
        $expected = <<<json
        {
            "message": "This has extras.",
            "extras": {
                "extra_message": "Hello!",
                "extra_object": {
                    "prop": "works"
                }
            }
        }
        json;

        $extras                     = new StdClass();
        $extras->extra_message      = 'Hello!';
        $extras->extra_object       = new StdClass();
        $extras->extra_object->prop = 'works';

        $result = (string) Log::with(
            "This has extras.",
            extras: $extras
        );

        $this->assertSame(
            $expected,
            $result
        );
    }

    /**
     * @test
     */
    public function has_expected_message(): void
    {
        $expected = <<<json
        {
            "message": "This is an info message."
        }
        json;

        $result = (string) Log::with(
            'This is an {level} {type}.',
            context: [
                'level' => 'info',
                'type'  => 'message'
            ]
        );

        $this->assertSame(
            $expected,
            $result
        );

        $expected = <<<json
        {
            "message": "This is an error message."
        }
        json;

        $result = (string) Log::with(
            'This is an {level} {type}.',
            context: [
                'level' => 'error',
                'type'  => 'message'
            ]
        );

        $this->assertSame(
            $expected,
            $result
        );
    }
}
