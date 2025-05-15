<?php

namespace App\Http\Controllers\Admin\Notification;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Contracts\Repositories\NotificationMessageRepositoryInterface;
use App\Contracts\Repositories\TranslationRepositoryInterface;
use App\Enums\ViewPaths\Admin\PushNotification;
use App\Http\Controllers\BaseController;
use App\Models\Notification;
use App\Models\Promotion;
use App\Services\PushNotificationService;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushNotificationSettingsController extends BaseController
{

    /**
     * @param BusinessSettingRepositoryInterface $businessSettingRepo
     * @param NotificationMessageRepositoryInterface $notificationMessageRepo
     * @param PushNotificationService $pushNotificationService
     * @param TranslationRepositoryInterface $translationRepo
     */
    public function __construct(
        private readonly BusinessSettingRepositoryInterface $businessSettingRepo,
        private readonly NotificationMessageRepositoryInterface $notificationMessageRepo,
        private readonly PushNotificationService $pushNotificationService,
        private readonly TranslationRepositoryInterface $translationRepo,
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

    /**
     * @return View
     */
    public function getView(): View
    {
        $customerMessages = $this->getPushNotificationMessageData(userType: 'customer');
        $vendorMessages = $this->getPushNotificationMessageData(userType: 'seller');
        $deliveryManMessages = $this->getPushNotificationMessageData(userType: 'delivery_man');
        $language = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'pnc_language']);
        return view(PushNotification::INDEX[VIEW], compact('customerMessages', 'vendorMessages', 'deliveryManMessages', 'language'));
    }

    /**
     * @param $userType
     * @return Collection
     */
    protected function getPushNotificationMessageData($userType): Collection
    {
        $pushNotificationMessages = $this->notificationMessageRepo->getListWhere(filters: ['user_type' => $userType]);
        $pushNotificationMessagesKeyArray = $this->pushNotificationService->getMessageKeyData(userType: $userType);
        foreach ($pushNotificationMessagesKeyArray as $value) {
            $checkKey = $pushNotificationMessages->where('key', $value)->first();
            if ($checkKey === null) {
                $this->notificationMessageRepo->add(
                    data: $this->pushNotificationService->getAddData(userType: $userType, value: $value)
                );
            }
        }
        foreach ($pushNotificationMessages as $value) {
            if (!in_array($value['key'], $pushNotificationMessagesKeyArray)) {
                $this->notificationMessageRepo->delete(params: ['id' => $value['id']]);
            }
        }
        return $this->notificationMessageRepo->getListWhere(filters: ['user_type' => $userType]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updatePushNotificationMessage(Request $request): RedirectResponse
    {
        $pushNotificationMessages = $this->notificationMessageRepo->getListWhere(filters: ['user_type' => $request['type']]);
        foreach ($pushNotificationMessages as $pushNotificationMessage) {
            $message = 'message' . $pushNotificationMessage['id'];
            $status = 'status' . $pushNotificationMessage['id'];
            $lang = 'lang' . $pushNotificationMessage['id'];
            $this->notificationMessageRepo->update(
                id: $pushNotificationMessage['id'],
                data: $this->pushNotificationService->getUpdateData(
                    request: $request,
                    message: $message,
                    status: $status,
                    lang: $lang
                )
            );
            foreach ($request->$lang as $index => $value) {
                if ($request->$message[$index] && $value != 'en') {
                    $this->translationRepo->updateData(
                        model: 'App\Models\NotificationMessage',
                        id: $pushNotificationMessage['id'],
                        lang: $value,
                        key: $pushNotificationMessage['key'],
                        value: $request->$message[$index]
                    );
                }
            }
        }
        Toastr::success(translate('update_successfully'));
        return redirect()->back();
    }

    /**
     * @return View
     */
    public function getFirebaseConfigurationView(): View
    {
        $pushNotificationKey = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'push_notification_key'])->value ?? '';
        $configData = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'fcm_credentials'])->value ?? '';
        $projectId = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'fcm_project_id'])->value ?? '';
        return view(PushNotification::FIREBASE_CONFIGURATION[VIEW], [
            'pushNotificationKey' => $pushNotificationKey,
            'projectId' => $projectId,
            'configData' => json_decode($configData),
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function getFirebaseConfigurationUpdate(Request $request): RedirectResponse
    {
        $this->businessSettingRepo->updateOrInsert(type: 'fcm_project_id', value: $request['fcm_project_id']);
        $this->businessSettingRepo->updateOrInsert(type: 'push_notification_key', value: $request['push_notification_key']);

        // fcm_credentials
        $configData = $this->pushNotificationService->getFCMCredentialsArray(request: $request);
        $this->pushNotificationService->firebaseConfigFileGenerate(config: $configData);
        $this->businessSettingRepo->updateOrInsert(type: 'fcm_credentials', value: json_encode($configData));
        clearWebConfigCacheKeys();

        Toastr::success(translate('settings_updated'));
        return back();
    }




    /**
     * Display a listing of notifications.
     */
   
    public function vendor_index()
    {
     
        $notifications = Notification::where('seller_id',Auth::guard('seller')->user()->id)->get();
        return view('vendor-views.push_notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new notification.
     */
    public function vendor_create($id)
    {
    
        return view('vendor-views.push_notifications.create');
    }

    /**
     * Store a newly created notification.
     */
    public function vendor_store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
   
        $data = $request->all();

        // Get seller's ID using the 'seller' guard
        $data['seller_id'] = Auth::guard('seller')->user()->id;
        $data['sent_by'] = 'vendor';

        if ($request->hasFile('image')) {
            // Generate a unique filename: YYYY-MM-DD-uniqueID.webp
            $extension = $request->file('image')->getClientOriginalExtension(); // Get file extension
            $filename = now()->format('Y-m-d') . '-' . uniqid() . '.' . $extension; // Custom filename

            // Store the file in 'public/notification' directory
            $request->file('image')->storeAs('notification', $filename, 'public');

            // Store only the filename in the database
            $data['image'] = $filename;
        }

        Notification::create($data);

        return redirect()->route('vendor.push-notification.index')->with('success', 'Notification created successfully.');
    }



    /**
     * Display the specified notification.
     */
    public function vendor_show($id)
    {
        $notification = Notification::findOrFail($id);
        return view('vendor-views.push_notifications.show', compact('notification'));
    }

    /**
     * Show the form for editing the specified notification.
     */
    public function vendor_edit($id)
    {
        $notification = Notification::findOrFail($id);
        return view('vendor-views.push_notifications.edit', compact('notification'));
    }

    /**
     * Update the specified notification in storage.
     */
    public function vendor_update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|integer|in:0,1',
        ]);

        $notification = Notification::findOrFail($id);
        $data = $request->all();

      
        if ($request->hasFile('image')) {
            // Generate a unique filename: YYYY-MM-DD-uniqueID.webp
            $extension = $request->file('image')->getClientOriginalExtension(); // Get file extension
            $filename = now()->format('Y-m-d') . '-' . uniqid() . '.' . $extension; // Custom filename

            // Store the file in 'public/notification' directory
            $request->file('image')->storeAs('notification', $filename, 'public');

            // Store only the filename in the database
            $data['image'] = $filename;
        }

        $notification->update($data);

        return redirect()->route('vendor.push-notification.index')->with('success', 'Notification updated successfully.');
    }

    /**
     * Remove the specified notification from storage.
     */
    public function vendor_destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()->route('vendor.push-notification.index')->with('success', 'Notification deleted successfully.');
    }

}
