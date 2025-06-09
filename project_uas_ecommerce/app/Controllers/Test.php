<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class Test extends Controller
{
    public function index()
    {
        try {
            $db = \Config\Database::connect();
            $result = $db->query('SELECT * FROM products')->getResultArray();
            if ($result) {
                echo "Koneksi database berhasil. Jumlah produk: " . count($result) . "<br>";
                print_r($result);
            } else {
                echo "Tidak ada produk di tabel.";
            }
        } catch (\Exception $e) {
            echo "Koneksi gagal: " . $e->getMessage();
        }
    }
}