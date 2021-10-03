<?php

declare(strict_types=1);

namespace GingTeam\Command;

use GingTeam\Grammar;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class ExtractCommand extends Command
{
    protected static $defaultName = 'run';

    protected function configure(): void
    {
        $this->addArgument('filename', InputArgument::REQUIRED, 'Filename.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = file_get_contents(path($input->getArgument('filename')));
        $exportPath = path('output');

        $filesystem = new Filesystem();

        if (!$filesystem->exists($exportPath)) {
            $filesystem->mkdir($exportPath);
        }

        $result = (new Grammar())->findAll($data);
        foreach ($result as $hex) {
            $filesystem->dumpFile(
                $filesystem->tempnam($exportPath, 'dump_', '.png'),
                hex2bin($hex)
            );
        }

        $output->writeln(sprintf('<info>Success, Total: %s</info>', count($result)));

        return Command::SUCCESS;
    }
}
