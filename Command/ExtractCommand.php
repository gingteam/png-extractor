<?php

declare(strict_types=1);

namespace GingTeam\Command;

use GingTeam\Grammar;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

final class ExtractCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'run';

    protected function configure(): void
    {
        $this->setDescription('Extract binary data appended to a png file')
            ->addArgument('filename', InputArgument::REQUIRED);
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $data = file_get_contents(path($input->getArgument('filename')));
        $exportPath = path('output');

        $filesystem = new Filesystem();

        if (!$filesystem->exists($exportPath)) {
            $filesystem->mkdir($exportPath);
        }

        $result = Grammar::extract($data);
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
