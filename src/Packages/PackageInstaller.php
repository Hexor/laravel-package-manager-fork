<?php

namespace LaravelPackageManager\Packages;

use LaravelPackageManager\Support\RunExternalCommand;

class PackageInstaller
{
    /**
     * Install a package using composer.
     * @param Package $package
     * @return void
     */
    public function install(Package $package, $options = null)
    {
        $cmd = $this->findComposerBinary().' require '.$package->getName();
        if ($package->getVersion()) {
            $cmd .= ':'.$package->getVersion();
        }
        if (isset($options)) {
            if ($options['dev']) {
                $cmd .= ' --dev';
            } elseif ($options['local-dev']) {
                $cmd .= ':*@dev';
            }
        }

        $runner = new RunExternalCommand($cmd);
        try {
            $runner->run();
        } catch (Exception $e) {
            echo 'Error: '.$e->getMessage().PHP_EOL;
        }
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposerBinary()
    {
        if (file_exists(base_path().'/composer.phar')) {
            return '"'.PHP_BINARY.'" composer.phar';
        }

        return 'composer';
    }
}
