<?php
namespace Kgkrunch\PasswordProtector\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\View\LayoutInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Data extends AbstractHelper {
    const USER_CONFIG_STATE_LABEL = 'configState';
    const USER_CONFIG_STATE_LAST_ACTIVITY_LABEL = 'last_activity';
    const USER_CONFIG_STATE_LAST_ACTIVE_IP_LABEL = 'last_active_ip';

    protected $_layout;
    protected $_objectManager;
    protected $_assetRepository;
    protected $_remoteAddress;
    protected $_version;
    protected $_date;

    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        LayoutInterface $layout,
        ProductMetadataInterface $productMetadataInterface,
		\Magento\Config\Model\ResourceModel\Config $resourceConfig,
    	\Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        DateTime $date
    ){
        $this->_version = $productMetadataInterface->getVersion();
        $this->_layout = $layout;
        $this->_remoteAddress = $context->getRemoteAddress();
        $this->_objectManager = $objectManager;
		$this->resourceConfig = $resourceConfig;
    	$this->cacheTypeList = $cacheTypeList;
        $this->_date = $date;

        parent::__construct($context);
    }

    /**
     * Check if Maintenance Mode is applicable
     *
     * @return boolean
     */
    public function isMaintenanceMode(){
        if($this->isEnabled()){
			 return true;          
        }

        return false;
    }

    /**
     * Get the config specific to Maintenance Mode extension
     *
     * @return string
     */
    public function getConfig($xPath){
        $value = $this->scopeConfig->getValue(
            $xPath,
            ScopeInterface::SCOPE_STORE
        );

        return $value;
    }

    /**
     * Check if Maintenance Mode is enabled
     *
     * @return boolean
     */
    public function isEnabled() {
        $isEnabled = $this->getConfig('PasswordProtector/Configuration/isEnabled');
        return $isEnabled;
    }
	
	
	public function isDisabled() {
		 $this->resourceConfig->saveConfig(
        'PasswordProtector/Configuration/isEnabled',
        0,
        \Magento\Framework\App\ScopeInterface::SCOPE_DEFAULT,
        0
    );
     $this->cacheTypeList->cleanType(\Magento\Framework\App\Cache\Type\Config::TYPE_IDENTIFIER);
        return true;
    }

    /**
     * Get the allowed IPs
     *
     * @return array
     */
    public function getPassword() {
        $password = $this->getConfig('PasswordProtector/Configuration/Password');
		return $password;
    }

    protected function isJSON($str){
        json_decode($str);

        return (json_last_error() == JSON_ERROR_NONE);
    }
	
   public function getPasswordProtectorPageBlock(){
	   $block = $this->_layout
            ->createBlock('Magento\Framework\View\Element\Template')
            ->setTemplate('Kgkrunch_PasswordProtector::password.phtml');
	        return $block;
    }
}