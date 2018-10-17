<?php

declare(strict_types=1);

namespace Doctrine\Bundle\DoctrineStaticWebsiteGeneratorBundle\Tests\Command;

use Doctrine\Bundle\DoctrineStaticWebsiteGeneratorBundle\Command\BuildWebsiteCommand;
use Doctrine\StaticWebsiteGenerator\SourceFile\SourceFileRepository;
use Doctrine\StaticWebsiteGenerator\SourceFile\SourceFiles;
use Doctrine\StaticWebsiteGenerator\SourceFile\SourceFilesBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function sys_get_temp_dir;

class BuildWebsiteCommandTest extends TestCase
{
    /** @var SourceFileRepository|MockObject */
    private $sourceFileRepository;

    /** @var SourceFilesBuilder|MockObject */
    private $sourceFilesBuilder;

    /** @var string */
    private $buildDir;

    /** @var BuildWebsiteCommand */
    private $buildWebsiteCommand;

    public function testConfigure() : void
    {
        $this->invokeMethod($this->buildWebsiteCommand, 'configure');

        self::assertSame('doctrine:build-website', $this->buildWebsiteCommand->getName());
    }

    public function testExecute() : void
    {
        $input  = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        $sourceFiles = new SourceFiles([]);

        $this->sourceFileRepository->expects(self::once())
            ->method('getSourceFiles')
            ->with($this->buildDir)
            ->willReturn($sourceFiles);

        $this->sourceFilesBuilder->expects(self::once())
            ->method('buildSourceFiles')
            ->with($sourceFiles);

        $this->invokeMethod($this->buildWebsiteCommand, 'execute', [
            $input,
            $output,
        ]);
    }

    protected function setUp() : void
    {
        $this->sourceFileRepository = $this->createMock(SourceFileRepository::class);
        $this->sourceFilesBuilder   = $this->createMock(SourceFilesBuilder::class);
        $this->buildDir             = sys_get_temp_dir() . '/build';

        $this->buildWebsiteCommand = new BuildWebsiteCommand(
            $this->sourceFileRepository,
            $this->sourceFilesBuilder,
            $this->buildDir
        );
    }

    /**
     * @param object  $object
     * @param mixed[] $parameters
     *
     * @return mixed
     */
    private function invokeMethod($object, string $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass($object);
        $method     = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
