<?php

namespace Feed\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="feed_tag")
 */
class FeedTag
{
    /**
     * @ORM\Id
     * @ORM\Column(name="feed_id", type="integer")
     */
    protected $feedId;

    /**
     * @ORM\Id
     * @ORM\Column(name="tag_id", type="integer")
     */
    protected $tagId;
}