<?php

namespace Mavenbird\PaymentRestriction\Block\Cart;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Quote\Model\Quote\Address as QuoteAddress;
use Mavenbird\PaymentRestriction\Model\Rule;
use Magento\Payment\Api\PaymentMethodListInterface;
use Mavenbird\PaymentRestriction\Model\ResourceModel\Rule\CollectionFactory as PaymentRestrictionCollectionFactory;

class RuleExample extends Template
{
     /**s
      * @var PaymentMethodListInterface
      */
    private $paymentMethodList;

    /**
     * @var PaymentRestrictionCollectionFactory
     */
    private $paymentrestrictionCollectionFactory;
    /**
     * @var string
     */
    private $message;
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * Construct function
     *
     * @param Template\Context $context
     * @param PaymentMethodListInterface $paymentMethodList
     * @param PaymentRestrictionCollectionFactory $paymentrestrictionCollectionFactory
     * @param CheckoutSession $checkoutSession
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        PaymentMethodListInterface $paymentMethodList,
        PaymentRestrictionCollectionFactory $paymentrestrictionCollectionFactory,
        CheckoutSession $checkoutSession,
        array $data = []
    ) {
        $this->paymentrestrictionCollectionFactory = $paymentrestrictionCollectionFactory;
        $this->paymentMethodList = $paymentMethodList;
        $this->checkoutSession        = $checkoutSession;
        $this->message                = '';
        parent::__construct($context, $data);
    }

    /**
     * GetString function
     *
     * @return string
     */
    public function getMessage()
    {
      
        $shippingAddress = $this->getShippingAddress();
        $rule = $this->getRule();
        $items = $rule->getItems();
        $validatedId = [];
        foreach ($items as $i) {
            if ($i && $i->validate($shippingAddress)) {
                
                $this->message = __(
                    'yeh this mehtod is true.'
                );
                $validatedId[] = $i->getId();
            }
            
        }
        
        return $validatedId;
    }
   
   /**
    * GetRule function
    *
    * @return Rule|null
    */
    private function getRule()
    {
        $ids= [];
        /** @var \Mavenbird\PaymentRestriction\Model\ResourceModel\Rule\Collection $paymentrestrictionCollection */
        $paymentrestrictionCollection = $this->paymentrestrictionCollectionFactory->create()->getData();
        foreach ($paymentrestrictionCollection as $id) {
            $ids[] = $id['rule_id'];
        }
        
        $paymentrestrictionCollection = $this->paymentrestrictionCollectionFactory->create();
        $paymentrestrictionCollection->addFieldToFilter('rule_id', ['in' => $ids]);
        /** @var Rule|null $rule */
        return $paymentrestrictionCollection;
    }

   /**
    * QuoteAddress function
    *
    * @return QuoteAddress|null
    */
    private function getShippingAddress(): ?QuoteAddress
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        try {
            $quote = $this->checkoutSession->getQuote();
        } catch (LocalizedException $exception) {
            return null;
        }
        if (!$quote) {
            return null;
        }
        return $quote->getIsVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();
    }
}
