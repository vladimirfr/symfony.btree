<?php

namespace App\Model;

class SearchResult
{
    public function __construct(
        protected array|bool $document,
        protected int        $compareOperationsCount,
    )
    {
    }

    public function getDocument(): bool|array
    {
        return $this->document;
    }

    public function getCompareOperationsCount(): int
    {
        return $this->compareOperationsCount;
    }
}