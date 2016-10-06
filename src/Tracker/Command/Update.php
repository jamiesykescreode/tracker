<?php

namespace Tracker\Command;

use Humbug\SelfUpdate\Updater;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Update extends Command {
    protected function configure() {
        $this
            ->setName('update')
            ->setDescription('Updates the application to the latest version.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $updater = new Updater();
        $updater->getStrategy()->setPharUrl('https://jamiesykescreode.github.io/tracker/tracker.phar');
        $updater->getStrategy()->setVersionUrl('https://jamiesykescreode.github.io/tracker/tracker.phar.version');
        try {
            $result = $updater->update();
            if (! $result) {
                // No update needed!
                $output->writeln('<info>Tracker is already at the latest version. Go you! :)</info>');
                exit;
            }
            $new = $updater->getNewVersion();
            $old = $updater->getOldVersion();

            $output->writeln('<info>Updated from '.$old.' to '.$new.'</info>');
            exit;
        } catch (\Exception $e) {
            // Report an error!
            $output->writeln('<error>'.$e->getMessage().'</error>');
            return 404;
            exit;
        }
    }
}

?>