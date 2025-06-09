<?php
namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table = 'cart_items';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'product_id', 'quantity'];

    public function getCartItemsByUser($userId)
    {
        return $this->select('cart_items.*, products.name, products.price, products.image')
                    ->join('products', 'products.id = cart_items.product_id')
                    ->where('cart_items.user_id', $userId)
                    ->findAll();
    }

    public function removeItem($id, $userId)
    {
        return $this->where(['id' => $id, 'user_id' => $userId])->delete();
    }

    public function clearCart($userId)
    {
        return $this->where('user_id', $userId)->delete();
    }
}