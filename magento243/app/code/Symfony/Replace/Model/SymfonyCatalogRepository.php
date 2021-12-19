<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Symfony\Replace\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\ValidatorException;
use Symfony\Replace\Api\SymfonyCatalogRepositoryInterface;

class SymfonyCatalogRepository implements SymfonyCatalogRepositoryInterface
{

    /**
     * @var ProductRepositoryInterface
     */
    private $repository;

    public function __construct(
        ProductRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * @param string $sku
     * @param string $newSku
     * @param bool $editMode
     * @param int|null $storeId
     * @param bool $forceReload
     * @return bool
     * @throws StateException
     */
    public function replace($sku, $newSku, $editMode = false, $storeId = null, $forceReload = false)
    {
        try {
            $product = $this->repository->get($sku, $editMode, $storeId, $forceReload);
            $this->repository->save($product->setSku($newSku));
        } catch (\Exception $e) {
            return false;
//            throw new StateException(
//                __('The "%1" product couldn\'t be changed.', $sku),
//                $e
//            );
        }

        return true;
    }
}
