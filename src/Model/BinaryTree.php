<?php

namespace App\Model;

class BinaryTree
{
    public function __construct(
        protected string          $field,
        protected ?BinaryTreeNode $root,
        protected int             $compareOperationsCount = 0
    )
    {
    }

    /**
     * Get document index.
     *
     * @param string $needle String to search.
     *
     * @return int|bool
     */
    public function search(string $needle): int|bool
    {
        $this->compareOperationsCount = 0;
        $node = $this->getNode($needle, $this->root);

        return $node === null ? false : $node->getKey();
    }

    /**
     * Get node by value.
     *
     * @param string $needle String to search.
     * @param BinaryTreeNode|null $root Root node.
     *
     * @return BinaryTreeNode|null
     */
    protected function getNode(string $needle, ?BinaryTreeNode $root): ?BinaryTreeNode
    {
        $this->compareOperationsCount++;
        if ($root === null || $root->getValue() === $needle) {
            return $root;
        }

        $this->compareOperationsCount++;
        if (strcmp($needle, $root->getValue()) < 0) {
            return $this->getNode($needle, $root->getLeft());
        }

        return $this->getNode($needle, $root->getRight());
    }

    /**
     * Get number of operations.
     *
     * @return int
     */
    public function getCompareOperationsCount(): int
    {
        return $this->compareOperationsCount;
    }

    /**
     * Check if index is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->root === null;
    }

    /**
     * Save object to json string.
     *
     * @return string
     */
    public function toJson(): string
    {
        $content = $this->__serialize();

        return json_encode($content, JSON_PRETTY_PRINT);
    }

    /**
     * Load object from json string.
     *
     * @param string $data
     *
     * @return self
     */
    public static function fromJson(string $data): self
    {
        $tree = new self('', null);
        $data = json_decode($data, true);
        $tree->__unserialize($data);

        return $tree;
    }

    public function __serialize(): array
    {
        return [
            'field' => $this->field,
            'root' => $this->root->__serialize(),
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->field = $data['field'] ?? '';
        if (isset($data['root']) && is_array($data['root'])) {
            $this->root = new BinaryTreeNode();
            $this->root->__unserialize($data['root']);
        }
    }
}