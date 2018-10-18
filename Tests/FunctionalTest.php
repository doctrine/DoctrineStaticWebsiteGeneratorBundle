<?php

declare(strict_types=1);

namespace Doctrine\Bundle\DoctrineStaticWebsiteGeneratorBundle\Tests;

use App\AppKernel;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use function assert;
use function file_exists;
use function file_get_contents;

class FunctionalTest extends TestCase
{
    public function testBuildWebsite() : void
    {
        $input = new ArrayInput(['command' => 'doctrine:build-website']);

        $kernel = new AppKernel('test', true);
        $kernel->boot();

        $container = $kernel->getContainer();

        $application = new Application($kernel);
        $application->setAutoExit(false);
        $application->run($input);

        $buildDir = __DIR__ . '/App/build-test';

        $indexContents = $this->getFileContents($buildDir, 'index.html');

        self::assertContains(
            '<title>Doctrine Static Website</title>',
            $indexContents
        );

        self::assertContains(
            '<h1>DoctrineStaticWebsiteGeneratorBundle</h1>',
            $indexContents
        );

        self::assertContains(
            '<p>Testing that DoctrineStaticWebsiteGeneratorBundle works in a Symfony application.</p>',
            $indexContents
        );

        self::assertContains('Homepage: /index.html', $indexContents);

        self::assertContains('Homepage URL: http://localhost/index.html', $indexContents);

        self::assertContains('Controller Data: This data came from the controller', $indexContents);

        self::assertContains('Request Data: /index.html', $indexContents);

        $jwageContents = $this->getFileContents($buildDir, 'user/jwage.html');

        self::assertContains('jwage', $jwageContents);

        $ocramiusContents = $this->getFileContents($buildDir, 'user/ocramius.html');

        self::assertContains('ocramius', $ocramiusContents);
    }

    private function getFileContents(string $buildDir, string $file) : string
    {
        $path = $buildDir . '/' . $file;

        self::assertTrue(file_exists($path));

        $contents = file_get_contents($path);
        assert($contents !== false);

        return $contents;
    }
}
