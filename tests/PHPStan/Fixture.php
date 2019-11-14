<?php

declare(strict_types=1);

namespace Tests\PHPStan;

use Carbon\Carbon;

Carbon::macro('phpStanMacro', static function (): string {
    return 'phpStanMacro';
});

class Fixture
{
    public function testCarbonMacroCalledStatically(): string
    {
        return Carbon::phpStanMacro();
    }

    public function testCarbonMacroCalledDynamically(): string
    {
        return Carbon::now()->phpStanMacro();
    }
}
