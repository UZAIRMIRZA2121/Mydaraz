<?php

use App\Enums\ViewPaths\Web\Pages;
use App\Enums\ViewPaths\Web\Review;
use App\Enums\ViewPaths\Web\Chatting;
use Illuminate\Support\Facades\Route;
use App\Enums\ViewPaths\Web\UserLoyalty;
use App\Enums\ViewPaths\Web\ShopFollower;
use App\Http\Controllers\SocialController;
use App\Enums\ViewPaths\Web\ProductCompare;
use App\Http\Controllers\Web\WebController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\CouponController;
use App\Http\Controllers\Web\ReviewController;
use App\Http\Controllers\Web\ChattingController;
use App\Http\Controllers\Web\CurrencyController;
use App\Http\Controllers\Web\ShopViewController;
use App\Http\Controllers\Web\UserWalletController;
use App\Http\Controllers\Customer\SystemController;
use App\Http\Controllers\Web\ProductListController;
use App\Http\Controllers\Web\UserLoyaltyController;
use App\Http\Controllers\Web\UserProfileController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Vendor\Auth\GoogleController;
use App\Http\Controllers\Vendor\Auth\SocialControllerFacebook;
use App\Http\Controllers\Web\ProductCompareController;
use App\Http\Controllers\Web\ProductDetailsController;
use App\Http\Controllers\Customer\Auth\LoginController;
use App\Http\Controllers\Payment_Methods\PaytmController;
use App\Http\Controllers\Web\Shop\ShopFollowerController;
use App\Http\Controllers\Customer\Auth\RegisterController;
use App\Http\Controllers\Payment_Methods\LiqPayController;
use App\Http\Controllers\Payment_Methods\PaymobController;
use App\Http\Controllers\Payment_Methods\PaytabsController;
use App\Http\Controllers\Customer\Auth\SocialAuthController;
use App\Http\Controllers\Payment_Methods\PaystackController;
use App\Http\Controllers\Payment_Methods\RazorPayController;
use App\Http\Controllers\CustomVendor\CustomVendorController;
use App\Http\Controllers\Payment_Methods\SenangPayController;
use App\Http\Controllers\Customer\Auth\CustomerAuthController;
use App\Http\Controllers\Web\DigitalProductDownloadController;
use App\Http\Controllers\Payment_Methods\MercadoPagoController;
use App\Http\Controllers\Customer\Auth\ForgotPasswordController;
use App\Http\Controllers\Payment_Methods\BkashPaymentController;
use App\Http\Controllers\Payment_Methods\FlutterwaveV3Controller;
use App\Http\Controllers\Payment_Methods\PaypalPaymentController;
use App\Http\Controllers\Payment_Methods\StripePaymentController;
use App\Http\Controllers\Payment_Methods\SslCommerzPaymentController;
use Laravel\Socialite\Facades\Socialite;
// https://albazar.sa/storage/banner/2025-04-30-6811f8d917a70.webp
// https://albazar.sa/storage/banner/2025-03-07-67cb4b908e4c1.webp
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// use Carbon\Carbon;
// use Illuminate\Support\Facades\Config;
// Route::get('/check-time', function () {
//     return [
//         'app_config_timezone' => config('app.timezone'),
//         'php_timezone' => date_default_timezone_get(),
//         'carbon_now' => Carbon::now()->format('Y-m-d H:i:s'),
//         'php_now' => date('Y-m-d H:i:s'),
//     ];
    
// });
// Route::get('/phpinfo', function () {
//     phpinfo();
// });
// use Illuminate\Support\Facades\Artisan;

// Route::get('/create-storage-link', function () {
//     Artisan::call('storage:link');
//     return 'Storage link created successfully!';
// });



// use Illuminate\Http\Request;
// use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
Route::get('/ccc', [HomeController::class, 'optimizeClear']);



Route::get('/select-method', [PaymentController::class, 'method'])->name('payment.select');
Route::post('/payment/iframe', [PaymentController::class, 'showIframe'])->name('payment.showIframe');


use Illuminate\Http\Request;

Route::get('/test-captcha', function () {
    return view('test-captcha');
});

Route::post('/test-captcha', function (Request $request) {
    // Validate the CAPTCHA response
    $request->validate([
        'g-recaptcha-response' => 'required|captcha',  // Assuming you have the captcha validation set up
    ]);

    return 'CAPTCHA verification passed!';
});

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

Route::get('/test-email', function () {
    try {
        $data = ['message' => 'This is a test email sent using Mailtrap in Laravel.'];
        $email = 'uzairmirza2121@gmail.com'; // Fixed email address

        // Send email via Mailtrap
        Mail::raw($data['message'], function ($message) use ($email) {
            $message->to($email)
                ->subject('Test Email')
                ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });

        // If email sent successfully, return success message
        return 'Email has been sent successfully!';
    } catch (\Exception $e) {
        dd('Error sending email: ' . $e->getMessage());
        // Log error and return failure message
        Log::error('Error sending email: ' . $e->getMessage());
        return 'Failed to send email.';
    }
});






Route::controller(WebController::class)->group(function () {
    Route::get('maintenance-mode', 'maintenance_mode')->name('maintenance-mode');
});

Route::group(['namespace' => 'Web', 'middleware' => ['maintenance_mode', 'guestCheck']], function () {
    Route::group(['prefix' => 'product-compare', 'as' => 'product-compare.'], function () {
        Route::controller(ProductCompareController::class)->group(function () {
            Route::get(ProductCompare::INDEX[URI], 'index')->name('index');
            Route::post(ProductCompare::INDEX[URI], 'add');
            Route::get(ProductCompare::DELETE[URI], 'delete')->name('delete');
            Route::get(ProductCompare::DELETE_ALL[URI], 'deleteAllCompareProduct')->name('delete-all');
        });
    });
    Route::post(ShopFollower::SHOP_FOLLOW[URI], [ShopFollowerController::class, 'followOrUnfollowShop'])->name('shop-follow');
});

Route::group(['namespace' => 'Web', 'middleware' => ['maintenance_mode', 'guestCheck']], function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->name('home');
    });
    Route::post('custome/vendors/add', [CustomVendorController::class, 'store'])->name('custome.vendors.store');
    Route::post('/send-otp', [CustomVendorController::class, 'sendOtp']);
    Route::get('login/{service}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('service-callback');
    Route::controller(WebController::class)->group(function () {
        Route::get('quick-view', 'getQuickView')->name('quick-view');
        Route::get('searched-products', 'getSearchedProducts')->name('searched-products');
    });
    Route::group(['middleware' => ['customer']], function () {
        Route::controller(ReviewController::class)->group(function () {
            Route::post(Review::ADD[URI], 'add')->name('review.store');
            Route::post(Review::ADD_DELIVERYMAN_REVIEW[URI], 'addDeliveryManReview')->name('submit-deliveryman-review');
            Route::post(Review::DELETE_REVIEW_IMAGE[URI], 'deleteReviewImage')->name('delete-review-image');
        });
    });

    Route::get('vendor/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('vendor.auth.login.google');
    Route::get('/vendor/google/callback', [GoogleController::class, 'handleGoogleCallback']);
  

  

    Route::get('vendor/auth/facebook', [SocialControllerFacebook::class, 'facebookRedirect'])->name('vendor.auth.login.facebook');
    Route::get('vendor/facebook/callback', [SocialControllerFacebook::class, 'loginWithFacebook']);


    Route::controller(WebController::class)->group(function () {
        Route::get('checkout-details', 'checkout_details')->name('checkout-details');
        Route::get('checkout-shipping', 'checkout_shipping')->name('checkout-shipping');
        Route::get('checkout-payment', 'checkout_payment')->name('checkout-payment');
        Route::get('checkout-review', 'checkout_review')->name('checkout-review');
        Route::get('checkout-complete', 'getCashOnDeliveryCheckoutComplete')->name('checkout-complete');
        Route::post('offline-payment-checkout-complete', 'getOfflinePaymentCheckoutComplete')->name('offline-payment-checkout-complete');
        Route::get('order-placed', 'order_placed')->name('order-placed');
        Route::get('order-placed-success', 'getOrderPlaceView')->name('order-placed-success');
        Route::get('shop-cart', 'shop_cart')->name('shop-cart');
        Route::post('order_note', 'order_note')->name('order_note');
        Route::get('digital-product-download/{id}', 'getDigitalProductDownload')->name('digital-product-download');
        Route::post('digital-product-download-otp-verify', 'getDigitalProductDownloadOtpVerify')->name('digital-product-download-otp-verify');
        Route::post('digital-product-download-otp-reset', 'getDigitalProductDownloadOtpReset')->name('digital-product-download-otp-reset');
        Route::get('pay-offline-method-list', 'pay_offline_method_list')->name('pay-offline-method-list')->middleware('guestCheck');

        //wallet payment
        Route::get('checkout-complete-wallet', 'checkout_complete_wallet')->name('checkout-complete-wallet');

        Route::post('subscription', 'subscription')->name('subscription');
        Route::get('search-shop', 'search_shop')->name('search-shop');

        Route::get('categories', 'getAllCategoriesView')->name('categories');
        Route::get('category-ajax/{id}', 'categories_by_category')->name('category-ajax');

        Route::get('brands', 'getAllBrandsView')->name('brands');
        Route::get('vendors', 'getAllVendorsView')->name('vendors');
        Route::get('seller-profile/{id}', 'seller_profile')->name('seller-profile');

        Route::get('flash-deals/{id}', 'getFlashDealsView')->name('flash-deals');
    });

    Route::controller(PageController::class)->group(function () {
        Route::get(Pages::ABOUT_US[URI], 'getAboutUsView')->name('about-us');
        Route::get(Pages::CONTACTS[URI], 'getContactView')->name('contacts');
        Route::get(Pages::HELP_TOPIC[URI], 'getHelpTopicView')->name('helpTopic');
        Route::get(Pages::REFUND_POLICY[URI], 'getRefundPolicyView')->name('refund-policy');
        Route::get(Pages::RETURN_POLICY[URI], 'getReturnPolicyView')->name('return-policy');
        Route::get(Pages::PRIVACY_POLICY[URI], 'getPrivacyPolicyView')->name('privacy-policy');
        Route::get(Pages::CANCELLATION_POLICY[URI], 'getCancellationPolicyView')->name('cancellation-policy');
        Route::get(Pages::SHIPPING_POLICY[URI], 'getShippingPolicyView')->name('shipping-policy');
        Route::get(Pages::TERMS_AND_CONDITION[URI], 'getTermsAndConditionView')->name('terms');
    });

    Route::controller(ProductDetailsController::class)->group(function () {
        Route::get('/product/{slug}', 'index')->name('product');
    });

    Route::controller(ProductListController::class)->group(function () {
        Route::get('products', 'products')->name('products');
    });

    Route::controller(ShopViewController::class)->group(function () {
        Route::post('ajax-filter-products', 'filterProductsAjaxResponse')->name('ajax-filter-products');
    });

    Route::controller(WebController::class)->group(function () {
        Route::get('orderDetails', 'orderdetails')->name('orderdetails');
        Route::get('discounted-products', 'discounted_products')->name('discounted-products');
        Route::post('/products-view-style', 'product_view_style')->name('product_view_style');

        Route::post('review-list-product', 'review_list_product')->name('review-list-product');
        Route::post('review-list-shop', 'getShopReviewList')->name('review-list-shop'); // theme fashion
        //Chat with seller from product details
        Route::get('chat-for-product', 'chat_for_product')->name('chat-for-product');

        Route::get('wishlists', 'viewWishlist')->name('wishlists')->middleware('customer');
        Route::post('store-wishlist', 'storeWishlist')->name('store-wishlist');
        Route::post('delete-wishlist', 'deleteWishlist')->name('delete-wishlist');
        Route::get('delete-wishlist-all', 'deleteAllWishListItems')->name('delete-wishlist-all')->middleware('customer');

        // end theme_aster compare list
        Route::get('searched-products-for-compare', 'getSearchedProductsForCompareList')->name('searched-products-compare'); // theme fashion compare list
    });

    Route::controller(CurrencyController::class)->group(function () {
        Route::post('/currency', 'changeCurrency')->name('currency.change');
    });

    // Support Ticket
    Route::controller(UserProfileController::class)->group(function () {
        Route::group(['prefix' => 'support-ticket', 'as' => 'support-ticket.'], function () {
            Route::get('{id}', 'single_ticket')->name('index');
            Route::post('{id}', 'comment_submit')->name('comment');
            Route::get('delete/{id}', 'support_ticket_delete')->name('delete');
            Route::get('close/{id}', 'support_ticket_close')->name('close');
        });
    });

    Route::controller(UserProfileController::class)->group(function () {
        Route::group(['prefix' => 'track-order', 'as' => 'track-order.'], function () {
            Route::get('', 'track_order')->name('index');
            Route::get('result-view', 'track_order_result')->name('result-view');
            Route::get('last', 'track_last_order')->name('last');
            Route::any('result', 'track_order_result')->name('result');
            Route::get('order-wise-result-view', 'track_order_wise_result')->name('order-wise-result-view');
        });
    });

    Route::controller(UserProfileController::class)->group(function () {
        Route::get('user-profile', 'user_profile')->name('user-profile')->middleware('customer'); //theme_aster
        Route::get('user-account', 'user_account')->name('user-account')->middleware('customer');
        Route::post('user-account-update', 'getUserProfileUpdate')->name('user-update')->middleware('customer');
        Route::post('user-account-picture', 'user_picture')->name('user-picture');
        Route::get('account-address-add', 'account_address_add')->name('account-address-add');
        Route::get('account-address', 'account_address')->name('account-address');
        Route::post('account-address-store', 'address_store')->name('address-store');
        Route::get('account-address-delete', 'address_delete')->name('address-delete');
        ROute::get('account-address-edit/{id}', 'address_edit')->name('address-edit');
        Route::post('account-address-update', 'address_update')->name('address-update');
        Route::get('account-payment', 'account_payment')->name('account-payment');
        Route::get('account-oder', 'account_order')->name('account-oder')->middleware('customer');
        Route::get('account-order-details', 'account_order_details')->name('account-order-details')->middleware('customer');
        Route::get('account-order-details-vendor-info', 'account_order_details_seller_info')->name('account-order-details-vendor-info')->middleware('customer');
        Route::get('account-order-details-delivery-man-info', 'account_order_details_delivery_man_info')->name('account-order-details-delivery-man-info')->middleware('customer');
        Route::get('account-order-details-reviews', 'getAccountOrderDetailsReviewsView')->name('account-order-details-reviews')->middleware('customer');
        Route::get('generate-invoice/{id}', 'generate_invoice')->name('generate-invoice');
        Route::get('account-wishlist', 'account_wishlist')->name('account-wishlist'); //add to card not work
        Route::get('refund-request/{id}', 'refund_request')->name('refund-request');
        Route::get('refund-details/{id}', 'refund_details')->name('refund-details');
        Route::post('refund-store', 'store_refund')->name('refund-store');
        Route::get('account-tickets', 'account_tickets')->name('account-tickets');
        Route::get('order-cancel/{id}', 'order_cancel')->name('order-cancel');
        Route::post('ticket-submit', 'submitSupportTicket')->name('ticket-submit');
        Route::get('account-delete/{id}', 'account_delete')->name('account-delete');
        Route::get('refer-earn', 'refer_earn')->name('refer-earn')->middleware('customer');
        Route::get('user-coupons', 'user_coupons')->name('user-coupons')->middleware('customer');
        Route::get('user-restock-requests', 'restockRequestsView')->name('user-restock-requests')->middleware('customer');
        Route::get('user-restock-request-delete', 'deleteRestockRequest')->name('user-restock-request-delete')->middleware('customer');
        Route::get('user-all-restock-request-delete/{ids}', 'deleteAllRestockRequest')->name('user-all-restock-request-delete')->middleware('customer');
    });

    Route::controller(ChattingController::class)->group(function () {
        Route::get(Chatting::INDEX[URI] . '/{type}', 'index')->name('chat')->middleware('customer');
        Route::get(Chatting::MESSAGE[URI], 'getMessageByUser')->name('messages');
        Route::post(Chatting::MESSAGE[URI], 'addMessage');
    });

    Route::controller(UserWalletController::class)->group(function () {
        Route::get('wallet-account', 'my_wallet_account')->name('wallet-account'); //theme fashion
        Route::get('wallet', 'index')->name('wallet')->middleware('customer');
    });

    Route::controller(UserLoyaltyController::class)->group(function () {
        Route::get(UserLoyalty::LOYALTY[URI], 'index')->name('loyalty')->middleware('customer');
        Route::post(UserLoyalty::EXCHANGE_CURRENCY[URI], 'getLoyaltyExchangeCurrency')->name('loyalty-exchange-currency');
        Route::get(UserLoyalty::GET_CURRENCY_AMOUNT[URI], 'getLoyaltyCurrencyAmount')->name('ajax-loyalty-currency-amount');
    });

    Route::controller(DigitalProductDownloadController::class)->group(function () {
        Route::group(['prefix' => 'digital-product-download-pos', 'as' => 'digital-product-download-pos.'], function () {
            Route::get('/', 'index')->name('index');
        });
    });

    Route::controller(ShopViewController::class)->group(function () {
        Route::get('shopView/{id}', 'seller_shop')->name('shopView');
        Route::get('ajax-shop-vacation-check', 'ajax_shop_vacation_check')->name('ajax-shop-vacation-check');
    });

    Route::controller(WebController::class)->group(function () {
        Route::post('shopView/{id}', 'seller_shop_product');
        Route::get('top-rated', 'top_rated')->name('topRated');
        Route::get('best-sell', 'best_sell')->name('bestSell');
        Route::get('new-product', 'new_product')->name('newProduct');
    });


    Route::group(['prefix' => 'contact', 'as' => 'contact.'], function () {
        Route::controller(WebController::class)->group(function () {
            Route::post('store', 'contact_store')->name('store');
            Route::get('/code/captcha/{tmp}', 'captcha')->name('default-captcha');
        });
    });
});

// Check done
Route::group(['prefix' => 'cart', 'as' => 'cart.', 'namespace' => 'Web'], function () {
    Route::controller(CartController::class)->group(function () {
        Route::post('variant_price', 'getVariantPrice')->name('variant_price');
        Route::post('add', 'addToCart')->name('add');
        Route::post('update-variation', 'update_variation')->name('update-variation'); //theme fashion
        Route::post('remove', 'removeFromCart')->name('remove');
        Route::get('remove-all', 'remove_all_cart')->name('remove-all'); //theme fashion
        Route::post('nav-cart-items', 'updateNavCart')->name('nav-cart');
        Route::post('floating-nav-cart-items', 'update_floating_nav')->name('floating-nav-cart-items'); // theme fashion floating nav
        Route::post('updateQuantity', 'updateQuantity')->name('updateQuantity');
        Route::post('updateQuantity-guest', 'updateQuantity_guest')->name('updateQuantity.guest');
        Route::post('order-again', 'orderAgain')->name('order-again')->middleware('customer');
        Route::post('select-cart-items', 'updateCheckedCartItems')->name('select-cart-items');
        Route::post('product-restock-request', 'addProductRestockRequest')->name('product-restock-request');
    });
});


Route::group(['prefix' => 'coupon', 'as' => 'coupon.', 'namespace' => 'Web'], function () {
    Route::controller(CouponController::class)->group(function () {
        Route::post('apply', 'apply')->name('apply');
        Route::get('remove', 'removeCoupon')->name('remove');
    });
});



/*Auth::routes();*/
Route::get('authentication-failed', function () {
    $errors = [];
    array_push($errors, ['code' => 'auth-001', 'message' => 'Unauthorized.']);
    return response()->json([
        'errors' => $errors
    ], 401);
})->name('authentication-failed');

Route::group(['namespace' => 'Customer', 'prefix' => 'customer', 'as' => 'customer.'], function () {

    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {

        Route::controller(CustomerAuthController::class)->group(function () {
            Route::get('login', 'loginView')->name('login');
            Route::post('login', 'loginSubmit');
            Route::get('login/verify-account', 'loginVerifyPhone')->name('login.verify-account');
            Route::post('login/verify-account/submit', 'verifyAccount')->name('login.verify-account.submit');
            Route::get('login/update-info', 'updateInfo')->name('login.update-info');
            Route::post('login/update-info', 'updateInfoSubmit');
            Route::post('login/resend-otp-code', 'resendOTPCode')->name('resend-otp-code');
        });

        Route::controller(LoginController::class)->group(function () {
            Route::get('/code/captcha/{tmp}', 'captcha')->name('default-captcha');
            Route::get('logout', 'logout')->name('logout');
            Route::get('get-login-modal-data', 'getLoginModalView')->name('get-login-modal-data');
        });

        Route::controller(RegisterController::class)->group(function () {
            Route::get('sign-up', 'getRegisterView')->name('sign-up');
            Route::post('sign-up', 'submitRegisterData');
            Route::get('check-verification', 'verificationCheckView')->name('check-verification');
            Route::post('verify', 'verifyRegistration')->name('verify');
            Route::post('ajax-verify', 'ajax_verify')->name('ajax_verify');
            Route::post('resend-otp', 'resendOTPToCustomer')->name('resend_otp');
        });

        Route::controller(SocialAuthController::class)->group(function () {
            Route::get('login/{service}', 'redirectToProvider')->name('service-login');
            Route::get('login/{service}/callback', 'handleProviderCallback')->name('service-callback');
            Route::get('login/social/confirmation', 'socialLoginConfirmation')->name('social-login-confirmation');
            Route::post('login/social/confirmation/update', 'updateSocialLoginConfirmation')->name('social-login-confirmation.update');
            Route::post('login/social/verify-account', 'verifyAccount')->name('login.social.verify-account');
        });

        Route::controller(ForgotPasswordController::class)->group(function () {
            Route::get('recover-password', 'reset_password')->name('recover-password');
            Route::post('forgot-password', 'resetPasswordRequest')->name('forgot-password');
            Route::post('verify-recover-password', 'verifyRecoverPassword')->name('verify-recover-password');
            Route::get('otp-verification', 'otp_verification')->name('otp-verification');
            Route::post('otp-verification', 'otp_verification_submit');
            Route::get('reset-password', 'resetPasswordView')->name('reset-password');
            Route::post('reset-password', 'resetPasswordSubmit');
            Route::post('resend-otp-reset-password', 'resendPhoneOTPRequest')->name('resend-otp-reset-password');
        });
    });

    Route::group([], function () {

        Route::controller(SystemController::class)->group(function () {
            Route::get('set-payment-method/{name}', 'setPaymentMethod')->name('set-payment-method');
            Route::get('set-shipping-method', 'setShippingMethod')->name('set-shipping-method');
            Route::post('choose-shipping-address', 'getChooseShippingAddress')->name('choose-shipping-address');
            Route::post('choose-shipping-address-other', 'getChooseShippingAddressOther')->name('choose-shipping-address-other');
            Route::post('choose-billing-address', 'choose_billing_address')->name('choose-billing-address');
        });

        Route::group(['prefix' => 'reward-points', 'as' => 'reward-points.', 'middleware' => ['auth:customer']], function () {
            Route::get('convert', 'RewardPointController@convert')->name('convert');
        });
    });
});

Route::group(['namespace' => 'Customer', 'prefix' => 'customer', 'as' => 'customer.'], function () {
    Route::controller(PaymentController::class)->group(function () {
        Route::post('/web-payment-request', 'payment')->name('web-payment-request');
        Route::post('/customer-add-fund-request', 'customer_add_to_fund_request')->name('add-fund-request');
    });
});

Route::controller(PaymentController::class)->group(function () {
    Route::get('web-payment', 'web_payment_success')->name('web-payment-success');
    Route::get('payment-success', 'success')->name('payment-success');
    Route::get('payment-fail', 'fail')->name('payment-fail');
});

$isGatewayPublished = 0;
try {
    $full_data = include('Modules/Gateways/Addon/info.php');
    $isGatewayPublished = $full_data['is_published'] == 1 ? 1 : 0;
} catch (\Exception $exception) {
}

if (!$isGatewayPublished) {
    Route::group(['prefix' => 'payment'], function () {

        //SSLCOMMERZ
        Route::group(['prefix' => 'sslcommerz', 'as' => 'sslcommerz.'], function () {
            Route::get('pay', [SslCommerzPaymentController::class, 'index'])->name('pay');
            Route::post('success', [SslCommerzPaymentController::class, 'success'])
                ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
            Route::post('failed', [SslCommerzPaymentController::class, 'failed'])
                ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
            Route::post('canceled', [SslCommerzPaymentController::class, 'canceled'])
                ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        });

        //STRIPE
        Route::group(['prefix' => 'stripe', 'as' => 'stripe.'], function () {
            Route::get('pay', [StripePaymentController::class, 'index'])->name('pay');
            Route::get('token', [StripePaymentController::class, 'payment_process_3d'])->name('token');
            Route::get('success', [StripePaymentController::class, 'success'])->name('success');
        });

        //RAZOR-PAY
        Route::group(['prefix' => 'razor-pay', 'as' => 'razor-pay.'], function () {
            Route::get('pay', [RazorPayController::class, 'index']);
            Route::post('payment', [RazorPayController::class, 'payment'])->name('payment')
                ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        });

        //PAYPAL
        Route::group(['prefix' => 'paypal', 'as' => 'paypal.'], function () {
            Route::get('pay', [PaypalPaymentController::class, 'payment']);
            Route::any('success', [PaypalPaymentController::class, 'success'])->name('success')
                ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
            Route::any('cancel', [PaypalPaymentController::class, 'cancel'])->name('cancel')
                ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        });

        //SENANG-PAY
        Route::group(['prefix' => 'senang-pay', 'as' => 'senang-pay.'], function () {
            Route::get('pay', [SenangPayController::class, 'index']);
            Route::any('callback', [SenangPayController::class, 'return_senang_pay']);
        });

        //PAYTM
        Route::group(['prefix' => 'paytm', 'as' => 'paytm.'], function () {
            Route::get('pay', [PaytmController::class, 'payment']);
            Route::any('response', [PaytmController::class, 'callback'])->name('response')
                ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        });

        //FLUTTERWAVE
        Route::group(['prefix' => 'flutterwave-v3', 'as' => 'flutterwave-v3.'], function () {
            Route::get('pay', [FlutterwaveV3Controller::class, 'initialize'])->name('pay');
            Route::get('callback', [FlutterwaveV3Controller::class, 'callback'])->name('callback');
        });

        //PAYSTACK
        Route::group(['prefix' => 'paystack', 'as' => 'paystack.'], function () {
            Route::get('pay', [PaystackController::class, 'index'])->name('pay');
            Route::post('payment', [PaystackController::class, 'redirectToGateway'])->name('payment');
            Route::get('callback', [PaystackController::class, 'handleGatewayCallback'])->name('callback');
            Route::get('cancel', [PaystackController::class, 'cancel'])->name('cancel');
        });

        //BKASH
        Route::group(['prefix' => 'bkash', 'as' => 'bkash.'], function () {
            // Payment Routes for bKash
            Route::get('make-payment', [BkashPaymentController::class, 'make_tokenize_payment'])->name('make-payment');
            Route::any('callback', [BkashPaymentController::class, 'callback'])->name('callback');
        });

        //Liqpay
        Route::group(['prefix' => 'liqpay', 'as' => 'liqpay.'], function () {
            Route::get('payment', [LiqPayController::class, 'payment'])->name('payment');
            Route::any('callback', [LiqPayController::class, 'callback'])->name('callback');
        });

        //MERCADOPAGO
        Route::group(['prefix' => 'mercadopago', 'as' => 'mercadopago.'], function () {
            Route::get('pay', [MercadoPagoController::class, 'index'])->name('index');
            Route::post('make-payment', [MercadoPagoController::class, 'make_payment'])->name('make_payment');
        });

        //PAYMOB
        Route::group(['prefix' => 'paymob', 'as' => 'paymob.'], function () {
            Route::any('pay', [PaymobController::class, 'credit'])->name('pay');
            Route::any('callback', [PaymobController::class, 'callback'])->name('callback');
        });

        //PAYTABS
        Route::group(['prefix' => 'paytabs', 'as' => 'paytabs.'], function () {
            Route::any('pay', [PaytabsController::class, 'payment'])->name('pay');
            Route::any('callback', [PaytabsController::class, 'callback'])->name('callback');
            Route::any('response', [PaytabsController::class, 'response'])->name('response');



        });
    });

Route::get('/payment/select-method', function () {
    return view('payment.select-method');
})->name('payment.select');

Route::post('/payment/iframe', [PaymentController::class, 'showIframe'])->name('payment.showIframe');


}

Route::controller(CustomVendorController::class)->group(function () {
    Route::POST('/VendorAdd-Custom', 'vendor_add')->name('vendor_addCustom');
    // Verify OTP Route
    Route::post('/verify-otp', 'verifyOtp')->name('verify.otp');
    // Resend OTP Route
    Route::post('/custom-resend-otp', 'resendOtp')->name('resend.otp');
});
Route::get('/send-otp', [CustomVendorController::class, 'sendOtp']);

Route::get('/check-shop-name', [CustomVendorController::class, 'checkShopName'])->name('check.shop.name');

Route::get('login/google', [App\Http\Controllers\Auth\SocialLoginController::class, 'redirectToGoogle']);
Route::get('login/google/callback', [App\Http\Controllers\Auth\SocialLoginController::class, 'handleGoogleCallback']);

Route::get('login/facebook', [App\Http\Controllers\Auth\SocialLoginController::class, 'redirectToFacebook']);
Route::get('login/facebook/callback', [App\Http\Controllers\Auth\SocialLoginController::class, 'handleFacebookCallback']);



Route::get('/paytabs/iframe/{payment_id}', [PaytabsController::class, 'showIframe'])->name('paytabs.iframe');



use App\Http\Controllers\TryotoController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\Shop\OrderController;

Route::post('/update-shipping-option', [TryotoController::class, 'updateShippingOption'])->name('shipping.update');
Route::post('/shipping-options', [TryotoController::class, 'getShippingOptions']);
Route::post('/create-order', [TryotoController::class, 'createOrderWithShipping']);
Route::post('/tryoto-webhook', [WebhookController::class, 'handleTryotoWebhook']);
Route::get('/order-tracking/{orderId}', [TryotoController::class, 'getOrderTracking']);
Route::post('/test-order-creation', [TryotoController::class, 'testOrderCreation']);
Route::get('/order/awb/{orderId}', [OrderController::class, 'getAWB'])->name('order.awb');

