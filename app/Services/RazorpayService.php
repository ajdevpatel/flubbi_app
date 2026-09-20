<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Throwable;

class RazorpayService
{
    private ?Api $api = null;

    public function key(): string
    {
        return trim((string) config("services.razorpay.key", ""));
    }

    public function secret(): string
    {
        return trim((string) config("services.razorpay.secret", ""));
    }

    public function isConfigured(): bool
    {
        return "" !== $this->key() && "" !== $this->secret();
    }

    private function api(): Api
    {
        return $this->api ??= new Api($this->key(), $this->secret());
    }

    public function createOrder(float $amount, string $receipt, array $notes = []): array
    {
        try {
            $order = $this->api()->order->create([
                "amount" => (int) round($amount * 100),
                "currency" => "INR",
                "receipt" => $receipt,
                "payment_capture" => 1,
                "notes" => $notes,
            ]);

            return [
                "status" => true,
                "id" => (string) $order["id"],
                "amount" => (int) $order["amount"],
                "currency" => (string) $order["currency"],
            ];
        } catch (Throwable $e) {
            Log::warning("Razorpay order failed", ["receipt" => $receipt, "error" => $e->getMessage()]);

            return [
                "status" => false,
                "message" => "Could not start the payment right now. Please try again in a moment.",
            ];
        }
    }

    public function verifySignature(string $order_id, string $payment_id, string $signature): bool
    {
        try {
            $this->api()->utility->verifyPaymentSignature([
                "razorpay_order_id" => $order_id,
                "razorpay_payment_id" => $payment_id,
                "razorpay_signature" => $signature,
            ]);
            return true;
        } catch (Throwable $e) {
            Log::warning("Razorpay signature rejected", ["order_id" => $order_id, "payment_id" => $payment_id, "error" => $e->getMessage()]);
            return false;
        }
    }
}
