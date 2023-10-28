<?php

namespace App\Command;

use App\Model\BinaryTree;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'binary-tree:search',
    description: 'Build Binary tree index',
    hidden: false,
)]
class SearchCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this
            ->addOption('index', 'i', InputOption::VALUE_OPTIONAL, 'Should be used binary tree index', false)
            ->addOption('input', 'in', InputOption::VALUE_OPTIONAL, 'Name of file with documents', 'documents.json')
            ->addOption('field', 'f', InputOption::VALUE_OPTIONAL, 'Name of field', 'name')
            ->addOption('needle', 's', InputOption::VALUE_REQUIRED, 'Value to search');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $source = $this->basePath . $input->getOption('input');
        if (!$this->loadDocuments($source)) {
            $io->error(sprintf('Bad file %s!', $source));
            return Command::FAILURE;
        } else {
            $io->info(sprintf('Total %d documents.', $this->repository->count()));
        }

        $isIndexNeeded = $input->getOption('index');
        $needle = $input->getOption('needle');
        $field = $input->getOption('field');
        if ($isIndexNeeded) {
            $indexPath = sprintf($this->cachePath, $field);

            if (file_exists($indexPath)) {
                $indexContent = file_get_contents($indexPath);
                $index = BinaryTree::fromJson($indexContent);

                if ($index->isEmpty()) {
                    $io->warning('Index is empty, trying without index...');
                } else {
                    $io->info('Search using index...');
                    $this->repository->setIndex($index);
                }
            } else {
                $io->warning(sprintf('Bad index file %s, trying without index...', $indexPath));
            }
        }

        $result = $this->repository->findByField($needle, $field);
        $io->success(sprintf('Total compare operations: %d', $result->getCompareOperationsCount()));

        $document = $result->getDocument();
        if ($document === false) {
            $io->success('Document not found');
        } else {
            $io->success('Found document:');
            $io->writeln(print_r($document, true));
        }

        return Command::SUCCESS;
    }
}