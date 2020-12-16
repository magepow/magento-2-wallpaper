<?php
/**
 * Unit
 *
 * @copyright Copyright © 2020 Magepow. All rights reserved.
 * @author    @copyright Copyright (c) 2014 Magepow (<https://www.magepow.com>)
 * @license <https://www.magepow.com/license-agreement.html>
 * @Author: magepow<support@magepow.com>
 * @github: <https://github.com/magepow>
 */

namespace Magepow\Wallpaper\Model\System\Config;


class Unit
{
    const UNIT_CM = 'cm';
    const UNIT_INCH = 'inch';
    const UNIT_FOOT = 'ft';


    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'label' => __('Cm'),
                'value' => self::UNIT_CM
            ],
            [
                'label' => __('Inch'),
                'value' => self::UNIT_INCH
            ],
            [
                'label' => __('Foot'),
                'value' => self::UNIT_FOOT
            ]
        ];

        return $options;
    }
}
