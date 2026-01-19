# RelayWarden PHP SDK

Official PHP SDK for the RelayWarden API v1.

## Installation

```bash
composer require relaywarden/php-sdk
```

## Quick Start

```php
<?php

use RelayWarden\Client;

// Initialize the client
$client = new Client(
    'https://api.relaywarden.eu/api/v1',
    'your-api-token'
);

// Set project ID for project-scoped operations
$client->setProjectId('your-project-id');

// Send a message
$message = $client->messages()->send([
    'from' => [
        'email' => 'noreply@example.com',
        'name' => 'Acme Corp'
    ],
    'to' => [
        ['email' => 'user@example.com']
    ],
    'subject' => 'Welcome!',
    'html' => '<h1>Welcome!</h1>',
    'text' => 'Welcome!'
]);

echo "Message ID: " . $message['data']['message_id'];
```

## Authentication

The SDK uses Bearer token authentication. Pass your API token when creating the client:

```php
$client = new Client(
    'https://api.relaywarden.eu/api/v1',
    'your-api-token'
);
```

## Resources

### Identity

```php
// Get current user/service account info
$me = $client->identity()->me();

// List teams
$teams = $client->identity()->teams();
```

### Projects

```php
// List projects
$projects = $client->projects()->list(['environment' => 'production']);

// Create project
$project = $client->projects()->create([
    'name' => 'Production',
    'environment' => 'production'
]);

// Get project
$project = $client->projects()->get('project-id');

// Update project
$project = $client->projects()->update('project-id', [
    'name' => 'Updated Name'
]);

// Delete project
$client->projects()->delete('project-id');
```

### Messages

```php
// Send message with idempotency key
$message = $client->messages()->send([
    'from' => ['email' => 'noreply@example.com'],
    'to' => [['email' => 'user@example.com']],
    'subject' => 'Hello',
    'html' => '<h1>Hello</h1>'
], 'unique-idempotency-key');

// List messages
$messages = $client->messages()->list([
    'status' => 'delivered',
    'per_page' => 25
]);

// Get message
$message = $client->messages()->get('message-id');

// Get message timeline
$timeline = $client->messages()->getTimeline('message-id');

// Cancel message
$client->messages()->cancel('message-id');

// Resend message
$client->messages()->resend('message-id');
```

### Templates

```php
// Create template
$template = $client->templates()->create([
    'name' => 'Welcome Email',
    'subject' => 'Welcome {{ $name }}!',
    'html_body' => '<h1>Welcome {{ $name }}!</h1>',
    'text_body' => 'Welcome {{ $name }}!'
]);

// Render template
$rendered = $client->templates()->render('template-id', [
    'data' => ['name' => 'John']
]);

// Test send
$client->templates()->testSend('template-id', [
    'to' => 'test@example.com',
    'data' => ['name' => 'John']
]);
```

### Domains

```php
// Create domain
$domain = $client->domains()->create([
    'domain' => 'mail.example.com'
]);

// Verify domain
$result = $client->domains()->verify('domain-id');

// Get DNS records
$records = $client->domains()->getDnsRecords('domain-id');

// Rotate DKIM keys
$client->domains()->rotateDkim('domain-id');
```

## Error Handling

The SDK throws specific exception types for different error scenarios:

```php
use RelayWarden\Exceptions\AuthenticationException;
use RelayWarden\Exceptions\RateLimitException;
use RelayWarden\Exceptions\ValidationException;
use RelayWarden\Exceptions\ApiException;

try {
    $message = $client->messages()->send([...]);
} catch (AuthenticationException $e) {
    // 401 - Invalid or missing token
    echo "Authentication failed: " . $e->getMessage();
} catch (ValidationException $e) {
    // 422 - Validation errors
    echo "Validation failed: " . $e->getMessage();
    foreach ($e->getErrorDetails() as $detail) {
        echo $detail['field'] . ": " . $detail['message'];
    }
} catch (RateLimitException $e) {
    // 429 - Rate limit exceeded
    echo "Rate limit exceeded. Retry after: " . $e->getRetryAfter() . " seconds";
} catch (ApiException $e) {
    // Other API errors
    echo "API Error: " . $e->getMessage();
    echo "Request ID: " . $e->getRequestId();
}
```

## Pagination

List endpoints return paginated responses:

```php
$response = $client->messages()->list();

// Access pagination metadata
$currentPage = $response['meta']['current_page'];
$total = $response['meta']['total'];
$lastPage = $response['meta']['last_page'];

// Access data
$messages = $response['data'];
```

## Rate Limiting

The SDK automatically handles rate limits with exponential backoff. Rate limit information is available in the exception:

```php
try {
    $client->messages()->send([...]);
} catch (RateLimitException $e) {
    $retryAfter = $e->getRetryAfter(); // Seconds to wait
    // SDK will automatically retry, but you can also handle manually
}
```

## Configuration

```php
$client = new Client(
    'https://api.relaywarden.eu/api/v1',
    'your-token',
    [
        'max_retries' => 3,
        'timeout' => 30
    ]
);
```

## Testing

```bash
composer test
composer test-coverage
```

## License

MIT
