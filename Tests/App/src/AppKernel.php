<?php

declare(strict_types=1);

namespace App;

use Doctrine\Bundle\DoctrineSkeletonMapperBundle\DoctrineSkeletonMapperBundle;
use Doctrine\Bundle\DoctrineStaticWebsiteGeneratorBundle\DoctrineStaticWebsiteGeneratorBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Kernel;
use function assert;
use function in_array;
use function realpath;

class AppKernel extends Kernel
{
    /**
     * @return Bundle[]
     */
    public function registerBundles() : array
    {
        $bundles = [];

        if (in_array($this->getEnvironment(), ['test'], true)) {
            $bundles[] = new FrameworkBundle();
            $bundles[] = new DoctrineSkeletonMapperBundle();
            $bundles[] = new DoctrineStaticWebsiteGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader) : void
    {
        $loader->load(__DIR__ . '/../config/config.yml');
    }

    public function getProjectDir() : string
    {
        $projectDir = realpath(__DIR__ . '/..');
        assert($projectDir !== false);

        return $projectDir;
    }

    public function getRootDir() : string
    {
        $rootDir = realpath(__DIR__ . '/..');
        assert($rootDir !== false);

        return $rootDir;
    }
}
