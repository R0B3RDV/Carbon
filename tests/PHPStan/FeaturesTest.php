<?php

declare(strict_types=1);

namespace Tests;

class PHPStanTest extends AbstractTestCase
{
    protected $phpStanPreviousDirectory = '.';

    protected function setUp(): void
    {
        parent::setUp();
        $this->phpStanPreviousDirectory = getcwd();
        chdir(__DIR__.'/../..');
    }

    protected function tearDown(): void
    {
        chdir($this->phpStanPreviousDirectory);
        parent::tearDown();
    }

    public function testAnalysesWithoutErrors(): void
    {
        $this->assertTrue($this->analyze(__DIR__.'/Fixture.php', $output));
    }

    private function analyze(string $file, array &$output = null): bool
    {
        exec(
            implode(' ', [
                implode(DIRECTORY_SEPARATOR, ['.', 'vendor', 'bin', 'phpstan']),
                'analyse',
                '--configuration='.escapeshellarg(realpath(__DIR__.'/../../extension.neon')),
                '--no-progress',
                '--no-interaction',
                '--level=3',
                escapeshellarg(realpath($file)),
            ]),
            $output,
            $success
        );

        return $success === 0;
    }
}
