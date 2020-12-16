<?php
/**
 * DataProvider
 *
 * @copyright Copyright © 2020 Magepow. All rights reserved.
 * @author    @copyright Copyright (c) 2014 Magepow (<https://www.magepow.com>)
 * @license <https://www.magepow.com/license-agreement.html>
 * @Author: magepow<support@magepow.com>
 * @github: <https://github.com/magepow>
 */

namespace Magepow\Wallpaper\Model\Wallpaper;

use Magepow\Wallpaper\Model\ResourceModel\Wallpaper\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;
    protected $dataPersistor;
    public $_storeManager;
    protected $loadedData;
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $blockCollectionFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $blockCollectionFactory->create();
        $this->_storeManager=$storeManager;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

        foreach ($items as $page) {
            $data = $page->getData();
            $image = $data['image'];
            if ($image && is_string($image)) {
                $data['images'][0]['name'] = $image;
                $data['images'][0]['url'] = $this->_storeManager->getStore()
                        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
                    . 'wallpaper/images/' . $image;
            }

            $this->loadedData[$page->getId()] = $page->getData();
        }

        $data = $this->dataPersistor->get('magepow_wallpaper');
        if (!empty($data)) {
            $page = $this->collection->getNewEmptyItem();
            $page->setData($data);
            $this->loadedData[$page->getId()] = $page->getData();
            $this->dataPersistor->clear('magepow_wallpaper');
        }

        return $this->loadedData;
    }
}
