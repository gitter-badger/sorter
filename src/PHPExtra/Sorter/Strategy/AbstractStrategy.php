<?php

namespace PHPExtra\Sorter\Strategy;

use PHPExtra\Sorter\Comparator\ComparatorInterface;
use PHPExtra\Sorter\Comparator\UnicodeCIComparator;
use PHPExtra\Sorter\SorterInterface;

/**
 * The AbstractSorterInterface class
 *
 * @author Jacek Kobus <kobus.jacek@gmail.com>
 */
abstract class AbstractStrategy implements StrategyInterface
{
    /**
     * @var int
     */
    private $sortOrder = self::ASC;
    
    /**
     *
     * @var bool
     */
    private $maintainKeyAssociation = false;

    /**
     * @var ComparatorInterface
     */
    private $comparator;

    /**
     * @param int                $sortOrder Default sort order
     * @param ComparatorInterface $comparator Default comparator
     *
     * @internal param int $defaultOrder
     */
    function __construct(ComparatorInterface $comparator = null, $sortOrder = null)
    {
        if($sortOrder){
            $this->setSortOrder($sortOrder);
        }

        if(!$comparator){
            $comparator = new UnicodeCIComparator();
        }
        $this->setComparator($comparator);
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
     * @return ComparatorInterface
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
     * 
     * {@inheritdoc}
     */
    public function setMaintainKeyAssociation($maintainKeyAssociation)
    {
        $this->maintainKeyAssociation = (bool)$maintainKeyAssociation;
    }
    
    public function getMaintainKeyAssociation()
    {
        return $this->maintainKeyAssociation;
    }

    /**
     * {@inheritdoc}
     */
    public function sort(array $collection)
    {
        $comparator = $this->getComparator();
        $checker = $this->getValueChecker();
        $function = $this->getMaintainKeyAssociation() ? 'uasort' : 'usort';

        $function($collection, function($a, $b) use ($comparator, $checker){
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

            $exceptionMessage = 'Comparator (%s) does not support "%s"';

            $error = null;
            if(!$comparator->supports($a)){
                $error = sprintf($exceptionMessage, get_class($comparator), gettype($a));
            }elseif(!$comparator->supports($b)){
                $error = sprintf($exceptionMessage, get_class($comparator), gettype($a));
            }

            if($error !== null){
                throw new \RuntimeException($error);
            }
        };
    }
}