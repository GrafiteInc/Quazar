<?php

namespace Yab\Hadron\Helpers;

use Yab\Quarx\Services\FileService;

class StoreHelper
{
    public static function storeUrl($url)
    {
        return url('store/'.$url);
    }

    public static function productUrl($url)
    {
        return url('store/product/'.$url);
    }

    public static function customer()
    {
        return app(\Yab\Hadron\Services\CustomerProfileService::class);
    }

    public static function customerSubscriptionUrl($subscription)
    {
        return url('store/account/subscriptions/'.crypto_encrypt($subscription->name));
    }

    public static function subscriptionPlan($subscription)
    {
        return app(\Yab\Hadron\Models\Plan::class)->getPlansByStripeId($subscription->stripe_plan);
    }

    public static function subscriptionUpcoming($subscription)
    {
        $key = $subscription->stripe_id.'__'.auth()->id();

        if (!\Cache::has($key)) {
            $invoice = auth()->user()->meta->upcomingInvoice($subscription->name);
            \Cache::put($key, [
                'total' => round(($invoice->total / 100), 2),
                'attempt_count' => $invoice->attempt_count,
                'period_start' => $invoice->period_start,
                'period_end' => $invoice->period_end,
                'date' => \Carbon\Carbon::createFromTimestamp($invoice->date),
            ], 25);
        }

        return \Cache::get($key);
    }

    public static function subscriptionUrl($id)
    {
        return url('store/plan/'.crypto_encrypt($id));
    }

    public static function subscribeBtn($id, $class = 'btn btn-primary')
    {
        return '<form method="post" action="'.url('store/subscribe/'.crypto_encrypt($id)).'">'.csrf_field().'<button class="'.$class.'">Subscribe</button></form>';
    }

    public static function cancelSubscriptionBtn($subscription, $class = 'btn btn-danger')
    {
        return '<form method="post" action="'.url('store/account/subscriptions/'.crypto_encrypt($subscription->name)).'/cancel">'
        .csrf_field()
        .'<input type="hidden" name="stripe_id" value="'.crypto_encrypt($subscription->stripe_id).'">'
        .'<button class="'.$class.'">Cancel Subscription</button></form>';
    }

    public static function subscriptionFrequency($interval)
    {
        switch ($interval) {
            case 'week':
                return 'weekly';
            case 'month':
                return 'monthly';
            case 'year':
                return 'yearly';
            default:
                return $interval;
        }
    }

    public static function heroImage($product)
    {
        return FileService::fileAsPublicAsset($product->hero_image);
    }

    public static function productVariants($product)
    {
        return app(\Yab\Hadron\Services\ProductService::class)->variants($product);
    }

    public static function variantOptions($variant)
    {
        return app(\Yab\Hadron\Services\ProductService::class)->variantOptions($variant);
    }

    public static function productDetails($product)
    {
        return app(\Yab\Hadron\Services\ProductService::class)->productDetails($product);
    }

    public static function productDetailsBtn($product, $class = '')
    {
        return app(\Yab\Hadron\Services\ProductService::class)->productDetailsBtn($product, $class);
    }

    public static function addToCartBtn($id, $type, $content, $class = '')
    {
        return app(\Yab\Hadron\Services\CartService::class)->addToCartBtn($id, $type, $content, $class);
    }

    public static function removeFromCartBtn($id, $type, $content, $class = '')
    {
        return app(\Yab\Hadron\Services\CartService::class)->removeFromCartBtn($id, $type, $content, $class);
    }

    public static function checkoutTax()
    {
        return app(\Yab\Hadron\Services\CartService::class)->getCartTax();
    }

    public static function checkoutTotal()
    {
        return app(\Yab\Hadron\Services\CartService::class)->getCartTotal();
    }

    public static function checkoutSubtotal()
    {
        return app(\Yab\Hadron\Services\CartService::class)->getCartSubtotal();
    }

    public static function checkoutShipping()
    {
        return app(\Yab\Hadron\Services\LogisticService::class)->shipping(auth()->user());
    }
}
