<?php

namespace Feed\Entity;

use BmCommon\Interfaces\Extractable;
use BmCommon\Traits\ManipulationVars;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Feed\Repository\FeedFile")
 * @ORM\Table(name="feed_file")
 */
class FeedFile implements Extractable
{
    const TYPE_PHOTO = 1;

    use ManipulationVars;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     * @var int
     */
    protected $id;

    /**
     * @var \Feed\Entity\Feed
     * @ORM\ManyToOne(targetEntity="Feed", inversedBy="files")
     * @ORM\JoinColumn(name="feed_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $feed;

    /**
     * @var string
     * @ORM\Column(name="file_path", type="string")
     */
    protected $path;

    /**
     * @var int
     * @ORM\Column(name="file_type_id", type="integer")
     */
    protected $typeId;

    /**
     * @var int
     * @ORM\Column(name="feed_id", type="integer")
     */
    protected $feedId;

    /**
     * @var \DateTime
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @param mixed $created
     *
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \Feed\Entity\Feed $feed
     *
     * @return $this
     */
    public function setFeed($feed)
    {
        $this->feed = $feed;
        return $this;
    }

    /**
     * @return \Feed\Entity\Feed
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * @param int $feedId
     *
     * @return $this
     */
    public function setFeedId($feedId)
    {
        $this->feedId = $feedId;
        return $this;
    }

    /**
     * @return int
     */
    public function getFeedId()
    {
        return $this->feedId;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param int $typeId
     *
     * @return $this
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;
        return $this;
    }

    /**
     * @return int
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Метод должен отдать массив с переменными которые будут отдаваться по json
     * @return array
     */
    public function getFilterVars()
    {
        return [
            'default' => [
                'id',
                'path',
                'type_id' => 'typeId',
            ],
            'profile' => [
                'id',
                'path',
                'typeId',
            ],
        ];
    }
}
