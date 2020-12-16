<?php

namespace Magepow\Wallpaper\Model\ResourceModel\Wallpaper;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    protected $_idFieldName = 'id';

    protected function _construct()
    {
        // Model + Resource Model
        $this->_init('Magepow\Wallpaper\Model\Wallpaper', 'Magepow\Wallpaper\Model\ResourceModel\Wallpaper');
    }

}
