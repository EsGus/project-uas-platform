<?php
namespace App\Controllers;

use App\Models\ProductModel;

class Product extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $data['products'] = $productModel->findAll();
        return view('product/index', $data);
    }

    public function detail($id)
    {
        $productModel = new ProductModel();
        $data['product'] = $productModel->find($id);
        return view('product/detail', $data);
    }
}
