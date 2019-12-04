<?php

namespace Drip\Connect\Model\ApiCalls\Helper;

class CreateUpdateQuote extends \Drip\Connect\Model\ApiCalls\Helper
{
    const PROVIDER_NAME = 'magento';
    const QUOTE_NEW = 'created';
    const QUOTE_CHANGED = 'updated';

    /** @var \Drip\Connect\Model\ApiCalls\BaseFactory */
    protected $connectApiCallsBaseFactory;

    /** @var \Drip\Connect\Model\ApiCalls\Request\BaseFactory */
    protected $connectApiCallsRequestBaseFactory;

    /** @var \Magento\Framework\App\ProductMetadataInterface */
    protected $productMetadata;

    /** @var \Magento\Framework\Module\ResourceInterface */
    protected $moduleResource;

    public function __construct(
        \Drip\Connect\Model\ApiCalls\BaseFactory $connectApiCallsBaseFactory,
        \Drip\Connect\Model\ApiCalls\Request\BaseFactory $connectApiCallsRequestBaseFactory,
        \Magento\Framework\Module\ResourceInterface $moduleResource,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Drip\Connect\Model\ConfigurationFactory $configFactory,
        $data = []
    ) {
        $this->connectApiCallsBaseFactory = $connectApiCallsBaseFactory;
        $this->connectApiCallsRequestBaseFactory = $connectApiCallsRequestBaseFactory;
        $this->moduleResource = $moduleResource;
        $this->productMetadata = $productMetadata;

        $config = $configFactory->createForCurrentScope();

        $this->apiClient = $this->connectApiCallsBaseFactory->create([
            'endpoint' => $config->getAccountId() . '/' . self::ENDPOINT_CART,
            'config' => $config,
            'v3' => true,
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
