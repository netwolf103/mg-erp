<?php
namespace App\MessageHandler\Catalog\Category\Product;

use App\MessageHandler\MessageHandlerAbstract;

use App\Entity\Product\Option\Dropdown;
use App\Entity\Product\Stock\Alert as ProductAlert;
use App\Message\Catalog\Category\Product\Alert;

/**
 * Message handler for product stock alert.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class AlertHandler extends MessageHandlerAbstract
{
	/**
	 * Product handler.
	 * 
	 * @param  Alert $alert
	 * @return void
	 */
    public function __invoke(Alert $alert)
    {
        $optionId = $alert->getOptionId();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->clear();

        $dropdownEntity = $entityManager->getRepository(Dropdown::class)->find($optionId);

        if (!$dropdownEntity) {
            return;
        }

        $alertEntity = $entityManager->getRepository(ProductAlert::class)->findOneBy(['parent' => $optionId]);
        if (!$alertEntity) {
            $alertEntity = new ProductAlert;
        }

        $alertEntity->setParent($dropdownEntity);
        $alertEntity->setProduct($dropdownEntity->getParent()->getProduct());
        $alertEntity->setSku($dropdownEntity->getParent()->getProduct()->getSku());
        $alertEntity->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($alertEntity);
        $entityManager->flush();

        $this->success(
            sprintf("Option id #%s, Done\r\n", $dropdownEntity->getId())
        );                        
    }      
}