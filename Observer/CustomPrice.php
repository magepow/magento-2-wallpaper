<?php
/**
 * CustomPrice
 *
 * @copyright Copyright © 2020 Magepow. All rights reserved.
 * @author    @copyright Copyright (c) 2014 Magepow (<https://www.magepow.com>)
 * @license <https://www.magepow.com/license-agreement.html>
 * @Author: magepow<support@magepow.com>
 * @github: <https://github.com/magepow>
 */

namespace Magepow\Wallpaper\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Http\Context as customerSession;
use Magepow\Wallpaper\Helper\Data;
class CustomPrice implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;
    protected $helper;
    protected $wallpaper;


    public function __construct( RequestInterface $request,customerSession $session, Data $helper){
        $this->request = $request;
        $this->helper = $helper;
        $this->customerSession = $session;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {

        if ($this->helper->getConfigModule("general/enabled")){
             $wallAttr = $this->request->getParam('wallpaper_attribute');
             if (!$wallAttr == 1) return;

            $wallpaper_attribute = $this->request->getParam('wallpaper_attribute');
            $widthVal = $this->request->getParam('widthVal');
            $heightVal = $this->request->getParam('heightVal');
            $unit_addcart = $this->request->getParam('unit_addcart');
            $category_a = $this->request->getParam('category_a');
            if ($widthVal == '' || $category_a == '' || $heightVal == '' || $unit_addcart == '' ){
                $price = $this->helper->getConfigModule("general/sample");

            }else{
                switch ($unit_addcart){
                    case "cm" : $price = $widthVal * $heightVal * $category_a * 0.0001; break;
                    case "inch" : $price = $widthVal * $heightVal * $category_a * 0.000645; break;
                    case "ft" : $price = $widthVal * $heightVal * $category_a * 0.092903; break;
                }
            }
            $item = $observer->getEvent()->getData('quote_item');
            $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
            $item->setCustomPrice($price);
            $item->setOriginalCustomPrice($price);
            $item->getProduct()->setIsSuperMode(true);

        }
    }

}
