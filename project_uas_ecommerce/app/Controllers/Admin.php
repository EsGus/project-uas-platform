<?php
namespace App\Controllers;

use App\Models\OrderModel;

class Admin extends BaseController
{
    public function index()
    {
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find(session()->get('user_id'));
        if ($user['username'] !== 'admin') {
            return redirect()->to('/')->with('error', 'Akses ditolak. Hanya admin yang bisa masuk.');
        }

        $orderModel = new OrderModel();
        $data['orders'] = $orderModel->findAll();
        return view('admin/index', $data);
    }

    public function approve($orderId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);
        if ($order && $order['status'] === 'pending') {
            $orderModel->update($orderId, ['status' => 'confirmed']);
            return redirect()->to('/admin')->with('success', 'Pesanan disetujui.');
        }
        return redirect()->to('/admin')->with('error', 'Pesanan tidak ditemukan atau sudah disetujui.');
    }

    public function ship($orderId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);
        if ($order && $order['status'] === 'confirmed') {
            $orderModel->update($orderId, ['shipping_status' => 'shipped']);
            return redirect()->to('/admin')->with('success', 'Pesanan dikirim.');
        }
        return redirect()->to('/admin')->with('error', 'Pesanan tidak ditemukan atau belum disetujui.');
    }

    public function cancelOrder($orderId)
    {
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find(session()->get('user_id'));
        if ($user['username'] !== 'admin') {
            return redirect()->to('/')->with('error', 'Akses ditolak. Hanya admin yang bisa membatalkan pesanan.');
        }

        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);
        if ($order) {
            $orderModel->update($orderId, ['status' => 'cancelled', 'payment_status' => 'cancelled']);
            return redirect()->to('/admin')->with('success', 'Pesanan berhasil dibatalkan.');
        }
        return redirect()->to('/admin')->with('error', 'Pesanan tidak ditemukan.');
    }

    public function deleteOrder($orderId)
    {
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find(session()->get('user_id'));
        if ($user['username'] !== 'admin') {
            return redirect()->to('/')->with('error', 'Akses ditolak. Hanya admin yang bisa menghapus pesanan.');
        }

        $orderModel = new OrderModel();
        if ($orderModel->canDelete($orderId)) {
            $orderModel->delete($orderId);
            return redirect()->to('/admin')->with('success', 'Pesanan berhasil dihapus.');
        }
        return redirect()->to('/admin')->with('error', 'Pesanan tidak dapat dihapus karena bukan status dibatalkan.');
    }
}