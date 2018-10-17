<?php

declare(strict_types=1);

namespace Doctrine\Bundle\DoctrineStaticWebsiteGeneratorBundle\Command;

use Doctrine\StaticWebsiteGenerator\SourceFile\SourceFileRepository;
use Doctrine\StaticWebsiteGenerator\SourceFile\SourceFilesBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildWebsiteCommand extends Command
{
    /** @var SourceFileRepository */
    private $sourceFileRepository;

    /** @var SourceFilesBuilder */
    private $sourceFilesBuilder;

    /** @var string */
    private $buildDir;

    public function __construct(
        SourceFileRepository $sourceFileRepository,
        SourceFilesBuilder $sourceFilesBuilder,
        string $buildDir
    ) {
        parent::__construct();

        $this->sourceFileRepository = $sourceFileRepository;
        $this->sourceFilesBuilder   = $sourceFilesBuilder;
        $this->buildDir             = $buildDir;
    }

    protected function configure() : void
    {
        $this
            ->setName('doctrine:build-website');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $sourceFiles = $this->sourceFileRepository->getSourceFiles($this->buildDir);

        $this->sourceFilesBuilder->buildSourceFiles($sourceFiles);

        return 0;
    }
}
