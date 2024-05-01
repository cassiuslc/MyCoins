<?php

namespace App\Interfaces;

interface WalletInterface
{
    public function getWallets();

    public function getWalletById($id);

    public function createWallet($data);

    public function updateWallet($id, $data);

    public function deleteWallet($id);

    public function getWalletByUserId($userId);    
}
