<?php

namespace Feed\Repository;

use Feed\Entity\Feed as FeedEntity;
use Feed\Entity\FeedFile as FeedFileEntity;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use DateTime;

class Feed extends EntityRepository
{
    /**
     * @param int $tagId
     *
     * @return FeedEntity[]
     */
    public function findFeedByTag($tagId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('f')
           ->from('Feed\Entity\FeedTag', 'ft')
           ->leftJoin('Feed\Entity\Feed', 'f', 'WITH', 'ft.feed = f')
           ->leftJoin('ft.tag', 't', 'WITH', 'ft.tag = t')
           ->where('t = :tagId')
           ->setParameter('tagId', $tagId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $feedId
     *
     * @return FeedFileEntity[]
     */
    public function findFiles($feedId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('file')
           ->from('Feed\Entity\FeedFile', 'file')
           ->leftJoin('Feed\Entity\Feed', 'f', 'WITH', 'file.feed = f')
           ->where('f = :feedId')
           ->setParameter('feedId', $feedId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $userId
     *
     * @return FeedEntity[]
     */
    public function findAllFeedEntityByUserId($userId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('f')
            ->from('Feed\Entity\Feed', 'f')
            ->join('f.', 'f')
            ->where('f.memberId = :userId')
            ->setParameter('userId', $userId);

        $query = $qb->getQuery();

        $result = $query->getArrayResult();

        return $result;
    }

    /**
     * Изменить количество комментариев
     *
     * @param      $feedId
     * @param bool $direction
     */
    public function changeCommentsCount($feedId, $direction = true)
    {
        /** @var \Feed\Entity\Feed $feed */
        $feed = $this->find($feedId);
        $feed->changeValue('commentCount', $direction);
        $feed->changeValue('popularityCount', $direction);

        $this->getEntityManager()->flush();
    }

    /**
     * Изменить количество лайков\полезного
     *
     * @param $feedId
     * @param $value
     * @param bool $direction
     */
    public function changeLikesCount($feedId, $value, $direction = true)
    {
        /** @var \Feed\Entity\Feed $feed */
        $feed = $this->find($feedId);
        $feed->changeValue($value, $direction);
        $feed->changeValue('popularityCount', $direction);

        $this->getEntityManager()->flush();
    }

    /**
     * Пометить пост, как удаленный
     *
     * @param $feedId
     */
    public function deleteFeed($feedId)
    {
        /** @var \Feed\Entity\Feed $feed */
        $feed = $this->find($feedId);
        $feed->setIsDeleted(true);

        $this->getEntityManager()->flush();
    }

    /**
     * Получить максимальный id в таблице постов от даты
     *
     * @param DateTime $date
     * @return int
     */
    public function getMaxIdByDate(DateTime $date)
    {
        $expr = new Query\Expr();
        return (int)$this
            ->createQueryBuilder('f')
            ->select('MAX(f.id) AS max_id')
            ->where($expr->lte('f.created', $expr->literal($date->format('Y-m-d H:i:s'))))
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Получить минимальный id в таблице постов за дату
     *
     * @param DateTime $date
     * @return int
     */
    public function getMinIdByDate(DateTime $date)
    {
        $expr = new Query\Expr();
        return (int)$this
            ->createQueryBuilder('f')
            ->select('MIN(f.id) AS max_id')
            ->where($expr->gte('f.created', $expr->literal($date->format('Y-m-d H:i:s'))))
            ->getQuery()
            ->getSingleScalarResult();
    }
}
