<?php

namespace App\Services;

use App\Interfaces\BalanceServiceInterface;
use App\Interfaces\WalletInterface;
use Illuminate\Support\Facades\DB;
use App\Classes\ApiResponseClass;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Exception;

class BalanceService implements BalanceServiceInterface
{ 
    private $wallet;
    private $user;
    /**
     * Constructs a new instance of the class.
     *
     * @param WalletInterface $wallet The wallet interface object.
     */
    public function __construct(WalletInterface $wallet){
        $this->wallet = $wallet;
    }

    public function debit($userId, $amount = 0)
    {
        $amount = intval($amount * 100);

        if ($amount <= 0) {
            throw new Exception('Debit amount must be greater than zero.');
        }

        $wallet = $this->wallet->getWalletByUserId($userId);
        if (!$wallet) {
            throw new Exception('User does not have a wallet.');
        }

        if (!$this->hasSufficientBalance($wallet, $amount)) {
            throw new Exception('User does not have sufficient balance.');
        }

        $wallet->amount -= $amount;
        $success = $wallet->save();
        if (!$success) {
            throw new Exception('Failed to update wallet.');
        }
        
        return $this->filterWalletFields($wallet);
    }

    public function credit($userId, $amount = 0)
    {
        $amount = intval($amount * 100);

        if ($amount <= 0) {
            throw new Exception('Invalid credit amount.');
        }

        $wallet = $this->wallet->getWalletByUserId($userId);
        $wallet->amount += $amount;
        $success = $wallet->save();
        if (!$success) {
            throw new Exception('Failed to update wallet.');
        }

        return $this->filterWalletFields($wallet);
    }

    /**
     * Calculate the balance of a user's wallet.
     *
     * @param int $id The ID of the user
     * @return float The balance amount
     */
    public function balance($wallet) : float
    {        
        if ($wallet && $wallet->amount > 0) {
            $balance = $wallet->amount / 100.0;
            return number_format($balance, 1);
        }

        return 0.0;
    }


    
    /**
     * Check if the user with the given ID has sufficient balance in their wallet.
     *
     * @param int $id The ID of the user
     * @param int $amount The amount to check for sufficiency
     * @return bool
     */
    private function hasSufficientBalance($wallet, $amount): bool
    {
        return $wallet ? $wallet->amount >= $amount : false;
    }

    private function filterWalletFields($wallet)
    {
        return [
            'user_id' => $wallet->user_id,
            'last_transaction_date' => $wallet->last_transaction_date,
            'balance' => $this->balance($wallet),
        ];
    }


}
