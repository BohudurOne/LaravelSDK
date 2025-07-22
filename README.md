# Bohudur Laravel SDK

<img src="https://bohudur.one/bohudurlogo.png" alt="Bohudur Logo" width="328"/>

This is Bohudur Laravel Module that helps to integrate Bohudur in your Laravel project.

---

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
  - [Initializing the SDK](#initializing-the-sdk)
  - [Initializing a Payment](#initializing-a-payment-bangladeshi-methods)
  - [Verifying a Payment](#verifying-a-payment)
- [Routes](#routes)
- [Notes](#notes)

---

## Installation

Install the Bohudur Laravel SDK using Composer:

```bash
composer require bohudur/laravel-sdk
```

---

## Usage

### Initializing the SDK

Add your Bohudur API Key to the `.env` file:

```env
BOHUDUR_API_KEY=your_api_key_here
```

Use the following code to initialize the SDK:

```php
use Bohudur\LaravelSDK\Bohudur;

$bohudur = Bohudur::make(env('BOHUDUR_API_KEY'));
```

---

### Initializing a Payment (Bangladeshi Methods)

To initiate a payment:

```php
use Bohudur\LaravelSDK\Requests\CheckoutRequest;

try {
    $checkoutRequest = CheckoutRequest::make()
        ->setFullName('John Doe')
        ->setEmail('john@doe.com')
        ->setAmount('10')
        ->addMetadata('order_id', '12345')
        ->setRedirectUrl(route('uddoktapay.verify'))
        ->setCancelUrl(route('uddoktapay.cancel'))
        ->setWebhookUrl(route('uddoktapay.ipn'));

    $response = $uddoktapay->checkout($checkoutRequest);

    if ($response->failed()) {
        dd($response->message());
    }

    return redirect($response->paymentURL());
} catch (\UddoktaPay\LaravelSDK\Exceptions\UddoktaPayException $e) {
    dd("Initialization Error: " . $e->getMessage());
}
```
---

### Verifying a Payment

After the payment is complete, verify it using the `VerifyResponse` class to understand the structure and available methods for processing the response:

```php
try {
    $response = $uddoktapay->verify($request);

    if ($response->success()) {
        // Handle successful status
        dd($response->toArray()); // Handle success
    } elseif ($response->pending()) {
        // Handle pending status
    } elseif ($response->failed()) {
        // Handle failure
    }
} catch (\UddoktaPay\LaravelSDK\Exceptions\UddoktaPayException $e) {
    dd("Verification Error: " . $e->getMessage());
}
```

---

## Routes

Add the following routes to your `web.php` file:

```php
use App\Http\Controllers\UddoktaPayController;

Route::get('/checkout', [UddoktaPayController::class, 'checkout'])->name('uddoktapay.checkout');
Route::get('/verify', [UddoktaPayController::class, 'verify'])->name('uddoktapay.verify');
Route::get('/cancel', [UddoktaPayController::class, 'cancel'])->name('uddoktapay.cancel');
Route::post('/ipn', [UddoktaPayController::class, 'ipn'])->name('uddoktapay.ipn');
Route::post('/refund', [UddoktaPayController::class, 'refund'])->name('uddoktapay.refund');
```

---

## Notes

- Replace placeholders like `your_api_key` with actual credentials.
- Use appropriate routes for success, cancel, and IPN handling.
- Always wrap SDK calls with `try-catch` to handle errors effectively.

---

## License

This project is open-source and available under the [MIT License](https://opensource.org/licenses/MIT).
