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
        $data = file_get_contents($this->path($input->getArgument('filename')));

        $filesystem = new Filesystem();
        $grammar = new Grammar();
        $outputPath = $this->path('output');

        if (!$filesystem->exists($outputPath)) {
            $filesystem->mkdir($outputPath);
        }

        $result = $grammar->findAll($data);
        foreach ($result as $hex) {
            $filesystem->dumpFile(
                $filesystem->tempnam($outputPath, 'dump_', '.png'),
                hex2bin($hex)
            );
        }

        $output->writeln(sprintf('<info>Success, Total: %s</info>', count($result)));

        return Command::SUCCESS;
    }

    protected function path(?string $path): string
    {
        return getcwd().($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}
