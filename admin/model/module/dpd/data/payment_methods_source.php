<?php
/**
 * Zitec_Dpd – shipping carrier extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Zitec
 * @package    Zitec_Dpd
 * @copyright  Copyright (c) 2014 Zitec COM
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 *
 * @category   Zitec
 * @package    Zitec_Dpd
 * @author     Zitec COM <magento@zitec.ro>
 */

require_once(DIR_APPLICATION . 'model' . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'dpd' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'source' . '.php');

class ModelModuleDpdDataPaymentMethodsSource extends ModelModuleDpdDataSource
{
    const CASH = 'Cash';
    const CREDIT_CART = 'CreditCard';
    const CROSSED_CHECK = 'CrossedCheck';

    public static $_data = array(
        self::CASH          => 'Cash',
        self::CREDIT_CART   => 'Credit card',
        self::CROSSED_CHECK => 'Crossed check',
    );

    public function getDefaultMethod()
    {
        return self::CASH;
    }

}