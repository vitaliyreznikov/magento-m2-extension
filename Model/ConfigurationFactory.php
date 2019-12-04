<?php

namespace Drip\Connect\Model;

class ConfigurationFactory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /** @var \Magento\Store\Model\StoreManagerInterface */
    protected $storeManager;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->objectManager = $objectManager;
        $this->request = $request;
        $this->storeManager = $storeManager;
    }

    /**
     * Create configuration model
     *
     * @param int $storeId
     * @return \Drip\Connect\Model\Configuration
     */
    public function create($storeId)
    {
        return $this->objectManager->create(\Drip\Connect\Model\Configuration::class, ['storeId' => $storeId]);
    }

    /**
     * Create a configuration model scoped to the current store based on the request param
     *
     * @return \Drip\Connect\Model\Configuration
     */
    public function createForCurrentStoreParam()
    {
        return $this->create($this->request->getParam('store'));
    }

    /**
     * Create a configuration model scoped to the global or default installation config
     *
     * @return \Drip\Connect\Model\Configuration
     */
    public function createForGlobalScope()
    {
        return $this->create(\Magento\Store\Model\Store::DEFAULT_STORE_ID);
    }

    /**
     * Obtains configuration scoped to the current store.
     *
     * Only useful when in a store view scope. E.g. this doesn't work in the admin.
     *
     * @return \Drip\Connect\Model\Configuration
     */
    public function createForCurrentScope()
    {
        return $this->create($this->storeManager->getStore()->getId());
    }
}
