<?php
namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'product_id', 'quantity', 'total_price', 'proof_image', 'status', 'shipping_status', 'payment_status'];

    public function canDelete($orderId)
    {
        $order = $this->find($orderId);
        return $order && $order['status'] === 'cancelled';
    }
}