<?php

namespace PHPDish\Bundle\PostBundle\EventListener;

use PHPDish\Bundle\PaymentBundle\Event\PaymentEvent;
use PHPDish\Bundle\PaymentBundle\Model\PaymentInterface;
use PHPDish\Bundle\PaymentBundle\Service\WalletManagerInterface;
use PHPDish\Bundle\PostBundle\Service\CategoryManagerInterface;

final class PaymentListener
{
    /**
     * @var CategoryManagerInterface
     */
    protected $categoryManager;

    /**
     * @var WalletManagerInterface
     */
    protected $walletManager;

    public function __construct(
        CategoryManagerInterface $categoryManager,
        WalletManagerInterface $walletManager
    )
    {
        $this->categoryManager = $categoryManager;
        $this->walletManager = $walletManager;
    }

    /**
     * 交易完成，
     * 1. 增加付款人的订阅
     * 2. 增加专栏所属人的钱余额
     * @param PaymentEvent $event
     */
    public function onPaymentPaid(PaymentEvent $event)
    {
        $payment = $event->getPayment();
        //买书的交易或者订阅专栏的交易处理
        if (
            $payment->getType() === PaymentInterface::TYPE_FOLLOW_CATEGORY
            || $payment->getType() === PaymentInterface::TYPE_BUY_BOOK
        ) {
            $category = $this->categoryManager->findCategoryById($payment->getPayableId());
            if ($category) { //用户支付成功，增加订阅
                $this->categoryManager->followCategory($category, $payment->getUser());

                $this->walletManager->addCategoryIncome($category->getCreator(), $category, $payment->getUser(), $payment->getAmount()); //增加收入
            }
        }
    }
}