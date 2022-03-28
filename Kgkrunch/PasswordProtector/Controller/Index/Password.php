<?php
namespace Kgkrunch\PasswordProtector\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\ObjectManager;
class Password extends \Magento\Framework\App\Action\Action
{
	protected $resultPageFactory;
	
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory,
		\Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
	)
	{
		$this->resultPageFactory = $resultPageFactory;
		$this->_resultFactory = $context->getResultFactory();
		$this->_resultRedirectFactory = $resultRedirectFactory;
		parent::__construct($context);
	}
	

	public function execute()
	{
		try {
		 $password=$this->getRequest()->getParam('password');
      	 $helper = ObjectManager::getInstance()->get('\Kgkrunch\PasswordProtector\Helper\Data');
		  if ($helper->isMaintenanceMode()) {
			  $store_password= $helper->getPassword();
			  if($store_password == $password)
			  {
				$metadata = ObjectManager::getInstance()->create('\Magento\Framework\Stdlib\Cookie\CookieMetadataFactory')->createPublicCookieMetadata()->setDuration('86400')->setPath('/');
				ObjectManager::getInstance()->create('Magento\Framework\Stdlib\CookieManagerInterface')->setPublicCookie('passwordportector','1',$metadata);
				echo "success";
				die;
			  }else
			  {
				  echo "Password incorrect, please try again.";
				  die;
			  }
		  }
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
		
		
	}
}