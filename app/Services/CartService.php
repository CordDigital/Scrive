<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    const KEY = 'cart';

    public function all(): array
    {
        return Session::get(self::KEY, []);
    }

    public function add(Product $product, int $qty = 1, ?string $size = null, ?string $color = null): void
    {
        $cart = $this->all();
        $key  = $product->id . '-' . $size . '-' . $color;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $qty;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'name_ar'    => $product->name_ar,
                'name_en'    => $product->name_en,
                'price'      => $product->price,
                'image'      => $product->image,
                'quantity'   => $qty,
                'size'       => $size,
                'color'      => $color,
            ];
        }

        Session::put(self::KEY, $cart);
    }

    public function update(string $key, int $qty): void
    {
        $cart = $this->all();
        if (isset($cart[$key])) {
            if ($qty <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key]['quantity'] = $qty;
            }
            Session::put(self::KEY, $cart);
        }
    }

    public function remove(string $key): void
    {
        $cart = $this->all();
        unset($cart[$key]);
        Session::put(self::KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::KEY);
        Session::forget('coupon');
    }

    public function count(): int
    {
        return array_sum(array_column($this->all(), 'quantity'));
    }

    public function subtotal(): float
    {
        $total = 0;
        foreach ($this->all() as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return round($total, 2);
    }

    public function isEmpty(): bool
    {
        return empty($this->all());
    }

    public function has($productId, $size = null, $color = null): bool
    {
        foreach ($this->all() as $item) {
            if ($item['product_id'] == $productId
                && $item['size'] == $size
                && $item['color'] == $color) {
                return true;
            }
        }
        return false;
    }
}
