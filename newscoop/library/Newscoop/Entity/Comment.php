<?php
/**
 * @package Newscoop
 * @copyright 2011 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl.txt
 */

namespace Newscoop\Entity;

use DateTime,
    InvalidArgumentException,
    Newscoop\Entity\Comment\Commenter,
    Newscoop\Entity\Language,
    Newscoop\Entity\Publication;

/**
 * Comment entity
 * @entity
 * @table(name="Comment")
 * @entity(repositoryClass="Newscoop\Entity\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @var string to code mapper for status
     */
    static $status_enum = array(
        'approved',
        'pending',
        'hidden',
        'deleted'
    );

    /**
     * @id @generatedValue
     * @column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @manyToOne(targetEntity="Newscoop\Entity\Comment\Commenter")
     * @joinColumn(name="fk_comment_commenter_id", referencedColumnName="id")
     * @var Newscoop\Entity\Comment\Commenter
     */
    private $commenter;

    /**
     * @manyToOne(targetEntity="Publication")
     * @joinColumn(name="fk_forum_id", referencedColumnName="Id")
     * @var Newscoop\Entity\Publication
     */
    private $forum;

    /**
     * @manyToOne(targetEntity="Comment")
     * @joinColumn(name="fk_parent_id", referencedColumnName="id")
     * @var Newscoop\Entity\Comment
     */
    private $parent;

    /**
     * @manyToOne(targetEntity="Article")
     * @joinColumn(name="fk_thread_id", referencedColumnName="Number")
     * @var Newscoop\Entity\Article
     */
    private $thread;

    /**
     * @manyToOne(targetEntity="Language")
     * @joinColumn(name="fk_language_id", referencedColumnName="Id")
     * @var Newscoop\Entity\Language
     */
    private $language;

    /**
     * @column(length=140)
     * @var string
     */
    private $subject;

    /**
     * @column
     * @var text
     */
    private $message;

    /**
     * @column(length=4)
     * @var int
     */
    private $thread_level;

    /**
     * @column(length=4)
     * @var int
     */
    private $thread_order;

    /**
     * @column(length=2)
     * @var int
     */
    private $status;

    /**
     * @column(length=39)
     * @var int
     */
    private $ip;

    /**
     * @column(type="datetime")
     * @var DateTime
     */
    private $time_created;

    /*
     * @column(type="datetime")
     * @var DateTime
     */
    private $time_updated;

    /**
     * @column(length=4)
     * @var int
     */
    private $likes;

    /**
     * @column(length=4)
     * @var int
     */
    private $dislikes;

    /**
     * Set id
     *
     * @param int $p_id
     * @return Newscoop\Entity\Comment
     */
    public function setId($p_id)
    {
        return $this->id;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set timecreated
     *
     * @param DateTime $p_datetime
     * @return Newscoop\Entity\Comment
     */
    public function setTimeCreated(DateTime $p_datetime)
    {
        $this->time_created = $p_datetime;
        // return this for chaining mechanism
        return $this;
    }

    /**
     * Get creation time.
     *
     * @return DateTime
     */
    public function getTimeCreated()
    {
        return $this->time_created;
    }

    /**
     * Set time updated
     *
     * @param DateTime $p_datetime
     * @return Newscoop\Entity\Comment
     */
    public function setTimeUpdated(DateTime $p_datetime)
    {
        $this->time_updated = $p_datetime;
        // return this for chaining mechanism
        return $this;
    }

    /**
     * Get creation time.
     *
     * @return DateTime
     */
    public function getTimeUpdated()
    {
        return $this->time_updated;
    }

    /**
     * Set comment subject.
     *
     * @param string $p_subject
     * @return Newscoop\Entity\Comment
     */
    public function setSubject($p_subject)
    {
        $this->subject = (string) $p_subject;
        // return this for chaining mechanism
        return $this;
    }

    /**
     * Get comment subject.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set comment message.
     *
     * @param string $p_message
     * @return Newscoop\Entity\Comment
     */
    public function setMessage($p_message)
    {
        $this->message = (string) $p_message;
        // return this for chaining mechanism
        return $this;
    }

    /**
     * Get comment message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set comment ip address
     *
     * @param string $p_ip
     * @return Newscoop\Entity\Comment
     */
    public function setIp($p_ip){
        // remove subnet & limit to IP_LENGTH
        $ip_array = explode('/', (string) $p_ip);
        $this->ip = substr($ip_array[0], 0, 39);
        // return this for chaining mechanism
        return $this;
    }

    /**
     * Get comment ip address
     *
     * @return string
     */
    public function getIp()
    {
        if (is_numeric($this->ip)) { // try to use old format
            static $max = 0xffffffff; // 2^32
            if($this->ip > 0 && $this->ip < $max)
            {
                return long2ip($this->ip);
            }
        }
        return (string)$this->ip;
    }

    /**
     * Set commenter
     *
     * @param Newscoop\Entity\Comment\Commenter $p_commenter
     * @return Newscoop\Entity\Comment
     */
    public function setCommenter(Commenter $p_commenter)
    {
        $this->commenter = $p_commenter;
        // return this for chaining mechanism
        return $this;
    }

    /**
     * Get commenter
     *
     * @return Newscoop\Entity\Comment\Commenter
     */
    public function getCommenter()
    {
        return $this->commenter;
    }

    /**
     * Get commenter name
     *
     * @return string
     */
    public function getCommenterName()
    {
        return $this->getCommenter()->getName();
    }

    /**
     * Set status string
     *
     * @return Newscoop\Entity\Comment
     */
    public function setStatus($p_status)
    {
        $status_enum = array_flip(self::$status_enum);
        $this->status = $status_enum[$p_status];
        // return this for chaining mechanism
        return $this;
    }

    /**
     * Get status string
     *
     * @return string
     */
    public function getStatus()
    {
        return self::$status_enum[$this->status];
    }

    /**
     * Set forum
     *
     * @return Newscoop\Entity\Comment
     */
    public function setForum(Publication $p_forum)
    {
        $this->forum = $p_forum;
        // return this for chaining mechanism
        return $this;
    }

    /**
     * Get thread
     *
     * @return Newscoop\Entity\Publication
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * Set thread
     *
     * @return Newscoop\Entity\Comment
     */
    public function setThread(Article $p_thread)
    {
        $this->thread = $p_thread;
        // return this for chaining mechanism
        return $this;
    }

    /**
     * Get thread
     *
     * @return Newscoop\Entity\Articles
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * Set thread level
     *
     * @return Newscoop\Entity\Comment
     */
    public function setThreadLevel($p_level)
    {
        $this->thread_level = $p_level;
        // return this for chaining mechanism
        return $this;
    }

    /**
     * Get thread level
     *
     * @return integer
     */
    public function getThreadLevel()
    {
        return $this->thread_level;
    }

    /**
     * Set thread order
     *
     * @return Newscoop\Entity\Comment
     */
    public function setThreadOrder($p_order)
    {
        $this->thread_order = $p_order;
        // return this for chaining mechanism
        return $this;
    }

    /**
     * Get thread level
     *
     * @return integer
     */
    public function getThreadOrder()
    {
        return $this->thread_order;
    }

    /**
     * Set Language
     *
     * @return Newscoop\Entity\Comment
     */
    public function setLanguage(Language $p_language)
    {
        $this->language = $p_language;
        // return this for chaining mechanism
        return $this;
    }

    /**
     * Get Language
     *
     * @return integer
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set Parent
     *
     * @return Newscoop\Entity\Comment
     */
    public function setParent(Comment $p_parent)
    {
        $this->parent = $p_parent;
        // return this for chaining mechanism
        return $this;
    }

    /**
     * Get Parent
     *
     * @return Newscoop\Entity\Comment
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get the likes count
     *
     * @return int
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Get the dislikes count
     *
     * @return int
     */
    public function getDislikes()
    {
        return $this->dislikes;
    }

}
