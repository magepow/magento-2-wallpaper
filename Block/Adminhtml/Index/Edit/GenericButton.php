<?php

namespace Magepow\Wallpaper\Block\Adminhtml\Index\Edit;

use Magento\Backend\Block\Widget\Context;

class GenericButton
{

    protected $context;
    protected $wallpaperFactory;

    public function __construct(
        Context $context,
        \Magepow\Wallpaper\Model\WallpaperFactory $wallpaperFactory
    )
    {
        $this->context = $context;
        $this->wallpaperFactory = $wallpaperFactory;
    }

    /**
     * Return Test ID
     */
    public function getWallpaperId()
    {
        $id = $this->context->getRequest()->getParam('id');
        $wallpaper = $this->wallpaperFactory->create()->load($id);
        if ($wallpaper->getId())
            return $id;
        return null;
    }

    /**
     * Generate url by route and parameters
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
