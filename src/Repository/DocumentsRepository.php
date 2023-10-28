<?php

namespace App\Repository;

use App\Model\BinaryTree;
use App\Model\SearchResult;
use \InvalidArgumentException;

class DocumentsRepository
{
    const ALLOWED_EXT = 'json';

    protected array $documents;

    protected BinaryTree|null $index = null;

    /**
     * Load documents from json path.
     *
     * @param string $path path to JSON file.
     *
     * @return void
     */
    public function load(string $path): void
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if ($ext != self::ALLOWED_EXT || !file_exists($path)) {
            throw new InvalidArgumentException('Bad file');
        }

        $documents = json_decode(file_get_contents($path), true);
        if (!is_array($documents)) {
            throw new InvalidArgumentException('Bad file');
        }

        $this->documents = $documents;
    }

    /**
     * Get all documents.
     *
     * @return array
     */
    public function findAll(): array
    {
        return $this->documents;
    }

    /**
     * Get number of documents.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->documents);
    }

    /**
     * Get document byy field and value.
     *
     * @param string $needle The string to search for.
     * @param string $field The field to search in.
     *
     * @return SearchResult
     */
    public function findByField(string $needle, string $field): SearchResult
    {
        $compareOperationsCount = 0;
        $result = false;
        if ($this->index !== null) {
            $documentIndex = $this->index->search($needle);
            $compareOperationsCount = $this->index->getCompareOperationsCount();
            if ($documentIndex !== false) {
                $result = $this->documents[$documentIndex];
            }
        } else {
            foreach ($this->documents as $document) {
                $value = $document[$field] ?? null;

                $compareOperationsCount++;
                if ($value == $needle) {
                    $result = $document;
                    break;
                }
            }
        }

        return new SearchResult(
            $result,
            $compareOperationsCount
        );
    }

    /**
     * Set binary tree index.
     *
     * @param BinaryTree|null $index
     *
     * @return void
     */
    public function setIndex(?BinaryTree $index): void
    {
        $this->index = $index;
    }
}