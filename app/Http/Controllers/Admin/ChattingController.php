<?php

namespace App\Http\Controllers\Admin;


use App\Contracts\Repositories\ChattingRepositoryInterface;
use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\DeliveryManRepositoryInterface;
use App\Contracts\Repositories\ShopRepositoryInterface;
use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Enums\ViewPaths\Admin\Chatting;
use App\Events\ChattingEvent;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\ChattingRequest;
use App\Models\Seller;
use App\Services\ChattingService;
use App\Traits\PushNotificationTrait;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Log;

class ChattingController extends BaseController
{
    use PushNotificationTrait;

    /**
     * @param ChattingRepositoryInterface $chattingRepo
     * @param ShopRepositoryInterface $shopRepo
     * @param ChattingService $chattingService
     * @param DeliveryManRepositoryInterface $deliveryManRepo
     * @param CustomerRepositoryInterface $customerRepo
     *  @param VendorRepositoryInterface $vendorRepo
     */
    public function __construct(
        private readonly ChattingRepositoryInterface $chattingRepo,
        private readonly ShopRepositoryInterface $shopRepo,
        private readonly ChattingService $chattingService,
        private readonly DeliveryManRepositoryInterface $deliveryManRepo,
        private readonly CustomerRepositoryInterface $customerRepo,
        private readonly VendorRepositoryInterface $vendorRepo,
    ) {
    }


    /**
     * @param Request|null $request
     * @param string|array|null $type
     * @return View|Collection|LengthAwarePaginator|callable|RedirectResponse|null
     */
    public function index(?Request $request, string|array $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
       
        return $this->getListView(type: $type);
    }

    /**
     * @param string|array $type
     * @return View
     */
    public function getListView(string|array $type): View
    {
    
        $shop = $this->shopRepo->getFirstWhere(params: ['seller_id' => auth('seller')->id()]);
        $adminId = 1;
        if ($type == 'delivery-man') {
            $allChattingUsers = $this->chattingRepo->getListWhereNotNull(
                orderBy: ['created_at' => 'DESC'],
                filters: ['admin_id' => $adminId],
                whereNotNull: ['delivery_man_id', 'admin_id'],
                relations: ['deliveryMan'],
                dataLimit: 'all'
            )->unique('delivery_man_id');

            if (count($allChattingUsers) > 0) {
                $lastChatUser = $allChattingUsers[0]->deliveryMan;
                $this->chattingRepo->updateAllWhere(
                    params: ['admin_id' => $adminId, 'delivery_man_id' => $lastChatUser['id']],
                    data: ['seen_by_admin' => 1]
                );

                $chattingMessages = $this->chattingRepo->getListWhereNotNull(
                    orderBy: ['created_at' => 'DESC'],
                    filters: ['admin_id' => $adminId, 'delivery_man_id' => $lastChatUser->id],
                    whereNotNull: ['delivery_man_id', 'admin_id'],
                    relations: ['deliveryMan'],
                    dataLimit: 'all'
                );

                return view(Chatting::INDEX[VIEW], [
                    'userType' => $type,
                    'allChattingUsers' => $allChattingUsers,
                    'lastChatUser' => $lastChatUser,
                    'chattingMessages' => $chattingMessages,
                ]);
            }
        } elseif ($type == 'customer') {
           

            $allChattingUsers = $this->chattingRepo->getListWhereNotNull(
                orderBy: ['created_at' => 'DESC'],
                filters: ['admin_id' => 1],
                whereNotNull: ['user_id', 'admin_id'],
                relations: ['customer'],
                dataLimit: 'all'
            )->unique('user_id');
           
            if (count($allChattingUsers) > 0) {
                $lastChatUser = $allChattingUsers[0]->customer;
                if ($lastChatUser) {
                    $this->chattingRepo->updateAllWhere(
                        params: ['admin_id' => 1, 'user_id' => $lastChatUser['id']],
                        data: ['seen_by_admin' => 1]
                    );
                }
                $chattingMessages = $this->chattingRepo->getListWhereNotNull(
                    orderBy: ['created_at' => 'DESC'],
                    filters: ['admin_id' => $adminId, 'user_id' => $lastChatUser?->id],
                    whereNotNull: ['user_id', 'admin_id'],
                    relations: ['customer'],
                    dataLimit: 'all'
                );
                return view(Chatting::INDEX[VIEW], [
                    'userType' => $type,
                    'allChattingUsers' => $allChattingUsers,
                    'lastChatUser' => $lastChatUser,
                    'chattingMessages' => $chattingMessages,
                ]);
            }
        } elseif ($type == 'seller') {
         
            $allChattingUsers = $this->chattingRepo->getListWhereNotNull(
                orderBy: ['created_at' => 'DESC'],
                filters: ['admin_id' => 1],
                whereNotNull: ['seller_id', 'admin_id'],
                relations: ['seller'],
                dataLimit: 'all'
            )->unique('seller_id');
           
            if (count($allChattingUsers) > 0) {
                $lastChatUser = $allChattingUsers[0]->seller;
               
                if ($lastChatUser) {
                    $this->chattingRepo->updateAllWhere(
                        params: ['admin_id' => $adminId, 'seller_id' => $lastChatUser['id']],
                        data: ['seen_by_admin' => 1]
                    );
                }

                $chattingMessages = $this->chattingRepo->getListWhereNotNull(
                    orderBy: ['created_at' => 'DESC'],
                    filters: ['admin_id' => $adminId, 'seller_id' => $lastChatUser?->id],
                    whereNotNull: ['seller_id', 'admin_id'],
                    relations: ['seller'],
                    dataLimit: 'all'
                );

                return view(Chatting::INDEX[VIEW], [
                    'userType' => $type,
                    'allChattingUsers' => $allChattingUsers,
                    'lastChatUser' => $lastChatUser,
                    'chattingMessages' => $chattingMessages,
                ]);
            }
        }
      
       
        return view(Chatting::INDEX[VIEW], compact('shop'));

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getMessageByUser(Request $request): JsonResponse
{
    Log::info('Request payload: ' . json_encode($request->all()));

    try {
        $adminId = "1";
        $data = [];
        $chatType = null;
        $getUser = null;
        $chattingMessages = null;

        if ($request->has('delivery_man_id')) {
            $chatType = 'delivery_man';
            $getUser = $this->deliveryManRepo->getFirstWhere(['id' => $request['delivery_man_id']]);

            if (!$getUser) {
                throw new Exception('Delivery man not found.');
            }

            $this->chattingRepo->updateAllWhere(
                params: ['admin_id' => $adminId, 'delivery_man_id' => $request['delivery_man_id']],
                data: ['seen_by_admin' => 1]
            );

            $chattingMessages = $this->chattingRepo->getListWhereNotNull(
                orderBy: ['created_at' => 'DESC'],
                filters: ['admin_id' => $adminId, 'delivery_man_id' => $request['delivery_man_id']],
                whereNotNull: ['delivery_man_id', 'admin_id'],
                dataLimit: 'all'
            );

        } elseif ($request->has('user_id')) {
            $chatType = 'customer';
            $getUser = $this->customerRepo->getFirstWhere(['id' => $request['user_id']]);

            if (!$getUser) {
                throw new Exception('Customer not found.');
            }

            $this->chattingRepo->updateAllWhere(
                params: ['admin_id' => $adminId, 'user_id' => $request['user_id']],
                data: ['seen_by_admin' => 1]
            );

            $chattingMessages = $this->chattingRepo->getListWhereNotNull(
                orderBy: ['created_at' => 'DESC'],
                filters: ['admin_id' => $adminId, 'user_id' => $request['user_id']],
                whereNotNull: ['user_id', 'admin_id'],
                dataLimit: 'all'
            );

        } elseif ($request->has('seller_id')) {
            $chatType = 'seller';
            $getUser = $this->vendorRepo->getFirstWhere(['id' => $request['seller_id']]);
            Log::info("Fetched user details for {$chatType}: " . json_encode($getUser));

            if (!$getUser) {
                throw new Exception('Seller not found.');
            }

            $this->chattingRepo->updateAllWhere(
                params: ['admin_id' => $adminId, 'seller_id' => $request['seller_id']],
                data: ['seen_by_admin' => 1]
            );

            $chattingMessages = $this->chattingRepo->getListWhereNotNull(
                orderBy: ['created_at' => 'DESC'],
                filters: ['admin_id' => $adminId, 'seller_id' => $request['seller_id']],
                whereNotNull: ['seller_id', 'admin_id'],
                dataLimit: 'all'
            );
        } else {
            throw new Exception('Invalid request. No valid user identifier provided.');
        }

        // Log data after processing
        Log::info("Fetched user details for {$chatType}: " . json_encode($getUser));
        Log::info("Chat messages for {$chatType}: " . json_encode($chattingMessages));

        // Render messages view
        $data = self::getRenderMessagesView(user: $getUser, message: $chattingMessages, type: $chatType);

        return response()->json($data);

    } catch (Exception $e) {
        // Log the error
        Log::error('Error retrieving chat data.', [
            'error' => $e->getMessage(),
            'request' => $request->all(),
        ]);

        // Return a JSON error response
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}


    /**
     * @param ChattingRequest $request
     * @return JsonResponse
     */
    public function addAdminMessage(ChattingRequest $request): JsonResponse
{
    Log::info('Request payload: ' . json_encode($request->all()));

    $data = [];
    $shop = [
        'name' => getWebConfig(name: 'company_name')
    ];
    $messageForm = (object) [
        'f_name' => 'admin',
        'shop' => (object) $shop,
    ];

    try {
        if ($request->has(key: 'delivery_man_id')) {
            $deliveryMan = $this->deliveryManRepo->getFirstWhere(params: ['id' => $request['delivery_man_id']]);
            if (!$deliveryMan) {
                throw new Exception('Delivery man not found.');
            }

            $this->chattingRepo->add(
                data: $this->chattingService->addChattingData(
                    request: $request,
                    type: 'delivery-man',
                )
            );

            event(new ChattingEvent(key: 'message_from_admin', type: 'delivery_man', userData: $deliveryMan, messageForm: $messageForm));

            $chattingMessages = $this->chattingRepo->getListWhereNotNull(
                orderBy: ['created_at' => 'DESC'],
                filters: ['admin_id' => 0, 'delivery_man_id' => $request['delivery_man_id']],
                whereNotNull: ['delivery_man_id', 'admin_id'],
                dataLimit: 'all'
            );
            $data = self::getRenderMessagesView(user: $deliveryMan, message: $chattingMessages, type: 'delivery_man');
        } elseif ($request->has(key: 'user_id')) {
            $customer = $this->customerRepo->getFirstWhere(params: ['id' => $request['user_id']]);
            if (!$customer) {
                throw new Exception('Customer not found.');
            }

            $this->chattingRepo->add(
                data: $this->chattingService->addChattingData(
                    request: $request,
                    type: 'customer',
                )
            );

            event(new ChattingEvent(key: 'message_from_admin', type: 'customer', userData: $customer, messageForm: $messageForm));

            $chattingMessages = $this->chattingRepo->getListWhereNotNull(
                orderBy: ['created_at' => 'DESC'],
                filters: ['admin_id' => 1, 'user_id' => $request['user_id']],
                whereNotNull: ['user_id', 'admin_id'],
                dataLimit: 'all'
            );
            $data = self::getRenderMessagesView(user: $customer, message: $chattingMessages, type: 'customer');
        } elseif ($request->has(key: 'seller_id')) {
            $seller = Seller::where('id', $request['seller_id'])->first();
            if (!$seller) {
                throw new Exception('Seller not found.');
            }

            $this->chattingRepo->add(
                data: $this->chattingService->addChattingData(
                    request: $request,
                    type: 'seller',
                )
            );

            event(new ChattingEvent(key: 'message_from_admin', type: 'seller', userData: $seller, messageForm: $messageForm));

            $chattingMessages = $this->chattingRepo->getListWhereNotNull(
                orderBy: ['created_at' => 'DESC'],
                filters: ['admin_id' => 1, 'seller_id' => $request['seller_id']],
                whereNotNull: ['seller_id', 'admin_id'],
                dataLimit: 'all'
            );
            $data = self::getRenderMessagesView(user: $seller, message: $chattingMessages, type: 'seller');
        }
    } catch (Exception $e) {
        Log::error('Error adding admin message.', [
            'error' => $e->getMessage(),
            'request' => $request->all(),
        ]);
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }

    return response()->json($data);
}

    /**
     * @param string $tableName
     * @param string $orderBy
     * @param string|int|null $id
     * @return Collection
     */
    protected function getChatList(string $tableName, string $orderBy, string|int $id = null): Collection
    {
        $adminId = 0;
        $columnName = $tableName == 'users' ? 'user_id' : 'delivery_man_id';
        $filters = isset($id) ? ['chattings.admin_id' => $adminId, $columnName => $id] : ['chattings.admin_id' => $adminId];
        return $this->chattingRepo->getListBySelectWhere(
            joinColumn: [$tableName, $tableName . '.id', '=', 'chattings.' . $columnName],
            select: ['chattings.*', $tableName . '.f_name', $tableName . '.l_name', $tableName . '.image', $tableName . '.country_code', $tableName . '.phone'],
            filters: $filters,
            orderBy: ['chattings.created_at' => $orderBy],
        );
    }

    /**
     * @param object $user
     * @param object $message
     * @param string $type
     * @return array
     */
    protected function getRenderMessagesView(object $user, object $message, string $type): array
    {
        $userData = [
            'name' => trim($user['f_name'] . ' ' . $user['l_name']), // Combine first and last name
            'phone' => $user['country_code'] . $user['phone'],       // Construct full phone number
        ];
        // Fetch and set the profile image
        $userData['image'] = getStorageImages(
            path: $user->image_full_url, 
            type: 'backend-profile'
        );
    
      
        return [
            'userData' => $userData,
            'chattingMessages' => view('admin-views.chatting.messages', [
                'lastChatUser' => $user,
                'userType' => $type,
                'chattingMessages' => $message
            ])->render(),
        ];
    }
    

    public function getNewNotification(): JsonResponse
    {
        $chatting = $this->chattingRepo->getListWhereNotNull(
            filters: ['admin_id' => 0, 'seen_by_admin' => 0, 'notification_receiver' => 'admin', 'seen_notification' => 0],
            whereNotNull: ['admin_id'],
        )->count();

        $this->chattingRepo->updateListWhereNotNull(
            filters: ['admin_id' => 0, 'seen_by_admin' => 0, 'notification_receiver' => 'admin', 'seen_notification' => 0],
            whereNotNull: ['admin_id'],
            data: ['seen_notification' => 1]
        );

        return response()->json([
            'newMessagesExist' => $chatting,
            'message' => $chatting > 1 ? $chatting . ' ' . translate('New_Message') : translate('New_Message'),
        ]);
    }

}
