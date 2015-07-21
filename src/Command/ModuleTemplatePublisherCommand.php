<?php

namespace Xsanisty\ModuleManager\Command;

use Exception;
use SilexStarter\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class ModuleTemplatePublisherCommand extends Command
{
    protected function configure()
    {
        $this->setName('module:publish-template')
             ->setDescription('Publish module template into application template directory');

        $this->addOption(
            'module',
            null,
            InputOption::VALUE_OPTIONAL,
            'If set, the command will publish templates of specific module'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app            = $this->getSilexStarter();
        $moduleManager  = $app['module'];
        $registeredMod  = $moduleManager->getRegisteredModules();
        $moduleId       = $input->getOption('module');

        $publishedMod   = [];

        if ($moduleId) {
            $publishedMod[] = $moduleId;
        } else {
            foreach ($registeredMod as $mod) {
                $publishedMod[] = $mod->getModuleIdentifier();
            }
        }

        foreach ($publishedMod as $mod) {
            if (isset($registeredMod[$mod])) {
                if ($registeredMod[$mod]->getResources()->views) {
                    try {
                        $output->writeLn('<info>Publishing template of '.$mod.' to '.$app['config']['twig.template_dir'].'/'.$mod.'</info>');
                        $moduleManager->publishTemplate($mod);
                    } catch (Exception $e) {
                        $output->writeLn('<error>Error occured while publishing template of "'.$mod.'" module</error>');
                        $output->writeLn('<error>'.$e->getMessage().'</error>');
                    }

                } else {
                    $output->writeLn('<comment>Module "'.$mod.'" has no defined template</comment>');
                }
            } else {
                $output->writeLn('<error>Module "'.$mod.'" is not registered</error>');
            }
        }
    }
}
