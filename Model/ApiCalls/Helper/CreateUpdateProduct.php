<?php

namespace Drip\Connect\Model\ApiCalls\Helper;

class CreateUpdateProduct extends \Drip\Connect\Model\ApiCalls\Helper
{
    const PRODUCT_NEW = 'created';
    const PRODUCT_CHANGED = 'updated';
    const PRODUCT_DELETED = 'deleted';

    /** @var \Drip\Connect\Model\ApiCalls\BaseFactory */
    protected $connectApiCallsBaseFactory;

    /** @var \Drip\Connect\Model\ApiCalls\Request\BaseFactory */
    protected $connectApiCallsRequestBaseFactory;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface */
    protected $scopeConfig;

    /** @var \Magento\Framework\App\ProductMetadataInterface */
    protected $productMetadata;

    /** @var \Magento\Framework\Module\ResourceInterface */
    protected $moduleResource;

    public function __construct(
        \Drip\Connect\Model\ApiCalls\BaseFactory $connectApiCallsBaseFactory,
        \Drip\Connect\Model\ApiCalls\Request\BaseFactory $connectApiCallsRequestBaseFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Module\ResourceInterface $moduleResource,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        $data = []
    ) {
        $this->connectApiCallsBaseFactory = $connectApiCallsBaseFactory;
        $this->connectApiCallsRequestBaseFactory = $connectApiCallsRequestBaseFactory;
        $this->scopeConfig = $scopeConfig;
        $this->moduleResource = $moduleResource;
        $this->productMetadata = $productMetadata;

        $this->apiClient = $this->connectApiCallsBaseFactory->create([
            'options' => [
                'endpoint' => $this->scopeConfig->getValue(
                    'dripconnect_general/api_settings/account_id',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                ) . '/' . self::ENDPOINT_PRODUCT,
                'v3' => true,
            ]
        ]);

        if (!empty($data) && is_array($data)) {
            $data['version'] = 'Magento ' . $this->productMetadata->getVersion() . ', '
                             . 'Drip Extension ' . $this->moduleResource->getDbVersion('Drip_Connect');
        }

        $this->request = $this->connectApiCallsRequestBaseFactory->create()
            ->setMethod(\Zend_Http_Client::POST)
            ->setRawData(json_encode($data));
    }
}
