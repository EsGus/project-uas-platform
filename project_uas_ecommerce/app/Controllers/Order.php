<?php
namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\ProductModel;

class Order extends BaseController
{
    public function create($productId)
    {
        $productModel = new ProductModel();
        $data['product'] = $productModel->find($productId);
        if (!$data['product']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Produk tidak ditemukan');
        }
        return view('order/create', $data);
    }
    
    public function store()
    {
        $orderModel = new OrderModel();
        $productId = $this->request->getPost('product_id');
        $quantity = $this->request->getPost('quantity');

        $productModel = new ProductModel();
        $product = $productModel->find($productId);
        if (!$product) {
            return redirect()->to('/')->with('error', 'Produk tidak ditemukan.');
        }

        // Check stock availability
        if ($product['stock'] < $quantity) {
            return redirect()->to('/product/' . $productId)->with('error', 'Stok tidak mencukupi. Sisa stok: ' . $product['stock']);
        }

        $totalPrice = $product['price'] * $quantity;

        $orderModel->save([
            'user_id' => session()->get('user_id'),
            'product_id' => $productId,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'shipping_status' => 'pending',
            'payment_status' => 'pending'
        ]);

        return redirect()->to('/cart/payment')->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
    }
    
    public function uploadProof($orderId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);
        
        if (!$order || $order['user_id'] !== session()->get('user_id')) {
            return redirect()->to('/')->with('error', 'Pesanan tidak ditemukan atau akses ditolak.');
        }
        
        return view('order/upload_proof', ['order' => $order]);
    }
    
    
    
    public function cancelPayment($orderId)
    {
        $orderModel = new OrderModel();
        $productModel = new ProductModel();
        $order = $orderModel->find($orderId);

        if (!$order) {
            log_message('error', 'Order ID ' . $orderId . ' not found.');
            return redirect()->to('/cart/payment')->with('error', 'Pesanan tidak ditemukan.');
        }

        if ($order['user_id'] !== session()->get('user_id')) {
            log_message('error', 'Access denied for order ID ' . $orderId . '. User ID mismatch.');
            return redirect()->to('/cart/payment')->with('error', 'Akses ditolak.');
        }

        if ($order['payment_status'] === 'completed') {
            log_message('error', 'Order ID ' . $orderId . ' cannot be cancelled because payment is completed.');
            return redirect()->to('/cart/payment')->with('error', 'Pembayaran yang sudah selesai tidak dapat dibatalkan.');
        }

        // Restore stock
        $product = $productModel->find($order['product_id']);
        if ($product) {
            $newStock = $product['stock'] + $order['quantity'];
            $productModel->update($product['id'], ['stock' => $newStock]);
            log_message('info', 'Stock restored for product ID ' . $product['id'] . '. New stock: ' . $newStock);
        }

        // Update order status
        $updateData = [
            'status' => 'cancelled',
            'shipping_status' => 'cancelled',
            'payment_status' => 'cancelled'
        ];
        $orderModel->update($orderId, $updateData);
        log_message('info', 'Updated order ID ' . $orderId . ' with: ' . json_encode($updateData));

        return redirect()->to('/transactions')->with('success', 'Pembayaran berhasil dibatalkan dan stok telah dikembalikan.');
    }
    
    public function deleteTransaction($orderId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);
        
        if (!$order || $order['user_id'] !== session()->get('user_id')) {
            return redirect()->to('/transactions')->with('error', 'Transaksi tidak ditemukan atau akses ditolak.');
        }
        
        if ($order['payment_status'] === 'completed') {
            return redirect()->to('/transactions')->with('error', 'Transaksi yang sudah dibayar tidak dapat dihapus.');
        }
        
        $orderModel->delete($orderId);
        return redirect()->to('/transactions')->with('success', 'Transaksi berhasil dihapus.');
    }
    
    public function postUploadProof($orderId)
    {
        $orderModel = new OrderModel();
        $productModel = new ProductModel();

        // Verify the order exists and belongs to the user
        $order = $orderModel->find($orderId);
        if (!$order || $order['user_id'] !== session()->get('user_id')) {
            return redirect()->to('/transactions')->with('error', 'Pesanan tidak ditemukan atau akses ditolak.');
        }

        // Get the file
        $file = $this->request->getFile('proof_image');
        if (!$file->isValid()) {
            return redirect()->to('/order/upload_proof/' . $orderId)->with('error', 'File tidak valid. Pastikan Anda memilih gambar yang benar.');
        }

        // Move the uploaded file
        $fileName = $file->getRandomName();
        $uploadPath = 'uploads/proof/' . $fileName;
        if (!$file->move('uploads/proof', $fileName)) {
            return redirect()->to('/order/upload_proof/' . $orderId)->with('error', 'Gagal mengunggah file. Silakan coba lagi.');
        }

        // Get product details
        $product = $productModel->find($order['product_id']);
        if (!$product) {
            return redirect()->to('/order/upload_proof/' . $orderId)->with('error', 'Produk tidak ditemukan.');
        }

        // Check if stock is sufficient
        if ($product['stock'] < $order['quantity']) {
            return redirect()->to('/order/upload_proof/' . $orderId)->with('error', 'Stok tidak mencukupi untuk menyelesaikan pesanan.');
        }

        // Update stock
        $newStock = $product['stock'] - $order['quantity'];
        $productModel->update($product['id'], ['stock' => $newStock]);

        // Log the stock update
        log_message('info', 'Stock updated for product ID ' . $product['id'] . '. New stock: ' . $newStock);

        // Update order details
        $orderModel->update($orderId, [
            'proof_image' => $fileName,
            'payment_status' => 'completed',
            'status' => 'confirmed'
        ]);

        return redirect()->to('/transactions')->with('success', 'Pembayaran berhasil dilakukan dan stok telah diperbarui.');
    }
}