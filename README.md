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
        ->setFullName("John Doe")
        ->setEmail("john@example.com")
        ->setAmount(10)
        ->setRedirectUrl(route('bohudur.execute'))
        ->setCancelUrl(route('bohudur.cancel'))
        ->setCurrency("BDT") //optional
        ->setCurrencyValue(1) //optional
        ->setMetadata([ /*optional */
            'order_id' => 1234,
            'user_id' => 5678,
            'custom_note' => 'First-time purchase'
        ])
        ->setWebhookSuccessUrl("https://yourapp.com/webhook/success") //optional
        ->setWebhookCancelUrl("https://yourapp.com/webhook/cancel"); //optional

    $response = $bohudur->checkout($checkoutRequest);

    if ($response->failed()) {
        dd($response->message());
    }
    
    return redirect()->away($response->paymentURL());
} catch (\Bohudur\LaravelSDK\Exceptions\BohudurException $e) {
    dd("Initialization Error: " . $e->getMessage());
}
```
---

### Execute a Payment

After the payment is complete, execute it using the `ExecuteResponse` class so that one payment key is used only one time. Also to understand the structure and available methods for processing the response:

```php
use Bohudur\LaravelSDK\Bohudur;

try {
    $bohudur = Bohudur::make(env('BOHUDUR_API_KEY'));
  
    $response = $bohudur->execute('paymentkey');

    if ($response->success()) {
        // Handle successful status
        return response()->json([
            'status' => 'success',
            'transaction_id' => $response->transactionId(),
            'amount' => $response->amount(),
        ]);
    } elseif ($response->pending()) {
        // Handle pending status
    } elseif ($response->failed()) {
        // Handle failure
    }
} catch (\Bohudur\LaravelSDK\Exceptions\BohudurException $e) {
    dd("Verification Error: " . $e->getMessage());
}
```

---

### Verify a Payment

After the payment is complete, you can verify it using the `VerifyResponse` class to understand the structure and available methods for processing the response:

```php
use Bohudur\LaravelSDK\Bohudur;

try {
    $bohudur = Bohudur::make(env('BOHUDUR_API_KEY'));
  
    $response = $bohudur->verify('paymentkey');

    if ($response->success()) {
        // Handle successful status
        return response()->json([
            'status' => 'success',
            'transaction_id' => $response->transactionId(),
            'amount' => $response->amount(),
        ]);
    } elseif ($response->pending()) {
        // Handle pending status
    } elseif ($response->failed()) {
        // Handle failure
    }
} catch (\Bohudur\LaravelSDK\Exceptions\BohudurException $e) {
    dd("Verification Error: " . $e->getMessage());
}
```

---

## Routes

Add the following routes to your `web.php` file:

```php
use App\Http\Controllers\BohudurController;

Route::get('/checkout', [BohudurController::class, 'checkout'])->name('bohudur.checkout');
Route::get('/verify', [BohudurController::class, 'verify'])->name('bohudur.verify');
Route::get('/cancel', [BohudurController::class, 'cancel'])->name('bohudur.cancel');
```

---

## Notes

- Replace placeholders like `your_api_key_here` with actual credentials.
- Always wrap SDK calls with `try-catch` to handle errors effectively.

---

## License

This project is open-source and available under the [MIT License](https://opensource.org/licenses/MIT).
