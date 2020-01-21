<?php
/**
 * Override Order
 */
// @codingStandardsIgnoreStart
class Order extends OrderCore
{
    /**
     * Point code
     */
    public $pos_code;

    /**
     * Operator name
     */
    public $pos_operator;

    /**
     * number
     */
    public $number;

    /**
     * status
     */
    public $status;

    /**
     * delivery_type
     */
    public $delivery_type;

    /**
     * creation_date
     */
    public $creation_date;

    /**
     * advice_date
     */
    public $advice_date;

    /**
     * tracking_number
     */
    public $tracking_number;

    /**
     * Cash on delivery
     */
    public $is_cod;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'orders',
        'primary' => 'id_order',
        'fields' => array(
            'id_address_delivery' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_address_invoice' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_cart' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_currency' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_shop_group' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_lang' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_carrier' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'current_state' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'secure_key' => array('type' => self::TYPE_STRING, 'validate' => 'isMd5'),
            'payment' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
            'module' => array('type' => self::TYPE_STRING, 'validate' => 'isModuleName', 'required' => true),
            'recyclable' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'gift' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'gift_message' => array('type' => self::TYPE_STRING, 'validate' => 'isMessage'),
            'mobile_theme' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'total_discounts' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_discounts_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_discounts_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_paid' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'total_paid_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_paid_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_paid_real' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'total_products' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'total_products_wt' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'total_shipping' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_shipping_tax_incl' =>  array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_shipping_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'carrier_tax_rate' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'total_wrapping' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_wrapping_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_wrapping_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'round_mode' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'round_type' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'shipping_number' => array('type' => self::TYPE_STRING, 'validate' => 'isTrackingNumber'),
            'conversion_rate' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => true),
            'invoice_number' => array('type' => self::TYPE_INT),
            'delivery_number' => array('type' => self::TYPE_INT),
            'invoice_date' => array('type' => self::TYPE_DATE),
            'delivery_date' => array('type' => self::TYPE_DATE),
            'valid' => array('type' => self::TYPE_BOOL),
            'reference' => array('type' => self::TYPE_STRING),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'pos_code' => array('type' => self::TYPE_STRING),
            'pos_operator' => array('type' => self::TYPE_STRING),
            'number' => array('type' => self::TYPE_STRING),
            'status' => array('type' => self::TYPE_STRING),
            'delivery_type' => array('type' => self::TYPE_STRING),
            'creation_date' => array('type' => self::TYPE_STRING),
            'advice_date' => array('type' => self::TYPE_STRING),
            'tracking_number' => array('type' => self::TYPE_STRING),
            'is_cod' => array('type' => self::TYPE_INT),
        ),
    );

    const NEW_STATUS                     = 'NEW';
    const SAVED                          = 'SAVED';
    const WAITING_FOR_PAYMENT            = 'WAITING_FOR_PAYMENT';
    const PAYMENT_CONFIRMED              = 'PAYMENT_CONFIRMED';
    const PAYMENT_REJECTED               = 'PAYMENT_REJECTED';
    const PAYMENT_CANCELLATION_ERROR     = 'PAYMENT_CANCELLATION_ERROR';
    const PROCESSING                     = 'PROCESSING';
    const ADVISING                       = 'ADVISING';
    const ERROR                          = 'ERROR';
    const READY_TO_SEND                  = 'READY_TO_SEND';
    const POSTED                         = 'POSTED';
    const ON_THE_WAY                     = 'ON_THE_WAY';
    const READY_TO_PICKUP                = 'READY_TO_PICKUP';
    const OUT_FOR_DELIVERY               = 'OUT_FOR_DELIVERY';
    const DELIVERED                      = 'DELIVERED';
    const REMINDER_SENT                  = 'REMINDER_SENT';
    const PICKUP_EXPIRED                 = 'PICKUP_EXPIRED';
    const AVIZO                          = 'AVIZO';
    const CLAIMED                        = 'CLAIMED';
    const RETURNED                       = 'RETURNED';
    const ARCHIVED                       = 'ARCHIVED';
    const OTHER                          = 'OTHER';
    const MARKED_FOR_CANCELLATION_STATUS = 'MARKED_FOR_CANCELLATION';
    const CANCELED                       = 'CANCELED';

    /**
     * Waybill NOT possible statuses
     *
     * @var array
     */
    protected $_waybillUnavailableStatuses = array(
        self::NEW_STATUS,
        self::SAVED,
        self::WAITING_FOR_PAYMENT,
        self::PAYMENT_CONFIRMED,
        self::PAYMENT_REJECTED,
        self::PAYMENT_CANCELLATION_ERROR,
        self::PROCESSING,
        self::ADVISING,
        self::ERROR,
        self::CANCELED,
    );

    /**
     * Cancel possible statuses
     *
     * @var array
     */

    protected $_cancelStatuses = array(self::MARKED_FOR_CANCELLATION_STATUS);

    /**
     * Cancel possible statuses
     *
     * @var array
     */
    protected $_sentStatuses = array(
        self::POSTED,
        self::ON_THE_WAY,
        self::READY_TO_PICKUP,
        self::OUT_FOR_DELIVERY,
        self::DELIVERED,
        self::CANCELED
    );

    /**
     * Advice possible statuses
     *
     * @var array
     */
    protected $_adviceStatuses = array(self::SAVED);

    /**
     * @return bool
     */
    public function canCancel() {
        if (in_array($this->status, $this->_cancelStatuses)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canAdvice() {
        if (!empty($this->number) && in_array($this->status, $this->_adviceStatuses)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canUpdate() {
        return true;
    }

    /**
     * @return bool
     */
    public function canWaybill()
    {
        if (empty($this->number) || in_array($this->status, $this->_waybillUnavailableStatuses)) {
            return false;
        }

        return true;
    }
}
// @codingStandardsIgnoreEnd
