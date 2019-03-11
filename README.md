# GA Measurement Protocol PHP Library

This simple library lets you send server side track events to Google Analytics.

## Installation

This library is best installed via composer. Via the command line: `composer require kitbrennan/ga-measurement-protocol`

If you are using PSR-4 (or a modern framework), simply import the client at the top of your script:
```php
use GaMeasurementProtocol\Client;
```

If you are not using PSR-4, you should require the composer autoloader: `require_once('vendor/autoload.php');`

## Using the library

### Initialise the Client

```php
$trackingId = 'GA-123456-1'; // Take the tracking/property ID from your Google Analytics account
$client = new Client($trackingId);
```

### Sending a pageview

The library defaults to firing pageviews.

```php
$trackingId = 'GA-123456-1'; // Take the tracking/property ID from your Google Analytics account
$client = new Client($trackingId);

$params = [
    'dl' => 'http://foo.com/home?a=b' // Document location, required for a 'pageview' type
];

$client->request()
   ->setParameters($params)
   ->send();
```

__Not working?__ The Measurement Protocol will not raise any errors by default, please explore the debug section below 
if your results are not showing in Google Analytics the issue.

### Sending other hit types (eg. event, screenview)

The library supports every tracking type (available as constants):

* GaMeasurementProtocol\Enums\HitType::PAGEVIEW
* GaMeasurementProtocol\Enums\HitType::SCREENVIEW
* GaMeasurementProtocol\Enums\HitType::EVENT
* GaMeasurementProtocol\Enums\HitType::TRANSACTION
* GaMeasurementProtocol\Enums\HitType::ITEM
* GaMeasurementProtocol\Enums\HitType::SOCIAL
* GaMeasurementProtocol\Enums\HitType::EXCEPTION
* GaMeasurementProtocol\Enums\HitType::TIMING 

Just call `setHitType($type)` on the request:

```php
$trackingId = 'GA-123456-1'; // Take the tracking/property ID from your Google Analytics account
$client = new Client($trackingId);

$params = [
    'dl' => 'http://foo.com/home?a=b' // Document location, required for a 'pageview' type
];

$client->request()
   ->setParameters($params)
   ->setHitType(GaMeasurementProtocol\Enums\HitType::EVENT)
   ->send();
```

### Sending additional parameters

The full supported parameter list can be found here: https://developers.google.com/analytics/devguides/collection/protocol/v1/parameters

Add all your optional parameters to an array, and call `setParameters($params)` on the request object: 

```php
$trackingId = 'GA-123456-1'; // Take the tracking/property ID from your Google Analytics account
$client = new Client($trackingId);

$params = [
    'dl' => 'http://foo.com/home?a=b', // Document location, required for a 'pageview' type
    'ul' => 'en-us' // User language
];

$client->request()
   ->setParameters($params)
   ->send();
```

### Debugging

The Measurement Protocol always returns 200, even if parameters are invalid. You instead need to enable debug mode, which 
will var_dump the debug results to your view.

__Note__ If debug mode is turned on, the results will NOT appear in your Google Analytics.

Once debug mode says your hit is valid, you should turn debug mode off and send your request again.

```php
$trackingId = 'GA-123456-1'; // Take the tracking/property ID from your Google Analytics account
$client = new Client($trackingId);

$params = [
    'dl' => 'http://foo.com/home?a=b' // Document location, required for a 'pageview' type
];

$client->setDebug(true)
   ->request()
   ->setParameters($params)
   ->send();
```
