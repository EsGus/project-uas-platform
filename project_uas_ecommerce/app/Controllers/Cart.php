<?php
namespace App\Controllers;

use App\Models\CartModel;
use App\Models\ProductModel;
use App\Models\OrderModel;

class Cart extends BaseController
{
    protected $cartModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
    }

    public function index()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Silakan login untuk melihat keranjang belanja.');
        }

        $userId = session()->get('user_id');
        $data['cart_items'] = $this->cartModel->getCartItemsByUser($userId);
        return view('cart/index', $data);
    }

    public function add()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Silakan login untuk menambahkan produk ke keranjang.');
        }

        $userId = session()->get('user_id');
        $productId = $this->request->getPost('product_id');
        $quantity = $this->request->getPost('quantity');

        $productModel = new ProductModel();
        $product = $productModel->find($productId);
        if (!$product) {
            return redirect()->to('/')->with('error', 'Produk tidak ditemukan.');
        }

        $existingItem = $this->cartModel->where(['user_id' => $userId, 'product_id' => $productId])->first();
        if ($existingItem) {
            $this->cartModel->update($existingItem['id'], ['quantity' => $existingItem['quantity'] + $quantity]);
        } else {
            $this->cartModel->save([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }

        return redirect()->to('/cart')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function remove($id)
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Silakan login untuk mengelola keranjang belanja.');
        }

        $userId = session()->get('user_id');
        $this->cartModel->removeItem($id, $userId);
        return redirect()->to('/cart')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function checkout()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Silakan login untuk checkout.');
        }

        $userId = session()->get('user_id');
        $cartItems = $this->cartModel->getCartItemsByUser($userId);

        if (empty($cartItems)) {
            return redirect()->to('/cart')->with('error', 'Keranjang belanja kosong.');
        }

        $orderModel = new OrderModel();
        foreach ($cartItems as $item) {
            $orderModel->save([
                'user_id' => $userId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'total_price' => $item['price'] * $item['quantity'],
                'status' => 'pending',
                'shipping_status' => 'pending',
                'payment_status' => 'pending'
            ]);
        }

        $this->cartModel->clearCart($userId);
        return redirect()->to('/cart/payment')->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
    }

    public function payment()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Silakan login untuk melakukan pembayaran.');
        }

        $userId = session()->get('user_id');
        $orderModel = new OrderModel();
        $data['orders'] = $orderModel->where(['user_id' => $userId, 'payment_status' => 'pending'])->findAll();
        return view('cart/payment', $data);
    }
}