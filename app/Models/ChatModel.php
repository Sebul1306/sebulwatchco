<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatModel extends Model
{
    protected $table            = 'tabel_chat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['sender_id', 'receiver_id', 'message', 'product_id', 'is_read', 'created_at'];

    public function getChatHistory($user1, $user2)
    {
        return $this->select('tabel_chat.*, product.nama as product_name, product.harga as product_price, product.foto as product_foto')
                    ->join('product', 'product.id = tabel_chat.product_id', 'left')
                    ->where("(tabel_chat.sender_id = $user1 AND tabel_chat.receiver_id = $user2) OR (tabel_chat.sender_id = $user2 AND tabel_chat.receiver_id = $user1)")
                    ->orderBy('tabel_chat.created_at', 'ASC')
                    ->findAll();
    }

    public function getUnreadCount($receiver_id)
    {
        return $this->where('receiver_id', $receiver_id)
                    ->where('is_read', 0)
                    ->countAllResults();
    }
}
