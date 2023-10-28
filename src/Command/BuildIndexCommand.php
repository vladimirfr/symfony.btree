<?php

namespace App\Command;

use App\Builder\BinaryTreeBuilder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'binary-tree:build',
    description: 'Build Binary tree index',
    hidden: false,
)]
class BuildIndexCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this
            ->addOption('input', 'in', InputOption::VALUE_OPTIONAL, 'Name of file with documents', 'documents.json')
            ->addOption('field', 'f', InputOption::VALUE_OPTIONAL, 'Name of field', 'name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $source = $this->basePath . $input->getOption('input');

        if (!$this->loadDocuments($source)) {
            $io->error(sprintf('Bad file %s!', $source));
            return Command::FAILURE;
        } else {
            $io->info(sprintf('Found %d documents.', $this->repository->count()));
        }

        $field = $input->getOption('field');

        $io->info('Start building index...');
        $service = new BinaryTreeBuilder();
        $tree = $service->build($this->repository->findAll(), $field);

        if ($tree->isEmpty()) {
            $io->warning('Index is empty, please check field name.');
        } else {
            $path = sprintf($this->cachePath, $field);
            file_put_contents($path, $tree->toJson());

            $io->success(sprintf('Index saved to %s.', $path));
        }

        return Command::SUCCESS;
    }
}