<?php
/**
 * InlineEdit
 *
 * @copyright Copyright © 2020 Magepow. All rights reserved.
 * @author    @copyright Copyright (c) 2014 Magepow (<https://www.magepow.com>)
 * @license <https://www.magepow.com/license-agreement.html>
 * @Author: magepow<support@magepow.com>
 * @github: <https://github.com/magepow>
 */

namespace Magepow\Wallpaper\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magepow\Wallpaper\Model\WallpaperFactory;
class InlineEdit extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Magepow_Wallpaper::save';

    protected $wallpaperFactory;
    protected $jsonFactory;

    public function __construct(
        Context $context,
        WallpaperFactory $wallpaperFactory,
        JsonFactory $jsonFactory
    )
    {
        parent::__construct($context);
        $this->wallpaperFactory = $wallpaperFactory;
        $this->jsonFactory = $jsonFactory;
    }

    public function execute()
    {
        // Init result Json
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        // Get POST data
        $postItems = $this->getRequest()->getParam('items', []);

        // Check request
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        // Save data to database
        foreach (array_keys($postItems) as $wallpaperId) {
            try {
                $wallpaper = $this->wallpaperFactory->create();
                $wallpaper->load($wallpaperId);
                $wallpaper->setData($postItems[(string)$wallpaperId]);
                $wallpaper->save();
            } catch (\Exception $e) {
                $messages[] = __('Something went wrong while saving the image.');
                $error = true;
            }
        }

        // Return result Json
        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}
