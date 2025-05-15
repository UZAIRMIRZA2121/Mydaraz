<?php

namespace App\Http\Controllers\Vendor;

use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Contracts\Repositories\ShopRepositoryInterface;
use App\Enums\ViewPaths\Vendor\Shop;
use App\Http\Requests\Vendor\ShopRequest;
use App\Http\Requests\Vendor\ShopVacationRequest;
use App\Http\Controllers\BaseController;
use App\Models\Seller;
use App\Models\SellerAvailability;
use App\Services\ShopService;
use App\Services\VendorService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ShopController extends BaseController
{
    public function __construct(
        private readonly VendorRepositoryInterface $vendorRepo,
        private readonly ShopRepositoryInterface $shopRepo,
        private readonly ShopService $shopService,
        private readonly VendorService $vendorService,
    ) {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View|Collection|LengthAwarePaginator|callable|RedirectResponse|null
     */
    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->getView(request: $request, type: $type);
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View|Collection|LengthAwarePaginator|callable|null
     */
    public function getView(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable
    {
        $shop = $this->shopRepo->getFirstWhere(['seller_id' => auth('seller')->id()]);
        $vendor = $this->vendorRepo->getFirstWhere(params: ['id' => auth('seller')->id()]);
        if (!isset($shop)) {
            $this->shopRepo->add($this->shopService->getShopDataForAdd(vendor: $vendor));
            $shop = $this->shopRepo->getFirstWhere(['seller_id' => auth('seller')->id()]);
        }

        $minimumOrderAmountStatus = getWebConfig(name: 'minimum_order_amount_status');
        $minimumOrderAmountByVendor = getWebConfig(name: 'minimum_order_amount_by_seller');
        $freeDeliveryStatus = getWebConfig(name: 'free_delivery_status');
        $freeDeliveryResponsibility = getWebConfig(name: 'free_delivery_responsibility');
        if ($request['pagetype'] == 'order_settings' && (($minimumOrderAmountStatus && $minimumOrderAmountByVendor) || ($freeDeliveryStatus && $freeDeliveryResponsibility == 'seller'))) {
            return view(Shop::ORDER_SETTINGS[VIEW], compact('vendor', 'minimumOrderAmountStatus', 'minimumOrderAmountByVendor', 'freeDeliveryStatus', 'freeDeliveryResponsibility'));
        }
        return view(Shop::INDEX[VIEW], compact('shop', 'minimumOrderAmountStatus', 'minimumOrderAmountByVendor', 'freeDeliveryStatus', 'freeDeliveryResponsibility'));
    }

    /**
     * @param string|int $id
     * @return View
     */
    public function getUpdateView(string|int $id): View
    {
        $shop = $this->shopRepo->getFirstWhere(['id' => $id]);
        return view(Shop::UPDATE[VIEW], compact('shop'));
    }

    /**
     * @param ShopRequest $request
     * @param string|int $id
     * @return RedirectResponse
     */
    public function update(ShopRequest $request, string|int $id): RedirectResponse
    {

        $shop = $this->shopRepo->getFirstWhere(['id' => $id]);
        $this->shopRepo->update(id: $id, data: $this->shopService->getShopDataForUpdate(request: $request, shop: $shop));

        $seller = $shop->seller;
        // Update the seller details if available in request
        if ($shop->seller) {
            $seller->update([
                'shop_name'=>$request['name'],
                'shop_address'=>$request['address'],
                'brief_here'=>$request['brief_here'],
                'longitude' => $request['longitude'],
                'latitude' => $request['latitude'],
                'city' => $request['city'],
                'brief_here' => $request['brief_here'],
            ]);
        }

        Toastr::info(translate('Shop_updated_successfully'));
        return redirect()->route(Shop::INDEX[ROUTE]);
    }

    /**
     * @param ShopVacationRequest $request
     * @param string|int $id
     * @return RedirectResponse
     */
    public function updateVacation(ShopVacationRequest $request, string|int $id): RedirectResponse
    {
        $this->shopRepo->update(id: $id, data: $this->shopService->getVacationData(request: $request));
        Toastr::success(translate('Vacation_mode_updated_successfully'));
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param string|int $id
     * @return JsonResponse
     */
    public function closeShopTemporary(Request $request, string|int $id): JsonResponse
    {
        $this->shopRepo->update(id: $id, data: ['temporary_close' => $request->get(key: 'status', default: 0)]);

        Cache::clear();
        return response()->json([
            'status' => true,
            'message' => $request['status'] ? translate("temporary_close_active_successfully") : translate("temporary_close_inactive_successfully"),
        ], status: 200);
    }

    /**
     * @param Request $request
     * @param string|int $id
     * @return RedirectResponse
     */
    public function updateOrderSettings(Request $request, string|int $id): RedirectResponse
    {
        if ($request->has('minimum_order_amount')) {
            $this->vendorRepo->update(id: $id, data: $this->vendorService->getMinimumOrderAmount(request: $request));
        }
        if ($request->has('free_delivery_over_amount')) {
            $this->vendorRepo->update(
                id: $id,
                data: $this->vendorService->getFreeDeliveryOverAmountData(
                    request: $request
                )
            );
        }
        return redirect()->back();
    }

    public function availability()
    {
        $minimumOrderAmountStatus = getWebConfig(name: 'minimum_order_amount_status');
        $freeDeliveryStatus = getWebConfig(name: 'free_delivery_status');
        $shop = $this->shopRepo->getFirstWhere(['seller_id' => auth('seller')->id()]);
        $availabilities = SellerAvailability::where('seller_id', auth('seller')->id())->get();
        return view('vendor-views.shop.availability', compact('minimumOrderAmountStatus', 'freeDeliveryStatus', 'shop', 'availabilities'));
    }
    public function updateAvailability(Request $request, $id)
    {
        // Fetch the shop details
        $shop = \App\Models\Shop::find($id);

        // Ensure the shop exists
        if (!$shop) {
            return redirect()->back()->with('error', 'Shop not found!');
        }

        // Get the associated seller
        $seller = Seller::find($shop->seller_id);

        // Ensure the seller exists
        if (!$seller) {
            return redirect()->back()->with('error', 'Seller not found!');
        }

        foreach ($request->availability as $day => $data) {
            SellerAvailability::updateOrCreate(
                ['seller_id' => $seller->id, 'day_of_week' => $day], // Use seller_id instead of shop_id
                [
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                ]
            );
        }

        return redirect()->back()->with('success', 'Availability updated successfully!');
    }

}
