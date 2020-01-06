<?php
namespace App\Message\Sales\Order;

/**
 * Message for order comment push.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CommentPush
{
	/**
	 * The comment id.
	 * 
	 * @var int
	 */
    private $commentId;

    /**
     * Comment init.
     * 
     * @param int $commentId
     */
    public function __construct(int $commentId)
    {
        $this->commentId = $commentId;
    }

    /**
     * Get comment id.
     * 
     * @return int
     */
    public function getCommentId(): int
    {
        return $this->commentId;
    }
}