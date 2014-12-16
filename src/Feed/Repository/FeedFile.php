<?php

namespace Feed\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class FeedFile extends EntityRepository
{
    /**
     * @param int[] $filesToDelete
     *
     * @return array
     */
    public function deleteByIds(array $filesToDelete)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->delete('Feed\Entity\FeedFile', 'feedFile')
           ->where($qb->expr()->in('feedFile.id', $filesToDelete));
        return $qb->getQuery()->getResult();
    }
}
