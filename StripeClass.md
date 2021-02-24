# Stripe Class

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
|**profile_id**| *true* | The identifier of the customer to be retrieved.

```php
	$stripe = new PaymentsClass('Stripe');
	
    $res = $stripe->getCustomerProfile([
	    'profile_id' => 'cus_D53AAGsHlILriF',	    
    ]);
```
```json
{
	"id": "cus_D53AAGsHlILriF",
	"object": "customer",
	"account_balance": 0,
	"created": 1529450192,
	"currency": null,
	"default_source": "card_1Cetr4GeWDdV8AVTTtxUapde",
	"delinquent": false,
	"description": "Test One",
	"discount": null,
	"email": "test1@mail.com",
	"invoice_prefix": "EFD8C32",
	"livemode": false,
	"metadata": [],
	"shipping": null,
	"sources": {...},
	"subscriptions": {...}
}
```







### Get Customer Profiles
|Field|Required| Description
|--|--|--|
|**limit**| *false* | A limit on the number of objects to be returned. Limit can range between 1 and 100, and the default is 10.
|**starting_after**| *false* | A cursor for use in pagination. `starting_after` is an object ID that defines your place in the list. For instance, if you make a list request and receive 100 objects, ending with `obj_foo`, your subsequent call can include `starting_after=obj_foo` in order to fetch the next page of the list.

```php
    $res = $stripe->getCustomerProfileIds([
		'limit' => '5'
    ]);
```
```json
{
	"object": "list",
	"data": [{
			"id": "cus_D53AAGsHlILriF",
			"object": "customer",
			"account_balance": 0,
			"created": 1529450192,
			"currency": null,
			"default_source": "card_1Cetr4GeWDdV8AVTTtxUapde",
			"delinquent": false,
			"description": "Test One",
			"discount": null,
			"email": "test1@mail.com",
			"invoice_prefix": "EFD8C32",
			"livemode": false,
			"metadata": [],
			"shipping": null,
			"sources": {...},
			"subscriptions": {...}
		},
		...
		{
			"id": "cus_ClHCzrRKSN8Gre",
			"object": "customer",
			"account_balance": 0,
			"created": 1524889583,
			"currency": null,
			"default_source": "card_1CLk0qGeWDdV8AVTrVJyfiHG",
			"delinquent": false,
			"description": "Customer for emily.jones@example.com",
			"discount": null,
			"email": null,
			"invoice_prefix": "4BCE258",
			"livemode": false,
			"metadata": [],
			"shipping": null,
			"sources": {...},
			"subscriptions": {...}
		}
	],
	"has_more": false,
	"url": "/v1/customers"
}
```
### Add Customer Profile
|Field|Required| Description
|--|--|--|
|**email**| *true* | Customer’s email address. It’s displayed alongside the customer in your dashboard and can be useful for searching and tracking. This may be up to _512 characters_. This will be unset if you POST an empty value.
|**source**| *true* | The source can either be a [Token](https://stripe.com/docs/api#tokens) or a [Source](https://stripe.com/docs/api#sources), as returned by [Elements](https://stripe.com/docs/elements), or a dictionary containing a user’s credit card details (with the options shown below). You must provide a source if the customer does not already have a valid source attached, and you are subscribing the customer to be charged automatically for a plan that is not free. Passing `source` will create a new source object, make it the customer default source, and delete the old customer default if one exists. If you want to add an additional source, instead use the [card creation API](https://stripe.com/docs/api#create_card) to add the card and then the [customer update API](https://stripe.com/docs/api#update_customer) to set it as the default. Whenever you attach a card to a customer, Stripe will automatically validate the card.
|**description**| *true* | An arbitrary string that you can attach to a customer object. It is displayed alongside the customer in the dashboard. This will be unset if you POST an empty value.

```php
    $res = $stripe->addCustomerProfile([
	    'email' => 'test2@mail.com',
		'source' => 'tok_mastercard',
		'description' => 'Test Two'
    ]);
```
```json
{
	"id": "cus_D5OXBI28ihasha",
	"object": "customer",
	"account_balance": 0,
	"created": 1529529723,
	"currency": null,
	"default_source": "card_1CfDkdGeWDdV8AVTeg1ID9AN",
	"delinquent": false,
	"description": "Test Two",
	"discount": null,
	"email": "test2@mail.com",
	"invoice_prefix": "7439A3C",
	"livemode": false,
	"metadata": [],
	"shipping": null,
	"sources": {
		"object": "list",
		"data": [{
			"id": "card_1CfDkdGeWDdV8AVTeg1ID9AN",
			"object": "card",
			"address_city": null,
			"address_country": null,
			"address_line1": null,
			"address_line1_check": null,
			"address_line2": null,
			"address_state": null,
			"address_zip": null,
			"address_zip_check": null,
			"brand": "MasterCard",
			"country": "US",
			"customer": "cus_D5OXBI28ihasha",
			"cvc_check": null,
			"dynamic_last4": null,
			"exp_month": 6,
			"exp_year": 2019,
			"fingerprint": "qbFOEWdWtTwbqyNm",
			"funding": "credit",
			"last4": "4444",
			"metadata": [],
			"name": null,
			"tokenization_method": null
		}],
		"has_more": false,
		"total_count": 1,
		"url": "/v1/customers/cus_D5OXBI28ihasha/sources"
	},
	"subscriptions": {
		"object": "list",
		"data": [],
		"has_more": false,
		"total_count": 0,
		"url": "/v1/customers/cus_D5OXBI28ihasha/subscriptions"
	}
}
```

### Update Customer Profile
|Field|Required| Description
|--|--|--|
|**profile_id**| *true* | The identifier of the customer to be retrieved.
|**default_source**| *false* | ID of the source to make the customer’s new default.
|**description**| *false* | An arbitrary string that you can attach to a customer object. It is displayed alongside the customer in the dashboard. This will be unset if you POST an empty value
|**email**| *false* |Customer’s email address. It’s displayed alongside the customer in your dashboard and can be useful for searching and tracking. This may be up to _512 characters_. This will be unset if you POST an empty value.

```php
    $res = $stripe->getDriver()->updateCustomerProfile([
	    'profile_id' => 'cus_D53AAGsHlILriF',
	    'default_source' => 'card_1Cetr4GeWDdV8AVTTtxUapde'
    ]);
```
```json
{
	"id": "cus_D53AAGsHlILriF",
	"object": "customer",
	"account_balance": 0,
	"created": 1529450192,
	"currency": null,
	"default_source": "card_1Cetr4GeWDdV8AVTTtxUapde",
	"delinquent": false,
	"description": "Test One",
	"discount": null,
	"email": "test1@mail.com",
	"invoice_prefix": "EFD8C32",
	"livemode": false,
	"metadata": [],
	"shipping": null,
	"sources": {...},
	"subscriptions": {...}
}
```
### Delete Customer Profile
|Field|Required| Description
|--|--|--|
|**profile_id**| *true* | The identifier of the customer to be retrieved.

```php
    $res = $stripe->delCustomerProfile([
		'profile_id' => 'cus_D5OXBI28ihasha'
    ]);
```
```json
{
	"id": "cus_D5OXBI28ihasha",
	"deleted": true
}
```
## Payment Profile
### Get Payment Profile List
|Field|Required| Description
|--|--|--|
|**profile_id**| *true* | The ID of the customer whose cards will be retrieved.
|**limit**| *false* | A limit on the number of objects to be returned. Limit can range between 1 and 100, and the default is 10.
|**starting_after**| *false* | A cursor for use in pagination. `starting_after` is an object ID that defines your place in the list. For instance, if you make a list request and receive 100 objects, ending with `obj_foo`, your subsequent call can include `starting_after=obj_foo` in order to fetch the next page of the list.

```php
    $res = $stripe->getPaymentProfileList([
		'profile_id' => 'cus_D53AAGsHlILriF',
		'limit' => 2
    ]);
```
```json
{
	"object": "list",
	"data": [{
			"id": "card_1Cetr4GeWDdV8AVTTtxUapde",
			"object": "card",
			"address_city": null,
			"address_country": null,
			"address_line1": null,
			"address_line1_check": null,
			"address_line2": null,
			"address_state": null,
			"address_zip": null,
			"address_zip_check": null,
			"brand": "Visa",
			"country": "US",
			"customer": "cus_D53AAGsHlILriF",
			"cvc_check": null,
			"dynamic_last4": null,
			"exp_month": 6,
			"exp_year": 2019,
			"fingerprint": "bUWfgOEk1ituXLPo",
			"funding": "debit",
			"last4": "5556",
			"metadata": [],
			"name": null,
			"tokenization_method": null
		},
		{
			"id": "card_1Cetq9GeWDdV8AVTRuC5mudX",
			"object": "card",
			"address_city": null,
			"address_country": null,
			"address_line1": null,
			"address_line1_check": null,
			"address_line2": null,
			"address_state": null,
			"address_zip": null,
			"address_zip_check": null,
			"brand": "Visa",
			"country": "US",
			"customer": "cus_D53AAGsHlILriF",
			"cvc_check": null,
			"dynamic_last4": null,
			"exp_month": 6,
			"exp_year": 2019,
			"fingerprint": "bUWfgOEk1ituXLPo",
			"funding": "debit",
			"last4": "5556",
			"metadata": [],
			"name": null,
			"tokenization_method": null
		}
	],
	"has_more": true,
	"url": "/v1/customers/cus_D53AAGsHlILriF/sources"
}
```
### Add Payment Profile
|Field|Required| Description
|--|--|--|
|**profile_id**| *true* | The ID of the customer whose cards will be retrieved.
|**token_id**| *true* | A token, like the ones returned by [Stripe.js](https://stripe.com/docs/stripe.js). Stripe will automatically validate the card.


```php
    $res = $stripe->addPaymentProfile([
		'profile_id' => 'cus_D53AAGsHlILriF',
		'token_id' => 'tok_visa_debit'
    ]);
```
```json
{
	"id": "card_1CfGXcGeWDdV8AVTb80T2W53",
	"object": "card",
	"address_city": null,
	"address_country": null,
	"address_line1": null,
	"address_line1_check": null,
	"address_line2": null,
	"address_state": null,
	"address_zip": null,
	"address_zip_check": null,
	"brand": "American Express",
	"country": "US",
	"customer": "cus_D53AAGsHlILriF",
	"cvc_check": null,
	"dynamic_last4": null,
	"exp_month": 6,
	"exp_year": 2019,
	"fingerprint": "ygbMfCXgRH8uSclQ",
	"funding": "credit",
	"last4": "8431",
	"metadata": [],
	"name": null,
	"tokenization_method": null
}
```
### Delete Payment Profile
|Field|Required|Description
|--|--|--|
|**profile_id**| *true* | The ID of the customer whose cards will be retrieved.
|**payment_profile_id**| *true* | The ID of the source to be deleted.

```php
    $res = $stripe->delPaymentProfile([
		'profile_id' => 'cus_D53AAGsHlILriF',
		'payment_profile_id' => 'card_1CfGXcGeWDdV8AVTb80T2W53'
    ]);
```
```json
{
	"id": "card_1CfGXcGeWDdV8AVTb80T2W53",
	"deleted": true
}
```
### Get Payment Profile
|Field|Required| Description
|--|--|--|
|**profile_id**| *true* | The ID of the customer whose cards will be retrieved.
|**payment_profile_id**| *true* | The ID of the card to be retrieved.


```php
    $res = $stripe->getPaymentProfileList([
		'profile_id' => 'cus_D53AAGsHlILriF',
		'payment_profile_id' => 'card_1Cet3rGeWDdV8AVTaPPLg4AR'
    ]);
```
```json
{
	"id": "card_1Cet3rGeWDdV8AVTaPPLg4AR",
	"object": "card",
	"address_city": "Miami",
	"address_country": "US",
	"address_line1": "1123 SW 12 Ave",
	"address_line1_check": "pass",
	"address_line2": "Ste 201",
	"address_state": "Florida",
	"address_zip": "33136",
	"address_zip_check": "pass",
	"brand": "Visa",
	"country": "US",
	"customer": "cus_D53AAGsHlILriF",
	"cvc_check": "pass",
	"dynamic_last4": null,
	"exp_month": 1,
	"exp_year": 2020,
	"fingerprint": "qhk1dunbCcem4WnV",
	"funding": "credit",
	"last4": "4242",
	"metadata": [],
	"name": "Test One",
	"tokenization_method": null
}
```
## Charge

### Charge Account
|Field|Required| Description
|--|--|--|
|**profile_id**| *true* | A payment source to be charged, such as a credit card. If you also pass a profile ID, the source must be the payment profile ID of a source belonging to the customer (e.g., a saved card). Otherwise, if you do not pass a profile ID, the source you provide must be a token, like the ones returned by [Stripe.js](https://stripe.com/docs/stripe-js).
|**source**| *true* | The ID of an existing customer that will be charged in this request.
|**amount**| *true* | A positive integer representing how much to charge
|**currency**| *false* | 3-letter [ISO code](https://stripe.com/docs/currencies) for currency.
|**description**| *false* | An arbitrary string which you can attach to a `Charge` object. It is displayed when in the web interface alongside the charge. Note that if you use Stripe to send automatic email receipts to your customers, your receipt emails will include the `description` of the charge(s) that they are describing.
|**statement_descriptor**| *false* | An arbitrary string to be displayed on your customer's credit card statement. This can be up to _22 characters_. As an example, if your website is `RunClub` and the item you're charging for is a race ticket, you might want to specify a `statement_descriptor` of `RunClub 5K race ticket`. The statement description must contain at least one letter, must not contain `<>"'` characters, and will appear on your customer's statement in capital letters. Non-ASCII characters are automatically stripped. While most banks and card issuers display this information consistently, some might display it incorrectly or not at all.

```php
    $res = $stripe->chargeAccount([
		'profile_id' => 'cus_D53AAGsHlILriF',
		'amount' => '10.50',
		'source' => 'card_1Cet3rGeWDdV8AVTaPPLg4AR',
	]);
```
```json
{
	"id": "ch_1Cf9TyGeWDdV8AVToBOoJs8T",
	"object": "charge",
	"amount": 1050,
	"amount_refunded": 0,
	"application": null,
	"application_fee": null,
	"balance_transaction": "txn_1Cf9TzGeWDdV8AVTBGXTCBmY",
	"captured": true,
	"created": 1529513314,
	"currency": "usd",
	"customer": "cus_D53AAGsHlILriF",
	"description": null,
	"destination": null,
	"dispute": null,
	"failure_code": null,
	"failure_message": null,
	"fraud_details": [],
	"invoice": null,
	"livemode": false,
	"metadata": [],
	"on_behalf_of": null,
	"order": null,
	"outcome": {
		"network_status": "approved_by_network",
		"reason": null,
		"risk_level": "normal",
		"seller_message": "Payment complete.",
		"type": "authorized"
	},
	"paid": true,
	"receipt_email": null,
	"receipt_number": null,
	"refunded": false,
	"refunds": {
		"object": "list",
		"data": [],
		"has_more": false,
		"total_count": 0,
		"url": "/v1/charges/ch_1Cf9TyGeWDdV8AVToBOoJs8T/refunds"
	},
	"review": null,
	"shipping": null,
	"source": {
		"id": "card_1Cet3rGeWDdV8AVTaPPLg4AR",
		"object": "card",
		"address_city": "Miami",
		"address_country": "US",
		"address_line1": "1123 SW 12 Ave",
		"address_line1_check": "pass",
		"address_line2": "Ste 201",
		"address_state": "Florida",
		"address_zip": "33136",
		"address_zip_check": "pass",
		"brand": "Visa",
		"country": "US",
		"customer": "cus_D53AAGsHlILriF",
		"cvc_check": null,
		"dynamic_last4": null,
		"exp_month": 1,
		"exp_year": 2020,
		"fingerprint": "qhk1dunbCcem4WnV",
		"funding": "credit",
		"last4": "4242",
		"metadata": [],
		"name": "Test One",
		"tokenization_method": null
	},
	"source_transfer": null,
	"statement_descriptor": "PROPEROS*CHATSON",
	"status": "succeeded",
	"transfer_group": null
}
```

### Authorize Account
|Field|Required| Description
|--|--|--|
|**profile_id**| *true* | A payment source to be charged, such as a credit card. If you also pass a profile ID, the source must be the payment profile ID of a source belonging to the customer (e.g., a saved card). Otherwise, if you do not pass a profile ID, the source you provide must be a token, like the ones returned by [Stripe.js](https://stripe.com/docs/stripe-js).
|**source**| *true* | The ID of an existing customer that will be charged in this request.
|**amount**| *true* | A positive integer representing how much to charge
|**currency**| *false* | 3-letter [ISO code](https://stripe.com/docs/currencies) for currency.
|**description**| *false* | An arbitrary string which you can attach to a `Charge` object. It is displayed when in the web interface alongside the charge. Note that if you use Stripe to send automatic email receipts to your customers, your receipt emails will include the `description` of the charge(s) that they are describing.
|**statement_descriptor**| *false* | An arbitrary string to be displayed on your customer's credit card statement. This can be up to _22 characters_. As an example, if your website is `RunClub` and the item you're charging for is a race ticket, you might want to specify a `statement_descriptor` of `RunClub 5K race ticket`. The statement description must contain at least one letter, must not contain `<>"'` characters, and will appear on your customer's statement in capital letters. Non-ASCII characters are automatically stripped. While most banks and card issuers display this information consistently, some might display it incorrectly or not at all.

```php
    $res = $stripe->authorizeAccount([
		'profile_id' => 'cus_D53AAGsHlILriF',
		'amount' => '10.50',
		'source' => 'card_1Cet3rGeWDdV8AVTaPPLg4AR',
	]);
```
```json
{
	"id": "ch_1Cf9zgGeWDdV8AVTpIriaRZm",
	"object": "charge",
	"amount": 1050,
	"amount_refunded": 0,
	"application": null,
	"application_fee": null,
	"balance_transaction": null,
	"captured": false,
	"created": 1529515280,
	"currency": "usd",
	"customer": "cus_D53AAGsHlILriF",
	"description": null,
	"destination": null,
	"dispute": null,
	"failure_code": null,
	"failure_message": null,
	"fraud_details": [],
	"invoice": null,
	"livemode": false,
	"metadata": [],
	"on_behalf_of": null,
	"order": null,
	"outcome": {
		"network_status": "approved_by_network",
		"reason": null,
		"risk_level": "normal",
		"seller_message": "Payment complete.",
		"type": "authorized"
	},
	"paid": true,
	"receipt_email": null,
	"receipt_number": null,
	"refunded": false,
	"refunds": {
		"object": "list",
		"data": [],
		"has_more": false,
		"total_count": 0,
		"url": "/v1/charges/ch_1Cf9zgGeWDdV8AVTpIriaRZm/refunds"
	},
	"review": null,
	"shipping": null,
	"source": {
		"id": "card_1Cet3rGeWDdV8AVTaPPLg4AR",
		"object": "card",
		"address_city": "Miami",
		"address_country": "US",
		"address_line1": "1123 SW 12 Ave",
		"address_line1_check": "pass",
		"address_line2": "Ste 201",
		"address_state": "Florida",
		"address_zip": "33136",
		"address_zip_check": "pass",
		"brand": "Visa",
		"country": "US",
		"customer": "cus_D53AAGsHlILriF",
		"cvc_check": null,
		"dynamic_last4": null,
		"exp_month": 1,
		"exp_year": 2020,
		"fingerprint": "qhk1dunbCcem4WnV",
		"funding": "credit",
		"last4": "4242",
		"metadata": [],
		"name": "Test One",
		"tokenization_method": null
	},
	"source_transfer": null,
	"statement_descriptor": "PROPEROS*CHATSON",
	"status": "succeeded",
	"transfer_group": null
}
```

### Capture Account
|Field|Required| Description
|--|--|--|
|**charge**| *true* | The identifier of the charge to be retrieved.

```php
    $res = $stripe->captureAccount([
		'charge' => 'ch_1Cf9zgGeWDdV8AVTpIriaRZm'
	]);
```
```json
{
	"id": "ch_1Cf9zgGeWDdV8AVTpIriaRZm",
	"object": "charge",
	"amount": 1050,
	"amount_refunded": 0,
	"application": null,
	"application_fee": null,
	"balance_transaction": "txn_1CfA7PGeWDdV8AVTMvarUNjm",
	"captured": true,
	"created": 1529515280,
	"currency": "usd",
	"customer": "cus_D53AAGsHlILriF",
	"description": null,
	"destination": null,
	"dispute": null,
	"failure_code": null,
	"failure_message": null,
	"fraud_details": [],
	"invoice": null,
	"livemode": false,
	"metadata": [],
	"on_behalf_of": null,
	"order": null,
	"outcome": {
		"network_status": "approved_by_network",
		"reason": null,
		"risk_level": "normal",
		"seller_message": "Payment complete.",
		"type": "authorized"
	},
	"paid": true,
	"receipt_email": null,
	"receipt_number": null,
	"refunded": false,
	"refunds": {
		"object": "list",
		"data": [],
		"has_more": false,
		"total_count": 0,
		"url": "/v1/charges/ch_1Cf9zgGeWDdV8AVTpIriaRZm/refunds"
	},
	"review": null,
	"shipping": null,
	"source": {
		"id": "card_1Cet3rGeWDdV8AVTaPPLg4AR",
		"object": "card",
		"address_city": "Miami",
		"address_country": "US",
		"address_line1": "1123 SW 12 Ave",
		"address_line1_check": "pass",
		"address_line2": "Ste 201",
		"address_state": "Florida",
		"address_zip": "33136",
		"address_zip_check": "pass",
		"brand": "Visa",
		"country": "US",
		"customer": "cus_D53AAGsHlILriF",
		"cvc_check": null,
		"dynamic_last4": null,
		"exp_month": 1,
		"exp_year": 2020,
		"fingerprint": "qhk1dunbCcem4WnV",
		"funding": "credit",
		"last4": "4242",
		"metadata": [],
		"name": "Test One",
		"tokenization_method": null
	},
	"source_transfer": null,
	"statement_descriptor": "PROPEROS*CHATSON",
	"status": "succeeded",
	"transfer_group": null
}
```

### Get Transaction
|Field|Required| Description
|--|--|--|
|**charge**| *true* | The identifier of the charge to be retrieved.

```php
    $res = $stripe->getTransaction([
		'charge' => 'ch_1Cf9zgGeWDdV8AVTpIriaRZm'
	]);
```
```json
{
	"id": "ch_1Cf9zgGeWDdV8AVTpIriaRZm",
	"object": "charge",
	"amount": 1050,
	"amount_refunded": 0,
	"application": null,
	"application_fee": null,
	"balance_transaction": "txn_1CfA7PGeWDdV8AVTMvarUNjm",
	"captured": true,
	"created": 1529515280,
	"currency": "usd",
	"customer": "cus_D53AAGsHlILriF",
	"description": null,
	"destination": null,
	"dispute": null,
	"failure_code": null,
	"failure_message": null,
	"fraud_details": [],
	"invoice": null,
	"livemode": false,
	"metadata": [],
	"on_behalf_of": null,
	"order": null,
	"outcome": {
		"network_status": "approved_by_network",
		"reason": null,
		"risk_level": "normal",
		"seller_message": "Payment complete.",
		"type": "authorized"
	},
	"paid": true,
	"receipt_email": null,
	"receipt_number": null,
	"refunded": false,
	"refunds": {
		"object": "list",
		"data": [],
		"has_more": false,
		"total_count": 0,
		"url": "/v1/charges/ch_1Cf9zgGeWDdV8AVTpIriaRZm/refunds"
	},
	"review": null,
	"shipping": null,
	"source": {
		"id": "card_1Cet3rGeWDdV8AVTaPPLg4AR",
		"object": "card",
		"address_city": "Miami",
		"address_country": "US",
		"address_line1": "1123 SW 12 Ave",
		"address_line1_check": "pass",
		"address_line2": "Ste 201",
		"address_state": "Florida",
		"address_zip": "33136",
		"address_zip_check": "pass",
		"brand": "Visa",
		"country": "US",
		"customer": "cus_D53AAGsHlILriF",
		"cvc_check": null,
		"dynamic_last4": null,
		"exp_month": 1,
		"exp_year": 2020,
		"fingerprint": "qhk1dunbCcem4WnV",
		"funding": "credit",
		"last4": "4242",
		"metadata": [],
		"name": "Test One",
		"tokenization_method": null
	},
	"source_transfer": null,
	"statement_descriptor": "PROPEROS*CHATSON",
	"status": "succeeded",
	"transfer_group": null
}
```
### Get Transaction History
|Field|Required| Description
|--|--|--|
|**payment_profile_type**| *false* | Return charges that match this source type string. Available options are `all`, `alipay_account`, `bank_account`, `bitcoin_receiver`, or `card`.
|**profile_id**| *false* | Only return charges for the customer specified by this profile ID.
|**limit**| *false* | A limit on the number of objects to be returned. Limit can range between 1 and 100, and the default is 10
|**starting_after**| *false* | A cursor for use in pagination. `starting_after` is an object ID that defines your place in the list. For instance, if you make a list request and receive 100 objects, ending with `obj_foo`, your subsequent call can include `starting_after=obj_foo` in order to fetch the next page of the list.
```php
    $res = $stripe->getTransactionHistory([
		'payment_profile_type' => 'card',
		'profile_id' => 'cus_ClUaGtDYBHoWNw',
		'limit' => 5,
		'starting_after' => 'ch_1CMQOyGeWDdV8AVTOPJLOIPu'
	]);
```
```json
{
	"object": "list",
	"data": [{
			"id": "ch_1CMQOoGeWDdV8AVTQx50wwtX",
			"object": "charge",
			"amount": 1000,
			"amount_refunded": 0,
			"application": null,
			"application_fee": null,
			"balance_transaction": "txn_1CMQOoGeWDdV8AVTDsgb5Qne",
			"captured": true,
			"created": 1525050110,
			"currency": "usd",
			"customer": "cus_ClUaGtDYBHoWNw",
			"description": null,
			"destination": null,
			"dispute": null,
			"failure_code": null,
			"failure_message": null,
			"fraud_details": [],
			"invoice": "in_1CMQOoGeWDdV8AVTQ9gy5wnW",
			"livemode": false,
			"metadata": [],
			"on_behalf_of": null,
			"order": null,
			"outcome": {
				"network_status": "approved_by_network",
				"reason": null,
				"risk_level": "normal",
				"seller_message": "Payment complete.",
				"type": "authorized"
			},
			"paid": true,
			"receipt_email": null,
			"receipt_number": null,
			"refunded": false,
			"refunds": {
				"object": "list",
				"data": [],
				"has_more": false,
				"total_count": 0,
				"url": "/v1/charges/ch_1CMQOoGeWDdV8AVTQx50wwtX/refunds"
			},
			"review": null,
			"shipping": null,
			"source": {
				"id": "card_1CM0kKGeWDdV8AVTtwvPgF2B",
				"object": "card",
				"address_city": "Miami",
				"address_country": "United States",
				"address_line1": "123 SW 12 ST",
				"address_line1_check": "pass",
				"address_line2": "Suite #202",
				"address_state": "FL",
				"address_zip": "33186",
				"address_zip_check": "pass",
				"brand": "Visa",
				"country": "US",
				"customer": "cus_ClUaGtDYBHoWNw",
				"cvc_check": null,
				"dynamic_last4": null,
				"exp_month": 12,
				"exp_year": 2021,
				"fingerprint": "bUWfgOEk1ituXLPo",
				"funding": "debit",
				"last4": "5556",
				"metadata": [],
				"name": "John Doe",
				"tokenization_method": null
			},
			"source_transfer": null,
			"statement_descriptor": null,
			"status": "succeeded",
			"transfer_group": null
		},
		...
 		{
			"id": "ch_1CMQ7MGeWDdV8AVTHVaQJP4V",
			"object": "charge",
			"amount": 3500,
			"amount_refunded": 0,
			"application": null,
			"application_fee": null,
			"balance_transaction": "txn_1CMQ7MGeWDdV8AVTY1MhT63A",
			"captured": true,
			"created": 1525049028,
			"currency": "usd",
			"customer": "cus_ClUaGtDYBHoWNw",
			"description": null,
			"destination": null,
			"dispute": null,
			"failure_code": null,
			"failure_message": null,
			"fraud_details": [],
			"invoice": "in_1CMQ7MGeWDdV8AVTFg9ikO4n",
			"livemode": false,
			"metadata": [],
			"on_behalf_of": null,
			"order": null,
			"outcome": {...},
			"paid": true,
			"receipt_email": null,
			"receipt_number": null,
			"refunded": false,
			"refunds": {...},
			"review": null,
			"shipping": null,
			"source": {...},
			"source_transfer": null,
			"statement_descriptor": null,
			"status": "succeeded",
			"transfer_group": null
		}
	],
	"has_more": false,
	"url": "/v1/charges"
}
```

### Refund Transaction
|Field|Required| Description
|--|--|--|
|**charge**| *true* | The identifier of the charge to refund
|**amount**| *false* | A positive integer in _cents_ representing how much of this charge to refund. Can refund only up to the remaining, unrefunded amount of the charge.
|**reason**| *false* | String indicating the reason for the refund. If set, possible values are `duplicate`, `fraudulent`, and `requested_by_customer`. If you believe the charge to be fraudulent, specifying `fraudulent` as the reason will add the associated card and email to your [blocklists](https://stripe.com/docs/radar/lists), and will also help us improve our fraud detection algorithms.

```php
    $res = $stripe->refundTransaction([
		'charge' => 'ch_1Cf9zgGeWDdV8AVTpIriaRZm'
	]);
```
```json
{
	"id": "re_1CfDI0GeWDdV8AVTM0k4yerk",
	"object": "refund",
	"amount": 1050,
	"balance_transaction": "txn_1CfDI0GeWDdV8AVTkQLh0Bxu",
	"charge": "ch_1Cf9zgGeWDdV8AVTpIriaRZm",
	"created": 1529527948,
	"currency": "usd",
	"metadata": [],
	"reason": null,
	"receipt_number": null,
	"status": "succeeded"
}
```