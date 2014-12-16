<?php

namespace Feed\Service\Feed\GetList\Adapter\Doctrine;

use Doctrine\ORM\Query;
use Feed\Entity\Feed;
use Feed\Service\Feed\GetList\Entity\Collection;
use Feed\Service\Feed\GetList\Adapter\SelectInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Лента постов / Объект выборки для Doctrine ORM
 *
 * Class Select
 * @package Application\Service\Feed\Connector
 */
class Select implements SelectInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var array
     */
    private $useTags = [];

    /**
     * @var bool
     */
    private $useProfile = false;

    /**
     * Constructor
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Получить новый запрос
     *
     * @return $this
     */
    public function init()
    {
        $this->queryBuilder = $this->entityManager->createQueryBuilder();
        $this->queryBuilder
            ->select('partial f.{id}')
            ->from('Feed\Entity\Feed', 'f')
            ->join('f.member', 'm');

        $this->useTags    = [];
        $this->useProfile = false;

        return $this;
    }

    /**
     * Подключить теги
     *
     * @param string $alias
     * @return $this
     */
    public function joinTags($alias)
    {
        if (!isset($this->useTags[$alias])) {
            $this->queryBuilder
                ->join(
                    'Feed\Entity\FeedTag',
                    $alias,
                    'WITH',
                    sprintf('f.id = %s.feedId', $alias)
                )
                ->groupBy('f.id');

            $this->useTags[$alias] = true;
        }

        return $this;
    }

    /**
     * Подключить профиль
     *
     * @return $this
     */
    public function joinProfile()
    {
        if (!$this->useProfile) {
            $this->queryBuilder->join('m.profile', 'p')->groupBy('f.id');
            $this->useProfile = true;
        }

        return $this;
    }

    /**
     * Добавить условие выборки
     *
     * @param mixed $criteria
     * @return $this
     */
    public function where($criteria)
    {
        $this->queryBuilder->andWhere($criteria);
        return $this;
    }

    /**
     * Добавить условие сортировки
     *
     * @param array $order
     * @return $this
     */
    public function order(array $order)
    {
        foreach ($order as $column => $type) {
            $this->queryBuilder->addOrderBy($column, $type);
        }

        return $this;
    }

    /**
     * Задать ограничение по количеству записей
     *
     * @param int $limit
     * @return $this
     */
    public function limit($limit)
    {
        $this->queryBuilder->setMaxResults($limit);
        return $this;
    }

    /**
     * Задать смещение
     *
     * @param int $offset
     * @return $this
     */
    public function offset($offset)
    {
        $this->queryBuilder->setFirstResult($offset);
        return $this;
    }

    /**
     * Вернуть коллекцию объектов
     *
     * @return Collection
     */
    public function getCollection()
    {
        $query = $this->queryBuilder->getQuery();
        $query->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true);

        $result = $query->getResult();
        $collection = new Collection();

        foreach ($result as $entity) {
            /** @var Feed $entity */
            $collection->attach($entity);
        }

        return $collection;
    }
}
