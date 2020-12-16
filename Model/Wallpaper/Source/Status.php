<?php
/**
 * Status
 *
 * @copyright Copyright © 2020 Magepow. All rights reserved.
 * @author    @copyright Copyright (c) 2014 Magepow (<https://www.magepow.com>)
 * @license <https://www.magepow.com/license-agreement.html>
 * @Author: magepow<support@magepow.com>
 * @github: <https://github.com/magepow>
 */

namespace Magepow\Wallpaper\Model\Wallpaper\Source;
use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{

    protected $wallpaper;

    public function __construct(\Magepow\Wallpaper\Model\Wallpaper $wallpaper)
    {
        $this->wallpaper = $wallpaper;
    }
    /**
     * Get status options
     */
    public function toOptionArray()
    {
        $availableOptions = $this->wallpaper->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
