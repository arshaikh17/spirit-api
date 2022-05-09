<?php

/**
 * @Author: Daniel Forer <daniel@forermedia.com>
 * @Date:   2017-09-26 14:33:49
 * @Last Modified by:   Daniel Forer
 * @Last Modified time: 2018-03-09 11:18:55
 */

namespace Edcoms\SpiritApiBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Edcoms\SpiritApiBundle\Entity\SpiritProductUsageTransactionType;

/**
 * Entity repository for SpiritProduct.
 *
 * @author  Daniel Forer <daniel@forermedia.com>
 */
class SpiritProductUsageRepository extends EntityRepository
{
    /**
     * @return mixed
     */
    public function findSpiritProductUsageCount(SpiritProductUsageTransactionType $transactionType = null) {

        $qb = $this->createQueryBuilder('spu');

        $qb->select('count(spu.id)');

        //if transactionType, get count for that transaction type only.
        if ($transactionType !== null) {
        	$qb->where('spu.spiritProductUsageTransactionType = :transactionType')
        	   ->setParameter('transactionType', $transactionType);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }	
}
