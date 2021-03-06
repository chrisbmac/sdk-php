<?php

/**
 * @copyright Copyright (c) 2020 Afterpay Limited Group
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Afterpay\SDK\HTTP\Request;

use Afterpay\SDK\Exception\InvalidArgumentException;
use Afterpay\SDK\Exception\PrerequisiteNotMetException;
use Afterpay\SDK\HTTP\Request;
use Afterpay\SDK\Model\Money;

class CreateRefund extends Request
{
    /**
     * @var array $data
     */
    protected $data = [
        'requestId' => [
            'type' => 'string',
            'length' => 64
        ],
        'amount' => [
            'type' => Money::class,
            'required' => true
        ],
        'merchantReference' => [
            'type' => 'string'
        ],
        'refundMerchantReference' => [
            'type' => 'string',
            'length' => 128
        ]
    ];

    /**
     * @var string $orderId
     *
     * @todo Make a flexible array for all path params similar to body data.
     */
    protected $orderId;

    /**
     * @throws \Afterpay\SDK\Exception\PrerequisiteNotMetException
     */
    protected function beforeSend()
    {
        if (is_null($this->orderId)) {
            throw new PrerequisiteNotMetException('Cannot send a CreateRefund Request without an Order ID (must call CreateRefund::setOrderId before CreateRefund::send)');
        }
    }

    public function __construct(...$args)
    {
        parent::__construct(... $args);

        $this
            ->setHttpMethod('POST')
            ->configureBasicAuth()
        ;
    }

    /**
     * @param string $orderId
     * @return \Afterpay\SDK\HTTP\Request\CreateRefund
     * @throws \Afterpay\SDK\Exception\InvalidArgumentException
     */
    public function setOrderId($orderId)
    {
        if (is_int($orderId)) {
            $orderId = (string) abs($orderId);
        } elseif (is_string($orderId) && ! preg_match('/^\d+$/', $orderId)) {
            throw new InvalidArgumentException("Expected integer or numeric string for orderId; '{$orderId}' given");
        } elseif (! is_string($orderId)) {
            throw new InvalidArgumentException('Expected integer or numeric string for orderId; ' . gettype($orderId) . ' given');
        }

        $this->orderId = $orderId;

        $this->setUri("/v2/payments/{$this->orderId}/refund");

        return $this;
    }
}
