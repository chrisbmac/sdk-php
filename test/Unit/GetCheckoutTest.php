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

namespace Afterpay\SDK\Test\Unit;

require_once __DIR__ . '/../autoload.php';

use PHPUnit\Framework\TestCase;

class GetCheckoutTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function testMissingCheckoutTokenException()
    {
        $getCheckoutRequest = new \Afterpay\SDK\HTTP\Request\GetCheckout();

        try {
            $getCheckoutRequest->send();

            throw new \Exception('Expected PrerequisiteNotMetException not thrown');
        } catch (\Afterpay\SDK\Exception\PrerequisiteNotMetException $e) {
            $this->assertEquals('Cannot send a GetCheckout Request without a checkout token (must call GetCheckout::setCheckoutToken before GetCheckout::send)', $e->getMessage());
        }
    }

    public function testNullCheckoutTokenException()
    {
        $getCheckoutRequest = new \Afterpay\SDK\HTTP\Request\GetCheckout();

        try {
            $getCheckoutRequest->setCheckoutToken(null);

            throw new \Exception('Expected InvalidArgumentException not thrown');
        } catch (\Afterpay\SDK\Exception\InvalidArgumentException $e) {
            $this->assertEquals('Expected string for checkoutToken; NULL given', $e->getMessage());
        }
    }

    public function testArrayCheckoutTokenException()
    {
        $getCheckoutRequest = new \Afterpay\SDK\HTTP\Request\GetCheckout();

        try {
            $getCheckoutRequest->setCheckoutToken([]);

            throw new \Exception('Expected InvalidArgumentException not thrown');
        } catch (\Afterpay\SDK\Exception\InvalidArgumentException $e) {
            $this->assertEquals('Expected string for checkoutToken; array given', $e->getMessage());
        }
    }

    public function testFloatCheckoutTokenException()
    {
        $getCheckoutRequest = new \Afterpay\SDK\HTTP\Request\GetCheckout();

        try {
            $getCheckoutRequest->setCheckoutToken(0.0);

            throw new \Exception('Expected InvalidArgumentException not thrown');
        } catch (\Afterpay\SDK\Exception\InvalidArgumentException $e) {
            $this->assertEquals('Expected string for checkoutToken; double given', $e->getMessage());
        }
    }

    public function testIntegerCheckoutTokenException()
    {
        $getCheckoutRequest = new \Afterpay\SDK\HTTP\Request\GetCheckout();

        try {
            $getCheckoutRequest->setCheckoutToken(1);

            throw new \Exception('Expected InvalidArgumentException not thrown');
        } catch (\Afterpay\SDK\Exception\InvalidArgumentException $e) {
            $this->assertEquals("Expected string for checkoutToken; integer given", $e->getMessage());
        }
    }

    public function testCheckoutTokenContainingSlashException()
    {
        $getCheckoutRequest = new \Afterpay\SDK\HTTP\Request\GetCheckout();

        try {
            $getCheckoutRequest->setCheckoutToken('a1/z9');

            throw new \Exception('Expected InvalidArgumentException not thrown');
        } catch (\Afterpay\SDK\Exception\InvalidArgumentException $e) {
            $this->assertEquals("Expected well-formed URI component for checkoutToken; 'a1/z9' given", $e->getMessage());
        }
    }

    public function testCheckoutTokenContainingPercentException()
    {
        $getCheckoutRequest = new \Afterpay\SDK\HTTP\Request\GetCheckout();

        try {
            $getCheckoutRequest->setCheckoutToken('a1%2Fz9');

            throw new \Exception('Expected InvalidArgumentException not thrown');
        } catch (\Afterpay\SDK\Exception\InvalidArgumentException $e) {
            $this->assertEquals("Expected well-formed URI component for checkoutToken; 'a1%2Fz9' given", $e->getMessage());
        }
    }

    public function testCheckoutTokenContainingEacuteException()
    {
        $getCheckoutRequest = new \Afterpay\SDK\HTTP\Request\GetCheckout();

        try {
            $getCheckoutRequest->setCheckoutToken('a1éz9');

            throw new \Exception('Expected InvalidArgumentException not thrown');
        } catch (\Afterpay\SDK\Exception\InvalidArgumentException $e) {
            $this->assertEquals("Expected well-formed URI component for checkoutToken; 'a1éz9' given", $e->getMessage());
        }
    }

    public function testNumericStringCheckoutTokenOk()
    {
        $getCheckoutRequest = new \Afterpay\SDK\HTTP\Request\GetCheckout();

        $getCheckoutRequest->setCheckoutToken('123456789');

        $this->assertEquals('/v2/checkouts/123456789', $getCheckoutRequest->getUri());
    }

    public function testUnusualStringCheckoutTokenOk()
    {
        $getCheckoutRequest = new \Afterpay\SDK\HTTP\Request\GetCheckout();

        $getCheckoutRequest->setCheckoutToken('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.~');

        $this->assertEquals('/v2/checkouts/ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.~', $getCheckoutRequest->getUri());
    }
}
