<?php
namespace ShopwareCli;

class DirectoryFilterIterator extends \FilterIterator
{
    /**
     * {@inheritdoc}
     */
    public function accept()
    {
        return $this->isValidDir($this->current());
    }

    /**
     * @param \DirectoryIterator $fileInfo
     *
     * @return bool
     */
    private function isValidDir(\DirectoryIterator $fileInfo)
    {
        return
            $fileInfo->isDir() &&
            !$fileInfo->isDot() &&
            // skip dot directories e.g. .git
            stripos($fileInfo->getBasename(), '.') !== 0;
    }
}
