<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Models\Commission;
use App\Models\Product;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\BaseController;
use App\Enums\ViewPaths\Admin\BusinessSettings;
use App\Http\Requests\Admin\VendorSettingsRequest;
use App\Contracts\Repositories\BusinessSettingRepositoryInterface;

class VendorSettingsController extends BaseController
{

    public function __construct(
        private readonly BusinessSettingRepositoryInterface $businessSettingRepo,
    ) {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View Index function is the starting point of a controller
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, string $type = null): View
    {
        return $this->getView();
    }

    public function getView(): View
    {

        $Commission = Commission::where('admin_id', Auth::guard('admin')->id())->first();
        $sales_commission = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'sales_commission']);
        if (!isset($sales_commission)) {
            $this->businessSettingRepo->add(data: ['type' => 'sales_commission', 'value' => 0]);
        }

        $seller_registration = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'seller_registration']);
        if (!isset($seller_registration)) {
            $this->businessSettingRepo->add(data: ['type' => 'seller_registration', 'value' => 1]);
        }
        return view(BusinessSettings::VENDOR_VIEW[VIEW], compact('Commission'));
    }

    public function update(VendorSettingsRequest $request): RedirectResponse
    {

        $validatedData = $request->validate([
            'commission' => 'required|numeric',
            'commission_first_percentage' => 'required|numeric',
            'commission_second_price' => 'required|numeric',
            'commission_second_percentage' => 'required|numeric',
            'tax_percentage' => 'required|numeric',
        ]);
        $record = Commission::where('admin_id', Auth::guard('admin')->id())->first();

        if ($record) {
            // Update the record
            $record->update([
                'commission' => $validatedData['commission'],
                'commission_first_percentage' => $validatedData['commission_first_percentage'],
                'commission_second_price' => $validatedData['commission_second_price'],
                'commission_second_percentage' => $validatedData['commission_second_percentage'],
                'tax_percentage' => $validatedData['tax_percentage'],
            ]);

            $message = 'Record updated successfully!';
        } else {
            // Create a new record
            Commission::create([
                'commission' => $validatedData['commission'],
                'commission_first_percentage' => $validatedData['commission_first_percentage'],
                'commission_second_price' => $validatedData['commission_second_price'],
                'commission_second_percentage' => $validatedData['commission_second_percentage'],
                'tax_percentage' => $validatedData['tax_percentage'],
            ]);

            $message = 'Record created successfully!';
        }
        Product::query()->update([
        'tax' => $validatedData['tax_percentage'], 
        'tax_type' => 'percent', 
        'tax_model' => 'include']);


        $this->businessSettingRepo->updateOrInsert(type: 'sales_commission', value: $request->get('commission', 10));
        $this->businessSettingRepo->updateOrInsert(type: 'sales_commission_first_percentage', value: $request->get('commission_first_percentage', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'sales_commission_second_price', value: $request->get('commission_second_price', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'sales_commission_second_percentage', value: $request->get('commission_second_percentage', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'seller_pos', value: $request->get('seller_pos', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'seller_registration', value: $request->get('seller_registration', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'minimum_order_amount_by_seller', value: $request->get('minimum_order_amount_by_seller', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'new_product_approval', value: $request->get('new_product_approval', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'product_wise_shipping_cost_approval', value: $request->get('product_wise_shipping_cost_approval', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'vendor_review_reply_status', value: $request->get('vendor_review_reply_status', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'vendor_forgot_password_method', value: $request->get('vendor_forgot_password_method', 'phone'));
        Toastr::success(translate('Updated_successfully'));
        return redirect()->back();
    }

}
