<?php

namespace App\Console\Commands;

use App\Models\Banner;
use App\Models\Product;
use Illuminate\Console\Command;
use App\Models\OrderTransaction;
use App\Models\SellerWallet;
use App\Models\WithdrawRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;


class TransferOrderTransactions extends Command
{
    protected $signature = 'transfer:transactions';
    protected $description = 'Transfer order transactions to seller wallets every 10 seconds';


    public function handle()
    {
        // Get expired products
        $expiredProducts = Product::where('end_date', '<', Carbon::now())->get();

        foreach ($expiredProducts as $product) {
            // Update the product status
            $product->update([
                'featured' => 0,
                'promotion_id' => null,
                'payment_status' => null,
                'featured_till' => null,
                'start_date' => null,
                'end_date' => null,
            
            ]); // 0 = Inactive
        
            // Log the update
            Log::info('Expired Product Updated: ' . $product->name);
        }

        $expiredBanner = Banner::where('end_date', '<', Carbon::now())->get();
        foreach ($expiredBanner as $banner) {
            $banner->update([
                'published' => 0,
            ]);
        
            // Log the update
            Log::info('Expired banner Updated:--------------> ' . $banner->name);
        }
      
        Log::info('Cron Job Started: Transferring Transactions');









        // Get transactions that are 10 days old and not yet transferred
        $tenDaysAgo = Carbon::now()->subDays(10);
        $orders = OrderTransaction::whereDate('created_at', '<=', $tenDaysAgo)
            ->where('sub_status', 'pending')
            ->get();

        foreach ($orders as $order) {
            // Inline the summary logic
            $orderSummary = [
                'total_tax' => $order->tax,  // Example, replace with actual logic
            ];
            Log::info('order: ' . json_encode($order));
            Log::info('orderSummary: ' . json_encode($orderSummary));

            // Find or create SellerWallet
            $wallet = SellerWallet::firstOrCreate(
                ['seller_id' => $order->seller_id],
                [
                    'total_earning' => 0,
                    'withdrawn' => 0,
                    'commission_given' => 0,
                    'pending_withdraw' => 0,
                    'delivery_charge_earned' => 0,
                    'collected_cash' => 0,
                    'total_tax_collected' => 0,
                ]
            );

            // Extract order details
            $commission = $order->admin_commission;
            $wallet->commission_given += $commission;
            $wallet->total_tax_collected += $orderSummary['total_tax'];

            // Shipping model logic
            $shippingModel = config('settings.shipping_model'); // Assuming shipping model is set in config

            if ($shippingModel == 'sellerwise_shipping') {
                // Update wallet with shipping cost if not free
                if (!$order->is_shipping_free) {
                    $wallet->delivery_charge_earned += $order->shipping_cost;
                }
                // Add to collected cash (total order amount)
                $wallet->collected_cash += $order->order_amount;
            } else {
                // Handle other shipping models here
            }

            // Update wallet with the earned values
            $wallet->update([
                'total_earning' => $wallet->total_earning + $order->seller_amount,
                'pending_withdraw' => $wallet->pending_withdraw + $order->seller_amount,
                'commission_given' => $wallet->commission_given,
                'delivery_charge_earned' => $wallet->delivery_charge_earned,
                'total_tax_collected' => $wallet->total_tax_collected,
                'collected_cash' => $wallet->collected_cash + ($order->payment_method === 'cash' ? $order->order_amount : 0),
            ]);

            // Create WithdrawRequest for the order
            WithdrawRequest::create([
                'seller_id' => $order->seller_id,
                'delivery_man_id' => $order->delivered_by == 0 ? null : $order->delivered_by, // Assuming you have this field in the OrderTransaction table
                'admin_id' => auth()->id(), // Assuming you want to use the currently authenticated admin ID
                'amount' => $order->seller_amount,
                'withdrawal_method_id' => $order->payment_method === 'cash' ? 1 : 2, // Example logic for withdrawal method (1 for cash, 2 for bank)
                'withdrawal_method_fields' => json_encode(['payment_method' => $order->payment_method]), // Example logic for withdrawal method fields
                'transaction_note' => 'Payment for order ID ' . $order->id,
                'approved' => false, // Set to false, assuming approval will happen later
            ]);

            // Mark the transaction as processed and update its status to 'completed'
            $order->update([
                'status' => 'transferred',
                'sub_status' => 'completed',  // Update sub_status to 'completed'
            ]);

            Log::info("Transaction ID {$order->id} transferred to SellerWallet, status updated to 'completed', and WithdrawRequest created");
        }

        Log::info('Cron Job Completed');
    }





}
