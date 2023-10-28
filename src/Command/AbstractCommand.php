<?php

namespace App\Command;

use App\Repository\DocumentsRepository;
use Symfony\Component\Console\Command\Command;

abstract class AbstractCommand extends Command
{
    public function __construct(
        protected string $basePath,
        protected string $cachePath,
        protected DocumentsRepository $repository,
        string           $name = null
    )
    {
        parent::__construct($name);
    }

    /**
     * Load documents to repository.
     *
     * @param string $source Path to source file.
     *
     * @return bool
     */
    protected function loadDocuments(string $source): bool
    {
        try {
            $this->repository->load($source);
        } catch (\InvalidArgumentException $e) {
            return false;
        }

        return true;
    }
}