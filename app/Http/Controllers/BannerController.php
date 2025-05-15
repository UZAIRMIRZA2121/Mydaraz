<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;
use App\Services\BannerService;
use App\Services\PayTabsService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;



class BannerController extends Controller
{
    private BannerService $bannerService;
    private PayTabsService $paytabsService;

    public function __construct(BannerService $bannerService, PayTabsService $paytabsService)
    {
        $this->bannerService = $bannerService;
        $this->paytabsService = $paytabsService;
    }

    /**
     * Display a listing of banners.
     */
    public function index($id)
    {
        $banners = Banner::all();
        $bannerTypes = $this->bannerService->getBannerTypes();
        $products = Product::all();
        $categories = Category::all();
        $promotion = Promotion::find($id);

        return view('vendor-views.banners.view', compact('banners', 'bannerTypes', 'products', 'categories', 'promotion'));
    }

    /**
     * Show the form for creating a new banner.
     */
    public function create()
    {
        return view('vendor-views.banners.create');
    }

    /**
     * Store a newly created banner.
     */
    public function store(Request $request)
    {


        // Store banner data in session before redirecting to payment
        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'url' => 'nullable|url',
            'resource_type' => 'nullable|string',
            'product_id' => 'nullable|integer',
            'category_id' => 'nullable|integer',
            'promotion_id' => 'nullable|integer',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = now()->format('Y-m-d') . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('banner', $imageName, 'public'); // Store in a temporary location
        }

     
        // Calculate the number of days
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $daysDifference = $startDate->diffInDays($endDate); // Including the last day

        $promotion = Promotion::find($request->promotion_id);

        $total_price = $promotion->price * $daysDifference;
        $description = "You want to buy '{$promotion->promotion_type}' for {$daysDifference} days from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}, with a total price of {$total_price} PKR.";
        $authenticatedUser = auth('seller')->user(); // Get authenticated seller

        $order = [
            'cart_id' => uniqid(),
            'description' => $description,
            'amount' => $total_price,
            'customer_details' => [
                'name' => $authenticatedUser->f_name . ' ' . $authenticatedUser->l_name ?? 'Unknown',
                'email' => $authenticatedUser->email ?? 'No Email',
                'phone' => $authenticatedUser->phone ?? 'No Phone',
                'street1' => $authenticatedUser->shop_address ?? 'No Address',
                'city' => $authenticatedUser->city ?? 'No City',
                'state' => $authenticatedUser->state ?? 'No State',
                'country' => $authenticatedUser->country ?? 'Saudi Arabia',
                'zip' => $authenticatedUser->zip ?? 'No Zip',
                'ip' => request()->ip(),
            ]
        ];
        $order['return'] = route('vendor.banner.payment');
     

        $paymentResponse = $this->paytabsService->createPayment($order);

     
        session(['tran_ref' => $paymentResponse['tran_ref']]);
        $validatedData['promotion_type'] = $promotion->promotion_type ;
   // Store validated data along with image path in session
   session(['banner_data' => array_merge($validatedData, ['image' => $imageName])]);

        if (isset($paymentResponse['redirect_url'])) {
            return redirect()->away($paymentResponse['redirect_url']);
        }


    }


    /**
     * Display the specified banner.
     */
    public function show(Banner $banner)
    {
        return view('vendor-views.banners.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified banner.
     */
    public function banner_edit(Banner $banner , $banner_id)
    {
        $banner = Banner::find($banner_id);

        return view('vendor-views.banners.edit', compact('banner'));
    }

    /**
     * Update the specified banner.
     */

    public function banner_update(Request $request, $id)
    {
        // Validate only the photo field
        $request->validate([
            // 'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:ratio=4/1',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
        
    
        // Find the existing banner
        $banner = Banner::findOrFail($id);
    
        // Handle image upload
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
    
            // Generate unique filename (YYYY-MM-DD-uniqueid.extension)
            $imageName = now()->format('Y-m-d') . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
    
            // Delete old image if exists
            if ($banner->photo && Storage::disk('public')->exists("banner/{$banner->photo}")) {
                Storage::disk('public')->delete("banner/{$banner->photo}");
            }
    
            // Store the new image in 'banner' folder (inside storage/app/public/)
            $imagePath = $image->storeAs('banner', $imageName, 'public');
    
            // Update only the photo field in the database
            $banner->update([
                'photo' => $imageName, // Store only the filename
                'published' => 0, // Store only the filename
            ]);
        }
        Toastr::success('Banner photo updated successfully!', 'Success');
        return redirect()->route('vendor.banner.my_banner');
    }
    

    /**
     * Remove the specified banner from storage.
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('vendor-views.banners.index')->with('success', 'Banner deleted successfully.');
    }
    public function payment_status(Request $request)
    {
        $tranRef = session('tran_ref');
    
        if (!$tranRef) {
            return redirect()->route('banner.index')->with('error', 'Invalid transaction reference.');
        }
    
        // Fetch payment status using the transaction reference
        $payment = $this->paytabsService->checkTransactionStatus($tranRef);
    
        if (isset($payment['payment_result']['response_status']) && $payment['payment_result']['response_status'] == 'A') {
    
            // Retrieve banner data from the session
            $bannerData = session('banner_data');
            // dd($bannerData['promotion_type']);
         
            if (!$bannerData) {
                return redirect()->route('banner.index')->with('error', 'No banner data found in session.');
            }
    
            // Retrieve stored image path from session and move to permanent folder
            $imagePath = $bannerData['image'] ?? null;
            $imageName = null;
    
            if ($imagePath) {
                $newPath = str_replace('banner', 'banner', $imagePath); // Move to 'banner' folder
                \Storage::disk('public')->move($imagePath, $newPath);
                $imageName = $newPath; // Store new path
            }
    
            // Create the banner
            $banner = Banner::create([
                'photo' => $imageName,
                'start_date' => $bannerData['start_date'],
                'end_date' => $bannerData['end_date'],
                'url' => $bannerData['url'],
                'banner_type' => $bannerData['promotion_type'], // Determine banner type
                'resource_type' => $bannerData['resource_type'],
                'resource_id' => $bannerData['resource_type'] === 'category' ? $bannerData['category_id'] : $bannerData['product_id'],
                'status' => 1, // Default status to active
                'seller_id' => Auth::guard('seller')->id(), // Default status to active
            ]);
          
            // Clear session data after storing banner
            session()->forget(['banner_data', 'tran_ref']);
    
            return redirect()->route('vendor.banner.my_banner')->with('success', 'Payment successful and banner created!');
        } else {
            return redirect()->route('vendor.dashboard.promotion.index')->with('error', 'Payment failed.');
        }
    }
    public function my_banner()
    {
        $sellerId = Auth::guard('seller')->id(); // Get the authenticated seller's ID

        $banners = Banner::where('seller_id'  , $sellerId )->get(); 

        return view('vendor-views.banners.my-banner', compact('banners'));
    }
    
    
}
