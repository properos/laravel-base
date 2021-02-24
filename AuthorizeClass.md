# Authorize Class

 - [Customer Profile](#customer-profile)
	- [Get Customer Profile](#get-customer-profile) 
   	- [Get Customer Profiles](#get-customer-profiles)
   	- [Add Customer Profile](#add-customer-profile)
   	- [Update Customer Profile](#update-customer-profile)
   	- [Delete Customer Profile](#delete-customer-profile)
 - [Payment Profile](#payment-profile)
	- [Get Payment Profile List](#get-payment-profile-list)
	- [Add Payment Profile](#add-payment-profile)
	- [Delete Payment Profile](#delete-payment-profile)
	- [Get Payment Profile](#get-payment-profile)
- [Charge](#charge)
	- [Charge Account](#charge-account)
	- [Authorize Account](#authorize-account)
	- [Capture Account](#capture-account)
	- [Get Transaction](#get-transaction)
	- [Get Transaction History](#get-transaction-history)
	- [Refund Transaction](#refund-transaction)
	

## Customer Profile

### Get Customer Profile
|Field|Required| Description
|--|--|--|
|**profile_id**| *true* | Payment gateway-assigned ID associated with the customer profile. Numeric string.

```php
	$authorize = new PaymentsClass('Authorize');
    $res = $authorize->getCustomerProfile([
	    'profile_id' => '1914482252',	    
    ]);
```
```json
{
	"profile_id": "1914482252",
	"description": "COMMON.ACCEPT.INAPP.PAYMENT",
	"payment_profiles": [
		{
			"payment_profile_id": "1828064502",
			"card_number": "XXXX0002",
			"brand": "AmericanExpress",
			"billing_firstname": null,
			"billing_lastname": null,
			"billing_address": null,
			"billing_zip": null,
		},
		{
			"payment_profile_id": "1828064502",
			"card_number": "XXXX0002",
			"brand": "AmericanExpress",
			"billing_firstname": null,
			"billing_lastname": null,
			"billing_address": null,
			"billing_zip": null,
		},
	],
	"subscriptions": ["1","2"]
}
```
### Get Customer Profiles
|Field|Required| Description

```php
    $res = $authorize->getCustomerProfileIds();
```
```json
{
	"profile_ids": ["1914480151","1914481732","1914482252"],
	"profile_count": 3
}
```
### Add Customer Profile
|Field|Required| Description
|--|--|--|
|**email**| *true* | Customer’s email address. It’s displayed alongside the customer in your dashboard and can be useful for searching and tracking. This may be up to _512 characters_. This will be unset if you POST an empty value.
|**token**| *true* | Base64 encoded data that contains encrypted payment data. The payment gateway expects the encrypted payment data and meta data for the encryption keys. String, 8192 characters. This value is the payment nonce that you received from Authorize.Net. This value must be passed to the Authorize.Net API, along with description, to represent the card details. The nonce is valid for 15 minutes.
|**description**| *true* | Specifies how the request should be processed. The value of description is based on the source of the value of token. String, 128 characters. Use COMMON.ACCEPT.INAPP.PAYMENT for Accept transactions. This value must be passed to the Authorize.Net API, along with token, to represent the card details.

```php
    $res = $authorize->addCustomerProfile([
	    'email' => 'test2@mail.com',
		'token' => 'eyJjb2RlIjoiNTBfMl8wNjAwMDUyRDQ3MDlGNjFEQjA2RUYzNEZBQzQ5ODRDQ0RGM0E2OUJBQTlCRUY5MkE1Qj....',
		'description' => 'COMMON.ACCEPT.INAPP.PAYMENT'
    ]);
```
```json
{
	"profile_id": "1828063212",
	"payment_profile_id": "1828063212"
}
```
### Update Customer Profile
|Field|Required| Description
|--|--|--|
|**profile_id**| *true* | Payment gateway assigned ID associated with the customer profile. Numeric string.
|**default_source**| *false* | ID of the source to make the customer’s new default.
|**description**| *false* | Description of the customer or customer profile. Required only when no values for merchantCustomerId and email are submitted. String, up to 255 characters.
|**email**| *false* |Email address associated with the customer profile. Required when no values for description and merchantCustomerId are submitted. Required when using a European payment processor.

```php
    $res = $authorize->updateCustomerProfile([
	    'profile_id' => '1914482252',
	    'email' => 'test1@test.loc'
    ]);
```
```json
{
	"profile_id": "1914482252",
	"description": "COMMON.ACCEPT.INAPP.PAYMENT",
	"email": "test1@test.loc"
}
```
### Delete Customer Profile
|Field|Required| Description
|--|--|--|
|**profile_id**| *true* | Payment gateway assigned ID associated with the customer profile. Numeric string.

```php
    $res = $authorize->delCustomerProfile([
		'profile_id' => '1914482252'
    ]);
```
```json
{
	"id": "1914482252",
	"deleted": true
}
```

## Payment Profile

### Get Payment Profile 
|Field|Required| Description
|--|--|--|
|**profile_id**| *true* | Payment gateway-assigned ID associated with the customer profile. Numeric string.
|**payment_profile_id**| *true* | Payment gateway-assigned ID associated with the customer payment profile. Numeric string.
|**exp_date**| *false* | Pass true to return an unmasked expiration date in the response. Default is false. Boolean.
|**issuer_info**| *false* | When set to true, this optional field requests that the issuer number (IIN) be included in the response, in the field issuerNumber. Boolean.

```php
    $res = $authorize->getPaymentProfile([
		'profile_id' => '1914480151',
		'payment_profile_id' => "1828063212"
    ]);
```
```json
{
	"profile_id":"1914481732",
	"payment_profile_id": "1828063212",
	"card_number": "XXXX8888",
	"brand":"Visa",
	"billing_firstname": null,
	"billing_lastname": null,
	"billing_address": null,
	"billing_city": null,
	"billing_state": null,
	"billing_country": null,
	"billing_zip": null,
	"subscriptions": "(optional)",
}
```
### Get Payment Profile List (get list of all the payment profiles that match the submitted searchType)
|Field|Required| Description
|--|--|--|
|**search_type**| *true* | The expiration month for the type of payment profiles. Use cardsExpiringInMonth to filter profiles with cards that expire in a given month.
|**exp_date**| *true* | Specifies how to filter search results.  String, 7 characters. Use gYearMonth (YYYY-MM) formatting.
|**order_by**| *false* | Order of results in response. String. Use id to sort results by payment profile ID.
|**order_descending**| *false* | Sort the results in descending order. Boolean.
|**limit**| *false* | The number of transactions per page. You can request up to 1000 payment profiles per page of results. Decimal, between 1 and 1000.
|**starting_after**| *false* | The number of the page to return results from. For example, if you use a limit of 100, setting offset to 1 will return the first 100 profiles, setting offset to 2 will return the second 100 profiles, and so forth. Decimal, between 1 and 100000.

```php
    $res = $authorize->getPaymentProfileList([
		'profile_id' => 'cus_D53AAGsHlILriF',
		'limit' => 2
    ]);
```
```json
{
	"payment_profile_count": "2",
	"payment_profiles": [{
			"profile_id":"1914481732",
			"payment_profile_id":"1828064366",
			"card_number":"XXXX0002",
			"brand":"AmericanExpress",
			"exp_date":"XXXX",
			"billing_firstname":null,
			"billing_lastname":null,
			"billing_address":null,
			"billing_city": null,
			"billing_state": null,
			"billing_country": null,
			"billing_zip":null
		},
		{
			"profile_id":"1914480151",
			"payment_profile_id":"1828063212",
			"card_number":"XXXX8888",
			"brand":"Visa",
			"exp_date":"XXXX",
			"billing_firstname":null,
			"billing_lastname":null,
			"billing_address":null,
			"billing_city": null,
			"billing_state": null,
			"billing_country": null,
			"billing_zip":null
		}
	],
}
```
### Update Payment Profile
|Field|Required| Description
|--|--|--|
|**profile_id**| *true* | The ID of the customer whose cards will be retrieved.
|**payment_profile_id**| *true* | Payment gateway-assigned ID associated with the customer payment profile. Numeric string.
|**card_number**| *true* | Information you comes back from an GetCustomerPaymentProfile
|**billing_firstname**| *false* | The customer’s first name.
|**billing_lastname**| *false* | The customer’s last name.
|**billing_address**| *false* | The customer’s address.
|**billing_city**| *false* | The city of the customer’s shipping address.
|**billing_state**| *false* | The state of the customer’s address.
|**billing_zip**| *false* | The postal code of customer’s billing address.
|**billing_country**| *false* | The country of the customer’s address.

```php
    $res = $authorize->updatePaymentProfile([
		'profile_id' => '1914480151',
		'payment_profile_id' => '1828064366',
		'card_number' => 'XXXX0001'
    ]);
```
```json
{
	"updated": true
}
```

### Add Payment Profile
|Field|Required| Description
|--|--|--|
|**profile_id**| *true* | The ID of the customer whose cards will be retrieved.
|**token**| *true* | This value is the payment nonce that you received from Authorize.Net. This value must be passed to the Authorize.Net API, along with description, to represent the card details. The nonce is valid for 15 minutes. String, 8192 characters.
|**description**| *true* | This value must be passed to the Authorize.Net API, along with token, to represent the card details. String, 128 characters. Use COMMON.ACCEPT.INAPP.PAYMENT for Accept transactions.
|**default_payment**| *false* | When set to true, this field designates the payment profile as the default payment profile. Boolean.
|**mode**| *false* | Indicates the processing mode for the request. If the customer profile contains no payment data, this field should not be sent. String. Use testMode to perform a Luhn mod-10 check on the card number, without further validation. Use liveMode to submit a zero-dollar or one-cent transaction (depending on card type and processor support) to confirm the card number belongs to an active credit or debit account.

```php
    $res = $authorize->addPaymentProfile([
		'profile_id' => '1914480151',
		'token' => 'eyJjb2RlIjoiNTBfMl8wNjAwMDUyRDQ3MDlGNjFEQjA2RUYzNEZBQzQ5ODRDQ0RGM0E2OUJBQTlCRUY5MkE1Qj....',
		'description' => 'COMMON.ACCEPT.INAPP.PAYMENT'
    ]);
```
```json
{
	"payment_profile_id": "1828074898"
}
```
### Delete Payment Profile
|Field|Required|Description
|--|--|--|
|**profile_id**| *true* | Payment gateway-assigned ID associated with the customer profile. Numeric string.
|**payment_profile_id**| *true* | Payment gateway assigned ID associated with the customer payment profile. Numeric string.

```php
    $res = $authorize->delPaymentProfile([
		'profile_id' => '1914480151',
		'payment_profile_id' => '1828074898'
    ]);
```
```json
{
	"id": "1828074898",
	"deleted": true
}
```

## Charge

### Charge Account
|Field|Required| Description
|--|--|--|
|**profile_id**| *true* | Payment gateway-assigned ID associated with the customer profile. Numeric string.
|**payment_profile_id**| *true* | Payment gateway assigned ID associated with the customer payment profile. Numeric string.
|**amount**| *true* | A positive integer representing how much to charge.
|**invoice_number**| *false* | Merchant-defined invoice number associated with the order.
||**token**| *false* | This value is the payment nonce that you received from Authorize.Net. This value must be passed to the Authorize.Net API, along with description, to represent the card details. The nonce is valid for 15 minutes. String, 8192 characters.
|**description**| *false* | This value must be passed to the Authorize.Net API, along with token, to represent the card details. String, 128 characters. Use COMMON.ACCEPT.INAPP.PAYMENT for Accept transactions.

```php
    $res = $authorize->chargeAccount([
		'profile_id' => '1914480151',
		'amount' => '10.50',
		'payment_profile_id' => '1828063212',
		'type' => 'profile|card|bank'
	]);
```
```json
{
	"response_code": "approved, declined, error, held_for_review",
	"authcode": "EZUHQJ (The authorization code granted by the card issuing bank for this transaction)",
	"transaction_id": "60105222015",
	"code_text": "1",
	"description": "This transaction has been approved.",
	"transaction_fee": 0.6045
}
```

### Authorize Account
|Field|Required| Description
|--|--|--|
|**profile_id**| *true* | Payment gateway-assigned ID associated with the customer profile. Numeric string.
|**payment_profile_id**| *true* | Payment gateway assigned ID associated with the customer payment profile. Numeric string.
|**amount**| *true* | A positive integer representing how much to charge.

```php
    $res = $authorize->authorizeAccount([
		'profile_id' => '1914480151',
		'amount' => '11.50',
		'payment_profile_id' => '1828063212',
	]);
```
```json
{
	"response_code": "approved, declined, error, held_for_review",
	"authcode": "GLJFMK (The authorization code granted by the card issuing bank for this transaction)",
	"transaction_id": "60105224047",
	"code_text": "1",
	"description": "This transaction has been approved.",
	"transaction_fee": 0.6335
}
```

### Capture Account
|Field|Required| Description
|--|--|--|
|**transaction_id**| *true* | Transaction ID of the original authOnlyTransaction request.
|**amount**| *true* | A positive integer representing how much to charge.

```php
    $res = $authorize->captureAccount([
		'transaction_id' => '60105224047',
		'amount' => '11.50',
	]);
```
```json
{
	"response_code": "approved, declined, error, held_for_review",
	"authcode": "ROHNFQ (The authorization code granted by the card issuing bank for this transaction)",
	"transaction_id": "60105224048",
	"ref_trans_id": "60105224047",
	"code_text": "1",
	"description": "This transaction has been approved.",
	"transaction_fee": 0.6335
}
```

### Get Transaction
|Field|Required| Description
|--|--|--|
|**transaction_id**| *true* | The identifier of the charge to be retrieved.

```php
    $res = $authorize->getTransaction([
		'transaction_id' => '60105224048'
	]);
```
```json
{
	"response_code": "approved, declined, error, held_for_review",
	"amount": "11.50",
	"transaction_id": "60105224048",
	"type": "authCaptureTransaction, authOnlyTransaction, captureOnlyTransaction, or refundTransaction",
	"status": "authorizedPendingCapture, capturedPendingSettlement, communicationError, refundSettledSuccessfully, refundPendingSettlement, approvedReview, declined, couldNotVoid, expired, generalError, failedReview, settledSuccessfully, settlementError, underReview, voided,FDSPendingReview, FDSAuthorizedPendingReview, returnedItem",
	"payment_profile": {
		"profile_id":"1914481732",
		"payment_profile_id":"1828064366",
		"card_number":"XXXX0002",
		"brand":"AmericanExpress",
		"exp_date":"XXXX",
		"billing_firstname":null,
		"billing_lastname":null,
		"billing_address":null,
		"billing_zip":null
	},
}
```
### Get Transaction History
|Field|Required| Description
|--|--|--|
|**profile_id**| *false* | Only return charges for the customer specified by this profile ID.
|**payment_profile_id**| *false* | Only return charges for the customer specified by this payment profile ID.
|**order_by**| *false* | Order of results in response. String. Use id to sort results by payment profile ID.
|**order_descending**| *false* | Sort the results in descending order. Boolean.
|**limit**| *false* | The number of transactions per page. You can request up to 1000 payment profiles per page of results.
|**starting_after**| *false* | The number of the page to return results from. For example, if you use a limit of 100, setting offset to 1 will return the first 100 profiles, setting offset to 2 will return the second 100 profiles, and so forth.
```php
    $res = $authorize->getTransactionHistory([
		'payment_profile_type' => 'card',
		'profile_id' => 'cus_ClUaGtDYBHoWNw',
		'limit' => 5,
		'starting_after' => 'ch_1CMQOyGeWDdV8AVTOPJLOIPu'
	]);
```
```json
{
	"transactions_count": 4,
	"transactions": [{
			"profile_id":"1914480151",
			"payment_profile_id":"1828063212",
			"transaction_id":"60105226279",
			"utc_date":{"date":"2018-06-28 21:16:55.210000","timezone_type":2,"timezone":"Z"},
			"local_date":{"date":"2018-06-28 14:16:55.210000","timezone_type":3,"timezone":"UTC"},
			"status":"capturedPendingSettlement",
			"invoice_number":null,
			"card_number":"XXXX8888",
			"brand":"Visa",
			"amount":12.5
		},
		...
 		{
			"profile_id":"1914480151",
			"payment_profile_id":"1828063212",
			"transaction_id":"60105224047",
			"utc_date":{"date":"2018-06-28 20:38:46.523000","timezone_type":2,"timezone":"Z"},
			"local_date":{"date":"2018-06-28 13:38:46.523000","timezone_type":3,"timezone":"UTC"},
			"status":"capturedPendingSettlement",
			"invoice_number":null,
			"card_number":"XXXX8888",
			"brand":"Visa",
			"amount":11.5
		}
	],
}
```

### Refund Transaction
|Field|Required| Description
|--|--|--|
|**card_number**| *true* | The customer’s credit card number. Only the last four digits are required for credit card refunds.
|**amount**| *true* | Amount of the transaction. This is the total amount and must include tax, shipping, tips, and any other charges.
|**ref_trans_id**| *false* | Transaction ID of the original request. Required for refunds submitted without Expanded Credit-Return Capabilities (ECC). For ECC transactions, refTransId is not required.

```php
    $res = $authorize->refundTransaction([
		'card_number' => '0015',
		'amount' => '2.00',
	]);
```
```json
{
	"response_code": "approved, declined, error, held_for_review",
	"authcode": "ROHNFQ (The authorization code granted by the card issuing bank for this transaction)",
	"transaction_id": "60105224048",
	"code_text": "1",
	"description": "This transaction has been approved.",
}
```
### Void Transaction
|Field|Required| Description
|--|--|--|
|**transaction_id**| *true* | Transaction ID of the unsettled transaction you wish to void.

```php
    $res = $authorize->voidTransaction([
		'transaction_id' => '60105226279',
	]);
```
```json
{
	"response_code": "approved, declined, error, held_for_review",
	"authcode": "ROHNFQ (The authorization code granted by the card issuing bank for this transaction)",
	"transaction_id": "60105224048",
	"code_text": "1",
	"description": "This transaction has been approved.",
}
```

### Create Subscription
|Field|Required| Description
|--|--|--|
|**subscription_name**| *true* | Merchant-assigned name for the subscription. String, up to 50 characters.
|**length**| *true* | The measurement of time, in association with unit, that is used to define the frequency of the billing occurrences. For a unit of days, use an integer between 7 and 365, inclusive. For a unit of months, use an integer between 1 and 12, inclusive. Numeric string, up to 3 digits.
|**unit**| *true* | The unit of time, in association with the length, between each billing occurrence. String. Either days or months.
|**start_date**| *true* | The date of the first payment. Can not be prior to the subscription creation date. The validation checks against the local server's time, which is expressed as Mountain Time. An error might occur if you try to submit a subscription from a time zone in which the resulting date is different; for example, if you are in the Pacific time zone and try to submit a subscription between 11:00 PM and midnight, with a start date set for today. If the start date is the 31st, and the interval is monthly, the billing date is the last day of each month (even when the month does not have 31 days). String, 10 characters. Use XML date (YYYY-MM-DD) formatting. 
|**total_occurrences**| *true* | Number of payments for the subscription. If a trial period is specified, this value should include the number of payments during the trial period. To create an ongoing subscription without an end date, set totalOccurrences to "9999". Numeric string, up to 4 digits.
|**amount**| *true* | Amount of the charge to be run after the trial period. This is the total amount and must include tax, shipping, tips, and any other charges.
|**token**| *true* | Base64 encoded data that contains encrypted payment data. The payment gateway expects the encrypted payment data and meta data for the encryption keys. String, 8192 characters. This value is the payment nonce that you received from Authorize.Net. This value must be passed to the Authorize.Net API, along with description, to represent the card details. The nonce is valid for 15 minutes.
|**description**| *true* | Specifies how the request should be processed. The value of description is based on the source of the value of token. String, 128 characters. Use COMMON.ACCEPT.INAPP.PAYMENT for Accept transactions. This value must be passed to the Authorize.Net API, along with token, to represent the card details.


```php
    $res = $authorize->createSubscription([
		'subscription_name' => 'Sample subscription',
		'length' => '1',
		'unit' => 'months|days',
		'start_date' => '2020-08-30',
		'total_occurrences' => '20',
		'amount' => '10.20',
		'token' => 'eyJjb2RlIjoiNTBfMl8wNjAwMDUyRDQ3MDlGNjFEQjA2RUYzNEZBQzQ5ODRDQ0RGM0E2OUJBQTlCRUY5MkE1Qj....',
		'description' => 'COMMON.ACCEPT.INAPP.PAYMENT'
	]);
```
```json
{
	"code": "I...",
	"message": "This subscription has been created.",
}
```

### Update Subscription
|Field|Required| Description
|--|--|--|
|**subscription_id**| *true* | The payment gateway-assigned identification number for the subscription.
|**length**| *false* | The measurement of time, in association with unit, that is used to define the frequency of the billing occurrences. For a unit of days, use an integer between 7 and 365, inclusive. For a unit of months, use an integer between 1 and 12, inclusive. Numeric string, up to 3 digits.
|**unit**| *false* | The unit of time, in association with the length, between each billing occurrence. String. Either days or months.
|**start_date**| *false* | The date of the first payment. Can not be prior to the subscription creation date. The validation checks against the local server's time, which is expressed as Mountain Time. An error might occur if you try to submit a subscription from a time zone in which the resulting date is different; for example, if you are in the Pacific time zone and try to submit a subscription between 11:00 PM and midnight, with a start date set for today. If the start date is the 31st, and the interval is monthly, the billing date is the last day of each month (even when the month does not have 31 days). String, 10 characters. Use XML date (YYYY-MM-DD) formatting. 
|**total_occurrences**| *false* | Number of payments for the subscription. If a trial period is specified, this value should include the number of payments during the trial period. To create an ongoing subscription without an end date, set totalOccurrences to "9999". Numeric string, up to 4 digits.
|**amount**| *false* | Amount of the charge to be run after the trial period. This is the total amount and must include tax, shipping, tips, and any other charges.
|**token**| *false* | Base64 encoded data that contains encrypted payment data. The payment gateway expects the encrypted payment data and meta data for the encryption keys. String, 8192 characters. This value is the payment nonce that you received from Authorize.Net. This value must be passed to the Authorize.Net API, along with description, to represent the card details. The nonce is valid for 15 minutes.
|**description**| *false* | Specifies how the request should be processed. The value of description is based on the source of the value of token. String, 128 characters. Use COMMON.ACCEPT.INAPP.PAYMENT for Accept transactions. This value must be passed to the Authorize.Net API, along with token, to represent the card details.


```php
    $res = $authorize->updateSubscription([
		'subscription_id' => '100748',
	]);
```
```json
{
	"code": "I...",
	"message": "This subscription has been updated.",
}
```

### Cancel Subscription
|Field|Required| Description
|--|--|--|
|**subscription_id**| *true* | The payment gateway-assigned identification number for the subscription.


```php
    $res = $authorize->cancelSubscription([
		'subscription_id' => '100748',
	]);
```
```json
{
	"code": "I...",
	"message": "This subscription has been deleted.",
}
```