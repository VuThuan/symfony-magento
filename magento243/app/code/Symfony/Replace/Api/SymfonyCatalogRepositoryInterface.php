<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Symfony\Replace\Api;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\NoSuchEntityException;

interface SymfonyCatalogRepositoryInterface
{
    /**
     * Get info about product by product SKU
     *
     * @param string $sku
     * @param string $newSku
     * @param bool $editMode
     * @param int|null $storeId
     * @param bool $forceReload
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    public function replace($sku, $newSku, $editMode = false, $storeId = null, $forceReload = false);
}
