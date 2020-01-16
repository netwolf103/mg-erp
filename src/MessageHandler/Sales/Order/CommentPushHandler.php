<?php
namespace App\MessageHandler\Sales\Order;

use App\MessageHandler\MessageHandlerAbstract;

use App\Api\Magento1x\Soap\Sales\OrderSoap;

use App\Entity\Sales\Order\Comment;
use App\Message\Sales\Order\CommentPush;

use App\Traits\ConfigTrait;

/**
 * Message handler for order comment push.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CommentPushHandler extends MessageHandlerAbstract
{
    /**
     * Order comment handler.
     * 
     * @param  CommentPush $commentPush
     * @return void
     */
    public function __invoke(CommentPush $commentPush)
    {
        $commentId = $commentPush->getCommentId();

        $entityManager = $this->getDoctrine()->getManager();

        $commentEntity = $entityManager->getRepository(Comment::class)->find($commentId);

        if (!$commentEntity) {
            return;
        }

        $client = $this->getClient();
        $response = $client->callAddComment(
            $commentEntity->getParent()->getIncrementId(),
            $commentEntity->getStatus(),
            $commentEntity->getComment()
        );

        $this->success(
            sprintf("Comment #%s, Push Done", $commentEntity->getId())
        );               
    }

    /**
     * Return OrderSoap client.
     * 
     * @return OrderSoap
     */
    private function getClient(): OrderSoap
    {
        ConfigTrait::loadConfigs($this->getDoctrine()->getManager());
        $apiParams = ConfigTrait::configMagentoApi();

        $client = new OrderSoap($apiParams['url'] ?? '', $apiParams['user'] ?? '', $apiParams['key'] ?? ''); 

        return $client;        
    }
}