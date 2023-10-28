<?php

namespace App\Builder;

use App\Model\BinaryTree;
use App\Model\BinaryTreeNode;

class BinaryTreeBuilder
{
    /**
     * Build binary tree index.
     *
     * @param array $documents Array of documents.
     * @param string $field Field to index.
     * @return BinaryTree|null
     */
    public function build(array $documents, string $field): ?BinaryTree
    {
        $index = [];
        foreach ($documents as $key => $document) {
            if (!isset($document[$field])) {
                continue;
            }

            $value = $document[$field];

            $index[] = [
                'key' => $key,
                'value' => $value,
            ];
        }

        if (!empty($index)) {
            usort($index, function ($a, $b) use ($field) {
                return strcmp($a['value'], $b['value']);
            });

            $root = $this->insertNode($index, 0, count($index) - 1);

            return new BinaryTree($field, $root);
        }

        return null;
    }

    /**
     * Insert node to tree.
     *
     * @param array $documents Nodes data array.
     * @param int $start Start index.
     * @param int $end End index.
     * @return BinaryTreeNode|null
     */
    protected function insertNode(array $documents, int $start, int $end): ?BinaryTreeNode
    {
        if ($end < $start) {
            return null;
        }

        $middle = floor(($end + $start) / 2.0);

        $currentDocument = $documents[$middle];

        $node = new BinaryTreeNode($currentDocument['key'], $currentDocument['value']);
        $node->setLeft(
            $this->insertNode($documents, $start, $middle - 1)
        );
        $node->setRight(
            $this->insertNode($documents, $middle + 1, $end)
        );

        return $node;
    }
}