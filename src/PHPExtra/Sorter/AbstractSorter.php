<?php

namespace PHPExtra\Sorter;

use PHPExtra\Sorter\Comparator\ComparatorInterface;

/**
 * The AbstractSorterInterface class
 *
 * @author Jacek Kobus <kobus.jacek@gmail.com>
 */
abstract class AbstractSorterInterface implements SorterInterface
{
    /**
     * @var int
     */
    private $sortOrder = self::ASC;

    /**
     * @var SorterInterface
     */
    private $comparator;

    /**
     * @param int                $sortOrder
     * @param ComparatorInterface $comparator
     *
     * @internal param int $defaultOrder
     */
    function __construct(ComparatorInterface $comparator = null, $sortOrder = null)
    {
        if($sortOrder !== null){
            $this->setSortOrder($sortOrder);
        }

        if($comparator !== null){
            $this->setComparator($comparator);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setComparator(ComparatorInterface $comparator)
    {
        $this->comparator = $comparator;

        return $this;
    }

    /**
     * @return SorterInterface
     */
    protected function getComparator()
    {
        return $this->comparator;
    }

    /**
     * {@inheritdoc}
     */
    public function setSortOrder($order)
    {
        $this->sortOrder = $order;
        return $this;
    }

    /**
     * @return int
     */
    protected function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * {@inheritdoc}
     */
    public function sort($collection)
    {
        $comparator = $this->getComparator();
        $checker = $this->getValueChecker();

        usort($collection, function($a, $b) use ($comparator, $checker){
            /** @var ComparatorInterface $comparator */
            $checker($a, $b, $comparator);
            return $comparator->compare($a, $b);
        });
        return $collection;
    }

    /**
     * Returns a closure that validates values before passing them to the ComparatorInterface
     *
     * @return \Closure
     */
    protected function getValueChecker()
    {
        return function($a, $b, ComparatorInterface $comparator){
            if(!$comparator->supports($a)){
                throw new \RuntimeException(sprintf('Comparator does not support %s', gettype($a)));
            }

            if(!$comparator->supports($b)){
                throw new \RuntimeException(sprintf('Comparator does not support %s', gettype($b)));
            }
        };
    }
}