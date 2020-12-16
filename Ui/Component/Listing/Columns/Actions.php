<?php
/**
 * Actions
 *
 * @copyright Copyright © 2020 Magepow. All rights reserved.
 * @author    @copyright Copyright (c) 2014 Magepow (<https://www.magepow.com>)
 * @license <https://www.magepow.com/license-agreement.html>
 * @Author: magepow<support@magepow.com>
 * @github: <https://github.com/magepow>
 */

namespace Magepow\Wallpaper\Ui\Component\Listing\Columns;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\UrlInterface;

class Actions extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * Url path
     */
    const WALLPAPER_URL_PATH_EDIT = 'wallpaper/index/edit';
    const WALLPAPER_URL_PATH_DELETE = 'wallpaper/index/delete';

    protected $urlBuilder;

    private $editUrl;

    public function __construct(
    ContextInterface $context,
    UiComponentFactory $uiComponentFactory,
    UrlInterface $urlBuilder,
    array $components = [],
    array $data = [],
    $editUrl = self::WALLPAPER_URL_PATH_EDIT
) {
    $this->urlBuilder = $urlBuilder;
    $this->editUrl = $editUrl;
    parent::__construct($context, $uiComponentFactory, $components, $data);
}

    /**
     * Prepare Data Source
     */
    public function prepareDataSource(array $dataSource)
{
    if (isset($dataSource['data']['items'])) {
        // Get column name
        $fieldName = $this->getData('name');

        foreach ($dataSource['data']['items'] as & $item) {
            if (isset($item['id'])) {
                // Add Edit link
                $item[$fieldName]['edit'] = [
                    'href' => $this->urlBuilder->getUrl($this->editUrl, ['id' => $item['id']]),
                    'label' => __('Edit')
                ];

                // Add Delete link
                $item[$fieldName]['delete'] = [
                    'href' => $this->urlBuilder->getUrl(self::WALLPAPER_URL_PATH_DELETE, ['id' => $item['id']]),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete ${ $.$data.image }'),
                        'message' => __('Are you sure you wan\'t to delete this record?')
                    ]
                ];
            }
        }
    }

    return $dataSource;
}

}
