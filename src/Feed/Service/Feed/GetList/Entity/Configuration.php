<?php

namespace Feed\Service\Feed\GetList\Entity;

use BmCommon\Traits\ConfigurationTrait;

/**
 * Лента постов / Конфигурация ленты
 *
 * Class Configuration
 * @package Application\Service\Feed\Entity
 */
class Configuration
{
    use ConfigurationTrait;

    /**
     * @var int
     */
    private $ownerIds = [];

    /**
     * @var string
     */
    private $city;

    /**
     * @var int
     */
    private $tabType;

    /**
     * @var int
     */
    private $productTags = [];

    /**
     * @var int
     */
    private $nicheTags = [];

    /**
     * @var int|null
     */
    private $moneyMin;

    /**
     * @var int|null
     */
    private $moneyMax;

    /**
     * @var bool
     */
    private $sortUseful = false;

    /**
     * @var int|null
     */
    private $limit;

    /**
     * @var int|null
     */
    private $offset;

    /**
     * @var bool
     */
    private $isAdmin = false;

    /**
     * @var int|null
     */
    private $minId;

    /**
     * @var int|null
     */
    private $maxId;

    /**
     * Создать новый экземпляр
     *
     * @return Configuration
     */
    public static function create()
    {
        return new Configuration();
    }

    /**
     * Constructor
     */
    public function __construct() {}

    /**
     * Задать список пользователей, у которых брать посты
     *
     * @param array $ownerIds
     * @return $this
     */
    public function setOwnerIds(array $ownerIds)
    {
        $this->ownerIds = $this->setIntArrayProperty($ownerIds, 'Передан недопустимый id пользователя ');
        return $this;
    }

    /**
     * Чьих пользователей можно выводить посты
     *
     * @return array
     */
    public function getOwnerIds()
    {
        return $this->ownerIds;
    }

    /**
     * Задать город
     *
     * @param int $value
     * @return $this
     */
    public function setCity($value)
    {
        $this->city = $this->setIntProperty($value, 'Передан недопустимый город');
        return $this;
    }

    /**
     * Выбранный город
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Задать раздел
     *
     * @param int $value
     * @return $this
     */
    public function setTabType($value)
    {
        $this->tabType = $this->setIntProperty($value, 'Передан недопустимый раздел');
        return $this;
    }

    /**
     * Выбранный раздел
     *
     * @return int
     */
    public function getTabType()
    {
        return $this->tabType;
    }

    /**
     * Задать теги продуктов
     *
     * @param array $productTags
     * @return $this
     */
    public function setProductTags(array $productTags)
    {
        $this->productTags = $this->setIntArrayProperty($productTags, 'Передан недопустимый тег курса');
        return $this;
    }

    /**
     * Выбранные теги продуктов
     *
     * @return array
     */
    public function getProductTags()
    {
        return $this->productTags;
    }

    /**
     * Задать теги интересов
     *
     * @param array $nicheTags
     * @return $this
     */
    public function setNicheTags(array $nicheTags)
    {
        $this->nicheTags = $this->setIntArrayProperty($nicheTags, 'Передан недопустимый тег нишы');
        return $this;
    }

    /**
     * Выбранные теги интересов
     *
     * @return array
     */
    public function getNicheTags()
    {
        return $this->nicheTags;
    }

    /**
     * Задать минимальную границу дохода
     *
     * @param int $value
     * @return $this
     */
    public function setMoneyMin($value)
    {
        $this->moneyMin = $this->setIntProperty($value, 'Передан недопустимая минимальная граница дохода');
        return $this;
    }

    /**
     * Минимальная граница дохода
     *
     * @return int
     */
    public function getMoneyMin()
    {
        return $this->moneyMin;
    }

    /**
     * Задать масимальную границу дохода
     *
     * @param int $value
     * @return $this
     */
    public function setMoneyMax($value)
    {
        $this->moneyMax = $this->setIntProperty($value, 'Передан недопустимая масимальная граница дохода');
        return $this;
    }

    /**
     * Максимальная граница дохода
     *
     * @return int
     */
    public function getMoneyMax()
    {
        return $this->moneyMax;
    }

    /**
     * Сортировать по полезности
     *
     * @param bool $value
     * @return $this
     */
    public function setSortUseful($value)
    {
        $this->sortUseful = (bool)$value;
        return $this;
    }

    /**
     * Сортировать по полезности
     *
     * @return bool
     */
    public function isSortUseful()
    {
        return $this->sortUseful;
    }

    /**
     * Задать максимальное количество записей в выборке
     *
     * @param int $value
     * @return $this
     */
    public function setLimit($value)
    {
        $this->limit = $this->setIntProperty($value, 'Передано недопустимое максимальное количество записей в выборке');
        return $this;
    }

    /**
     * Максимальное количество записей в выборке
     *
     * @return int|null
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Задать смещение в списке
     *
     * @param int $value
     * @return $this
     */
    public function setOffset($value)
    {
        $this->offset = $value === 0 ? 0 : $this->setIntProperty($value, 'Передано недопустимое смещение в списке');
        return $this;
    }

    /**
     * Смещение в списке
     *
     * @return int|null
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Админ ли пользователь
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Задать админ ли пользователь или нет
     *
     * @param bool $isAdmin
     * @return $this
     */
    public function setAsAdmin($isAdmin)
    {
        $this->isAdmin = (bool)$isAdmin;
        return $this;
    }

    /**
     * Ограничение по минимальному id поста
     *
     * @param int $value
     * @return $this
     */
    public function setMinId($value)
    {
        $this->minId = $value === 0 ? 0 : $this->setIntProperty($value, 'Передан недопустимый минимальный id поста');
        return $this;
    }

    /**
     * Ограничение по минимальному id поста
     * Используется для вывода ленты за период (например, за последние сутки)
     *
     * @return int|null
     */
    public function getMinId()
    {
        return $this->minId;
    }

    /**
     * Ограничение по максимальному id поста
     *
     * @param int $value
     * @return $this
     */
    public function setMaxId($value)
    {
        $this->maxId = $value === 0 ? 0 : $this->setIntProperty($value, 'Передан недопустимый максимальный id поста');
        return $this;
    }

    /**
     * Ограничение по максимальному id поста
     * Используется для правильного постраничного вывода ленты (с учетом добавления новых постов пока читаешь ленту)
     *
     * @return int|null
     */
    public function getMaxId()
    {
        return $this->maxId;
    }
}
