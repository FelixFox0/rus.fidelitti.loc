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

// Heading
$_['heading_title'] = 'Livrare DPD - Setari generale';

// Text
$_['text_shipping'] = 'Livrare';

// Button
$_['button_add_module'] = 'Add Module';
$_['button_remove']     = 'Sterge';
$_['dpd_text_shipping'] = 'DPD Shipping Carrier';

// Error

$_['shipping_settings_details'] = '';
$_['sender_settings_details']   = '';
$_['table_rates_details']       = '';


$temp = $this->load('shipping/zitec_dpd/general');
$temp = array_diff_key($temp, $_);
$_    = array_merge($_, $temp);