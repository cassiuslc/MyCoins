<?php

namespace App\Repositories;

use App\Interfaces\WalletInterface;
use App\Models\Wallet as WalletModel;


class WalletReposiotry implements WalletInterface
{   public function getWallets()
    {
        return WalletModel::all();
    }

    public function getWalletById($id)
    {
        return WalletModel::find($id);
    }

    public function createWallet($data)
    {
        return WalletModel::create($data);
    }

    public function updateWallet($id, $data)
    {
        $wallet = $this->getWalletById($id);
        if ($wallet) {
            $wallet->fill($data)->save();
            return $wallet;
        }
        return false;
    }

    public function deleteWallet($id)
    {
        $wallet = $this->getWalletById($id);
        if ($wallet) {
            return $wallet->delete();
        }
        return false;
    }

    public function getWalletByUserId($userId)
    {
        return WalletModel::where('user_id', $userId)->get();
    }

}
