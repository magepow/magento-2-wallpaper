<?php
/**
 * SelfView
 *
 * @copyright Copyright © 2020 Magepow. All rights reserved.
 * @author    @copyright Copyright (c) 2014 Magepow (<https://www.magepow.com>)
 * @license <https://www.magepow.com/license-agreement.html>
 * @Author: magepow<support@magepow.com>
 * @github: <https://github.com/magepow>
 */

namespace Magepow\Wallpaper\Block\Catalog\Product;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\Context;
use Magepow\Wallpaper\Model\ResourceModel\Wallpaper\CollectionFactory;

class SelfView extends AbstractProduct
{
    /**
     * @var \Magepow\Wallpaper\Model\ResourceModel\Wallpaper\CollectionFactory
     */
    protected $wallpaperCollectionFactory;
    /**
     * @var \Magepow\Wallpaper\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Magento\Catalog\Block\Product\View\Attributes
     */
    protected $viewAttributes;
    public function __construct(Context $context,
                                CollectionFactory $wallpaperCollectionFactory,
                                \Magepow\Wallpaper\Helper\Data $_helper,
                                array $data)
    {
        $this->wallpaperCollectionFactory = $wallpaperCollectionFactory;
        $this->_helper = $_helper;

        parent::__construct($context, $data);
    }
    public function getWallpaper(){

        $collection = $this->wallpaperCollectionFactory->create();
        $wallpaper =  $collection->addFieldToFilter('status', ['eq' => true]);
        $this->setData('magepow_wallpaper', $wallpaper);
        $this->setData('mediaURL', $this->_storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'wallpaper/images/');
        return $wallpaper;
    }
}
