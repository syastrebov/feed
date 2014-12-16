<?php

namespace Feed\Entity;

use Application\Entity\Tag;
use Application\Entity\Entry;
use BmComment\Service\Comment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Like\Service\Like;
use Member\Entity\Member;
use BmCommon\Interfaces\Extractable;
use BmCommon\Traits\ManipulationVars;
use DateTime;
use Exception;

/**
 * @ORM\Entity(repositoryClass="Feed\Repository\Feed")
 * @ORM\Table(name="feed")
 */
class Feed implements Extractable
{
    use ManipulationVars;

    const FEED_ENTITY_TYPE_SIMPLE      = 1;
    const FEED_ENTITY_TYPE_SIMPLE_GOAL = 2;
    const FEED_ENTITY_SYSTEM_PUB       = 3;

    const FEED_LIKE = Like::TYPE_SOCIAL_FEED_LIKE;
    const FEED_HELPFUL = Like::TYPE_SOCIAL_FEED_HELPFUL;
    const FEED_COMMENT = Comment::TYPE_ID_SOCIAL_FEED_ENTITY;


    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /**
     * @var Tag[]
     * @ORM\ManyToMany(targetEntity="Application\Entity\Tag", inversedBy="feedList", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="feed_tag",
     *   joinColumns={@ORM\JoinColumn(name="feed_id", referencedColumnName="id", onDelete="SET NULL")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     */
    protected $tags;

    /**
     * @var FeedFile[]
     * @ORM\OneToMany(targetEntity="FeedFile", mappedBy="feed", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="feed_id", onDelete="SET NULL")
     */
    protected $files;

    /**
     * @var string
     * @ORM\Column(name="title", type="string")
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(name="text", type="string")
     */
    protected $text;

    /**
     * @var int
     * @ORM\Column(name="member_id", type="integer")
     */
    protected $memberId;

    /**
     * @var Member
     * @ORM\ManyToOne(targetEntity="Member\Entity\Member")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    protected $member;

    /**
     * @var \DateTime
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     * @var \DateTime
     */
    protected $updated;

    /**
     * @var int
     * @ORM\Column(name="access", type="integer")
     */
    protected $access;

    /**
     * @var int
     * @ORM\Column(name="type_id", type="integer")
     */
    protected $typeId;

    /**
     * @var int
     * @ORM\Column(name="entry_id", type="integer")
     */
    protected $entryId;

    /**
     * @var Entry
     * @ORM\OneToOne(targetEntity="Application\Entity\Entry")
     * @ORM\JoinColumn(name="entry_id", referencedColumnName="id")
     */
    protected $entry;

    /**
     * @ORM\Column(name="popularity_count", type="integer", options={"default"=0})
     */
    protected $popularityCount = 0;

    /**
     * @ORM\Column(name="like_count", type="integer", options={"default"=0})
     */
    protected $likeCount = 0;

    /**
     * @ORM\Column(name="helpful_count", type="integer", options={"default"=0})
     */
    protected $helpfulCount = 0;

    /**
     * @ORM\Column(name="comment_count", type="integer", options={"default"=0})
     */
    protected $commentCount = 0;

    /**
     * @ORM\Column(name="is_deleted", type="smallint", options={"default"=0})
     */
    protected $isDeleted = false;

    /**
     * Лайкнул ли запись пользователь
     *
     * @var bool
     */
    protected $isLiked = false;

    /**
     * Полезная ли запись для пользователя
     *
     * @var bool
     */
    protected $isHelpful = false;

    /**
     * Комментарии данного поста
     *
     * @var array
     */
    protected $comments = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags  = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

    /**
     * Id объекта
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Обнулить файлы
     *
     * @return $this
     */
    public function resetFiles()
    {
        $this->files = new ArrayCollection();
        return $this;
    }

    /**
     * Привязать файл к посту
     *
     * @param FeedFile $file
     * @return $this
     * @throws Exception
     */
    public function addFile(FeedFile $file)
    {
        if ($this->hasFile($file)) {
            throw new Exception('Такой файл уже был добавлен');
        }

        $this->files->add($file);
        return $this;
    }

    /**
     * Есть ли уже такой файл
     *
     * @param FeedFile $file
     * @return bool
     */
    public function hasFile(FeedFile $file)
    {
        $checkTag = $this->files->filter(
            function ($existFile) use ($file) {
                /** @var FeedFile $existFile */
                return $file->getId() == $existFile->getId();
            }
        );

        return count($checkTag) > 0;
    }

    /**
     * @return FeedFile[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param ArrayCollection $files
     *
     * @return $this
     */
    public function setFiles($files)
    {
        $this->files = $files;
        return $this;
    }

    /**
     * Обнулить теги
     *
     * @return $this
     */
    public function resetTags()
    {
        $this->tags = new ArrayCollection();
        return $this;
    }

    /**
     * Привязать тег к посту
     *
     * @param Tag $tag
     * @return $this
     * @throws Exception
     */
    public function addTag(Tag $tag)
    {
        if ($this->hasTag($tag)) {
            throw new Exception('Такой тег уже был добавлен');
        }

        $this->tags->add($tag);
        return $this;
    }

    /**
     * Есть ли уже такой тег
     *
     * @param Tag $tag
     * @return bool
     */
    public function hasTag(Tag $tag)
    {
        $checkTag = $this->tags->filter(
            function ($existTag) use ($tag) {
                /** @var Tag $existTag */
                return $tag->getId() == $existTag->getId();
            }
        );

        return count($checkTag) > 0;
    }

    /**
     * @return Tag[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return integer
     */
    public function getMemberId()
    {
        return $this->memberId;
    }

    /**
     * @param int $memberId
     * @return $this
     */
    public function setMemberId($memberId)
    {
        $this->memberId = $memberId;
        return $this;
    }

    /**
     * @param Member $member
     * @return $this
     */
    public function setMember(Member $member)
    {
        $this->member = $member;
        return $this;
    }

    /**
     * @return Member
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Изменить значение некоторых полей на 1
     * В основном это дессериализоанные поля
     *
     * @param      $valueName
     * @param bool $direction
     */
    public function changeValue($valueName, $direction = true)
    {
        $availableValues = ['popularityCount', 'likeCount', 'helpfulCount', 'commentCount'];
        if (in_array($valueName, $availableValues, true)) {
            $this->$valueName += $direction ? 1 : -1;
        }
    }

    /**
     * @param Entry $entry
     * @return $this
     */
    public function setUserEntry(Entry $entry)
    {
        $this->entry = $entry;
        $this->entryId = $entry->getId();
        return $this;
    }

    /**
     * @return Entry
     */
    public function getEntry()
    {
        return $this->entry;
    }

    /**
     * Задать id связанной цели
     *
     * @param int $entryId
     * @return $this
     */
    public function setEntryId($entryId)
    {
        $this->entryId = (int)$entryId;
        return $this;
    }

    /**
     * @return int
     */
    public function getEntryId()
    {
        return $this->entryId;
    }

    /**
     * @return $this
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return $this
     * @param int $access
     */
    public function setAccess($access)
    {
        $this->access = $access;
        return $this;
    }

    /**
     * @return int
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     *
     * @param DateTime $created
     * @return $this
     */
    public function setCreated(DateTime $created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return int
     */
    public function getCreatedTimestamp()
    {
        return $this->created->getTimestamp();
    }

    /**
     * @param DateTime $updated
     * @return $this
     */
    public function setUpdated(DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * Количество полезного
     *
     * @param int $count
     * @return $this
     */
    public function setHelpfulCount($count)
    {
        $this->helpfulCount = (int)$count;
        return $this;
    }

    /**
     * Количество комментов
     *
     * @param int $commentCount
     * @return $this
     */
    public function setCommentCount($commentCount)
    {
        $this->commentCount = $commentCount;
        return $this;
    }

    /**
     * Количество полезного
     *
     * @return int
     */
    public function getHelpfulCount()
    {
        return $this->helpfulCount;
    }

    /**
     * @return mixed
     */
    public function getCommentCount()
    {
        return $this->commentCount;
    }

    /**
     * @return mixed
     */
    public function getLikeCount()
    {
        return $this->likeCount;
    }

    /**
     * @param boolean $isHelpful
     */
    public function setIsHelpful($isHelpful)
    {
        $this->isHelpful = $isHelpful;
    }

    /**
     * @param boolean $isLiked
     */
    public function setIsLiked($isLiked)
    {
        $this->isLiked = $isLiked;
    }

    /**
     * @return boolean
     */
    public function getIsHelpful()
    {
        return $this->isHelpful;
    }

    /**
     * @return boolean
     */
    public function getIsLiked()
    {
        return $this->isLiked;
    }

    /**
     * @param array $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return array
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return bool
     */
    public function getIsDeleted()
    {
        return (bool)$this->isDeleted;
    }

    /**
     * @param mixed $isDeleted
     * @return $this
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = (bool)$isDeleted;
        return $this;
    }

    /**
     * Метод должен отдать массив с
     * переменными которые будут отдаваться по json
     *
     * @return array
     */
    public function getFilterVars()
    {
        return [
            'default' => [
                'id',
                'memberId',
                'member',
                'title',
                'text',
                'entryId',
                'created',
                'access',
                'typeId',
                'tags',
                'helpfulCount',
                'likeCount',
                'commentCount',
                'createdTimestamp',
                'files',
                'isLiked',
                'isHelpful',
                'comments',
                'isDeleted',
            ],
            'profile' => [
                'id',
                'memberId',
                'member',
                'title',
                'text',
                'entryId',
                'created',
                'access',
                'typeId',
                'tags',
                'helpfulCount',
                'likeCount',
                'commentCount',
                'createdTimestamp',
                'files',
                'isLiked',
                'isHelpful',
                'comments',
                'isDeleted',
            ],
        ];
    }
}
