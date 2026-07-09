<?php

namespace App\Controllers;

use App\Models\ChatModel;
use App\Models\UserModel;
use App\Models\ProductModel;

class ChatController extends BaseController
{
    protected $chatModel;
    protected $userModel;
    protected $productModel;

    public function __construct()
    {
        $this->chatModel = new ChatModel();
        $this->userModel = new UserModel();
        $this->productModel = new ProductModel();
    }

    private function isAuth()
    {
        return session()->get('logged_in') || session()->get('isLoggedIn');
    }

    public function index()
    {
        if (!$this->isAuth()) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Chat & Dukungan Pelanggan',
            'role' => session()->get('role'),
            'current_user_id' => session()->get('id')
        ];

        if (session()->get('role') == 'admin' || session()->get('role') == 'owner') {
            $data['customers'] = $this->userModel->where('role', 'pelanggan')->findAll();
        } else {
            $admin = $this->userModel->where('role', 'admin')->first();
            $data['admin_target'] = $admin ? $admin['id'] : 1;
        }

        return view('v_chat', $data);
    }

    public function getMessages($target_id)
    {
        if (!$this->isAuth()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authenticated']);
        }

        $current_user_id = session()->get('id');
        if (session()->get('role') == 'admin' || session()->get('role') == 'owner') {
            $current_user_id = 1; // Pool all admins to ID 1
        }
        
        // Tandai pesan sebagai sudah dibaca
        $this->chatModel->where('sender_id', $target_id)
                        ->where('receiver_id', $current_user_id)
                        ->where('is_read', 0)
                        ->set(['is_read' => 1])
                        ->update();

        $messages = $this->chatModel->getChatHistory($current_user_id, $target_id);

        return $this->response->setJSON(['status' => 'success', 'data' => $messages]);
    }

    public function sendMessage()
    {
        if (!$this->isAuth()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authenticated']);
        }

        $sender_id = session()->get('id');
        if (session()->get('role') == 'admin' || session()->get('role') == 'owner') {
            $sender_id = 1; // Pool all admins to ID 1
        }
        $receiver_id = $this->request->getPost('receiver_id');
        $message = $this->request->getPost('message');
        $product_id = $this->request->getPost('product_id');

        if (empty($receiver_id) || (empty($message) && empty($product_id))) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pesan tidak boleh kosong']);
        }

        $data = [
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'message' => $message ?? '',
            'is_read' => 0
        ];

        if (!empty($product_id)) {
            $data['product_id'] = $product_id;
        }

        $this->chatModel->save($data);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function getUnread()
    {
        if (!$this->isAuth()) {
            return $this->response->setJSON(['count' => 0]);
        }
        $current_user_id = session()->get('id');
        if (session()->get('role') == 'admin' || session()->get('role') == 'owner') {
            $current_user_id = 1; // Pool all admins to ID 1
        }
        if (!$current_user_id) return $this->response->setJSON(['count' => 0]);
        $count = $this->chatModel->getUnreadCount($current_user_id);
        
        return $this->response->setJSON(['count' => $count]);
    }

    public function getCustomers()
    {
        if (!$this->isAuth()) {
            return $this->response->setJSON(['customers' => []]);
        }
        
        $current_user_id = session()->get('id');
        if (session()->get('role') == 'admin' || session()->get('role') == 'owner') {
            $current_user_id = 1; // Pool all admins to ID 1
        }

        // Get all unique users who have chatted with the admin
        $chatUsers = $this->chatModel->select('sender_id, receiver_id')
                                     ->where('sender_id', $current_user_id)
                                     ->orWhere('receiver_id', $current_user_id)
                                     ->findAll();
        
        $activeUserIds = [];
        foreach ($chatUsers as $cu) {
            if ($cu['sender_id'] != $current_user_id) $activeUserIds[] = $cu['sender_id'];
            if ($cu['receiver_id'] != $current_user_id) $activeUserIds[] = $cu['receiver_id'];
        }
        $activeUserIds = array_unique($activeUserIds);

        if (empty($activeUserIds)) {
            return $this->response->setJSON(['customers' => []]);
        }

        $customers = $this->userModel->where('role', 'pelanggan')
                                     ->whereIn('id', $activeUserIds)
                                     ->findAll();
        
        foreach ($customers as &$c) {
            $c['unread'] = $this->chatModel->where('sender_id', $c['id'])
                                           ->where('receiver_id', $current_user_id)
                                           ->where('is_read', 0)
                                           ->countAllResults();
        }

        return $this->response->setJSON(['customers' => $customers]);
    }

    public function getProducts()
    {
        if (!$this->isAuth()) {
            return $this->response->setJSON(['products' => []]);
        }
        $keyword = $this->request->getGet('q') ?? '';
        
        $builder = $this->productModel;
        if (!empty($keyword)) {
            $builder = $builder->like('nama', $keyword);
        }
        $products = $builder->where('jumlah >', 0)->findAll(20);
        
        return $this->response->setJSON(['products' => $products]);
    }
}
