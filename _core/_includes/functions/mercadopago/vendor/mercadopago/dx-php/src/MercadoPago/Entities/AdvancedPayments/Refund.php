<?php

namespace MercadoPago\AdvancedPayments;

use MercadoPago\Annotation\RestMethod;
use MercadoPago\Annotation\RequestParam;
use MercadoPago\Annotation\Attribute;
use MercadoPago\Entity;

/**
 * @RestMethod(resource="/v1/advanced_payments/:advanced_payment_id/refunds", method="create")
 * @RestMethod(resource="/v1/advanced_payments/:advanced_payment_id/refunds/:refund_id", method="read")
 * @RequestParam(param="access_token")
 */
class Refund extends Entity {

    /**
     * @Attribute()
     */
    protected $id;
    /**
     * @Attribute(serialize=false)
     */
    protected $payment_id;
    /**
     * @Attribute()
     */
    protected $amount;
    /**
     * @Attribute()
     */
    protected $metadata;
    /**
     * @Attribute()
     */
    protected $source;
    /**
     * @Attribute(readOnly=true)
     */
    protected $date_created;

}

?>