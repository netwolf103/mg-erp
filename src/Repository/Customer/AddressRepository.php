<?php

namespace App\Repository\Customer;

use App\Entity\Customer\Address;

use App\Repository\AbstractRepository;

/**
 * Repository for customer address.
 * 
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array $criteria, array $orderBy = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class AddressRepository extends AbstractRepository
{
    protected $entityClass = Address::class;
}
