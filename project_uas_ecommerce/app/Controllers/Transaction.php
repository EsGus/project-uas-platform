<?php
namespace App\Controllers;

use App\Models\OrderModel;

class Transaction extends BaseController
{
    public function index()
    {
        $orderModel = new OrderModel();
        $userId = session()->get('user_id');
        $transactions = $orderModel->where('user_id', $userId)->findAll();

        if (empty($transactions)) {
            log_message('info', 'No transactions found for user ID ' . $userId);
        } else {
            log_message('info', 'Fetched ' . count($transactions) . ' transactions for user ID ' . $userId);
        }

        $data['transactions'] = $transactions;
        return view('transaction/index', $data);
    }
}