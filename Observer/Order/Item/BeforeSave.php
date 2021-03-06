<?php

namespace Drip\Connect\Observer\Order\Item;

/**
 * Order item before save observer
 */
class BeforeSave extends \Drip\Connect\Observer\Base
{
    /** @var \Drip\Connect\Model\Transformer\OrderItemFactory */
    protected $orderItemTransformerFactory;

    /** @var \Magento\Framework\Registry */
    protected $registry;

    /**
     * constructor
     */
    public function __construct(
        \Drip\Connect\Model\ConfigurationFactory $configFactory,
        \Drip\Connect\Logger\Logger $logger,
        \Drip\Connect\Model\Transformer\OrderItemFactory $orderItemTransformerFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($configFactory, $logger, $storeManager);
        $this->registry = $registry;
        $this->orderItemTransformerFactory = $orderItemTransformerFactory;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function executeWhenEnabled(\Magento\Framework\Event\Observer $observer)
    {
        $orderItem = $observer->getDataObject();

        if (!$orderItem->getId()) {
            return;
        }

        /** @var Drip\Connect\Model\Transformer\OrderItem */
        $orderItemTransformer = $this->orderItemTransformerFactory->create([
            'item' => $orderItem,
        ]);

        $items = $this->registry->registry(self::REGISTRY_KEY_ORDER_ITEMS_OLD_DATA);
        $items[$orderItem->getId()] = $orderItemTransformer->getOrigStatusData();

        $this->registry->unregister(self::REGISTRY_KEY_ORDER_ITEMS_OLD_DATA);
        $this->registry->register(self::REGISTRY_KEY_ORDER_ITEMS_OLD_DATA, $items);
    }
}
