<?php

namespace Xsanisty\ModuleManager\Command;

use Exception;
use SilexStarter\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class ModuleAssetPublisherCommand extends Command
{
    protected function configure()
    {
        $this->setName('module:publish-asset')
             ->setDescription('Publish module asset into public directory');

        $this->addArgument(
            'module',
            InputArgument::OPTIONAL,
            'If set, the command will publish assets of specific module'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app            = $this->getSilexStarter();
        $moduleManager  = $app['module'];
        $registeredMod  = $moduleManager->getRegisteredModules();
        $moduleId       = $input->getArgument('module');

        $publishedMod   = [];

        if ($moduleId) {
            $publishedMod[] = $moduleId;
        } else {
            $publishedMod   = array_keys($registeredMod);
        }

        foreach ($publishedMod as $mod) {
            if (isset($registeredMod[$mod])) {
                if ($registeredMod[$mod]->getResources()->assets) {
                    try {
                        $output->writeLn('<info>Publishing asset of '.$mod.' to '.$app['path.public'].'assets/'.$mod.'</info>');
                        $moduleManager->publishAsset($mod);
                    } catch (Exception $e) {
                        $output->writeLn('<error>Error occured while publishing asset of "'.$mod.'" module</error>');
                        $output->writeLn('<error>'.$e->getMessage().'</error>');
                    }

                } else {
                    $output->writeLn('<comment>Module "'.$mod.'" has no defined assets</comment>');
                }
            } else {
                $output->writeLn('<error>Module "'.$mod.'" is not registered</error>');
            }
        }
    }
}
