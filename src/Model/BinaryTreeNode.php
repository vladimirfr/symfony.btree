<?php

namespace App\Model;

class BinaryTreeNode
{
    protected ?BinaryTreeNode $left = null;
    protected ?BinaryTreeNode $right = null;

    public function __construct(
        protected int   $key = -1,
        protected mixed $value = null,
    )
    {
    }

    /**
     * Get left child.
     *
     * @return BinaryTreeNode|null
     */
    public function getLeft(): ?BinaryTreeNode
    {
        return $this->left;
    }

    /**
     * Set left child.
     *
     * @param BinaryTreeNode|null $left
     *
     * @return void
     */
    public function setLeft(?BinaryTreeNode $left): void
    {
        $this->left = $left;
    }

    /**
     * Get right child.
     *
     * @return BinaryTreeNode|null
     */
    public function getRight(): ?BinaryTreeNode
    {
        return $this->right;
    }

    /**
     * Set right child.
     *
     * @param BinaryTreeNode|null $right
     *
     * @return void
     */
    public function setRight(?BinaryTreeNode $right): void
    {
        $this->right = $right;
    }

    /**
     * Node value.
     *
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * Set document value.
     *
     * @param mixed $value
     * @return void
     */
    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    /**
     * Set Document index.
     *
     * @param int $key
     * @return void
     */
    public function setKey(int $key): void
    {
        $this->key = $key;
    }

    /**
     * Document index.
     *
     * @return int
     */
    public function getKey(): int
    {
        return $this->key;
    }

    public function __serialize(): array
    {
        $result = [
            'key' => $this->key,
            'value' => $this->value,
        ];

        if ($this->left !== null) {
            $result['left'] = $this->left->__serialize();
        }

        if ($this->right !== null) {
            $result['right'] = $this->right->__serialize();
        }

        return $result;
    }

    public function __unserialize(array $data): void
    {
        $this->key = $data['key'] ?? 0;
        $this->value = $data['value'] ?? null;

        if (isset($data['left'])) {
            $left = new BinaryTreeNode();
            $left->__unserialize($data['left']);
            $this->setLeft($left);
        }
        if (isset($data['left'])) {
            $right = new BinaryTreeNode();
            $right->__unserialize($data['right']);
            $this->setRight($right);
        }
    }
}