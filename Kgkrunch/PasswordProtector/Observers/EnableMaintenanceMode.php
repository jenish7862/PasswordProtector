<?php
namespace Kgkrunch\PasswordProtector\Observers;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\ObjectManager;

class EnableMaintenanceMode implements ObserverInterface
{

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
		$response = $observer->getResponse();
		$helper = ObjectManager::getInstance()->get('\Kgkrunch\PasswordProtector\Helper\Data');
		 if ($helper->isMaintenanceMode() && !ObjectManager::getInstance()->get('Magento\Framework\Stdlib\CookieManagerInterface')->getCookie('passwordportector')) {
			$passwordprocterhelper = $helper->getPasswordProtectorPageBlock();
            $response->setBody($passwordprocterhelper->toHtml());
		 }
    }
}