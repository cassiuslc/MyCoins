<?php

namespace App\Services;

use App\Interfaces\BalanceServiceInterface;
use App\Interfaces\WalletInterface;
use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\InvalidArgumentException;
use Illuminate\Support\Facades\DB;

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

    /**
     * Debits the specified user with the given amount.
     *
     * @param int $userId The ID of the user to debit.
     * @param int $amount The amount to debit the user with. Default is 0.
     * @throws InvalidArgumentException If the amount is invalid.
     * @throws InsufficientBalanceException If the user does not have sufficient balance.
     * @return bool True if the debit was successful, false otherwise.
     */
    public function debit($userId, $amount = 0): bool
    {
        $amount = intval($amount * 100);

        if ($amount <= 0) {
            throw new InvalidArgumentException('Invalid debit amount.');
        }

        try {
            $wallet = $this->wallet->getWalletByUserId($userId);
            if (!$this->hasSufficientBalance($userId, $amount)) {
                throw new InsufficientBalanceException('User does not have sufficient balance.');
            }

            $wallet->amount -= $amount;
            $success = $wallet->save();
            return $success;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Credits the specified user with the given amount.
     *
     * @param int $userId The ID of the user to credit.
     * @param int $amount The amount to credit the user with. Default is 0.
     * @throws InvalidArgumentException If the amount is invalid.
     * @return bool True if the credit was successful, false otherwise.
     */
    public function credit($userId, $amount = 0): bool
    {
        $amount = intval($amount * 100);

        if ($amount <= 0) {
            throw new InvalidArgumentException('Invalid credit amount.');
        }

        try {
            $wallet = $this->wallet->getWalletByUserId($userId);
            $wallet->amount += $amount;
            $success = $wallet->save();
            return $success;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Calculate the balance of a user's wallet.
     *
     * @param int $id The ID of the user
     * @return float The balance amount
     */
    public function balance($id) : float
    {
        $wallet = $this->wallet->getWalletByUserId($id);
        
        if ($wallet && $wallet->amount > 0) {
            return $wallet->amount / 100.0;
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
    private function hasSufficientBalance($id, $amount): bool
    {
        $wallet = $this->wallet->getWalletByUserId($id);
        return $wallet ? $wallet->amount >= $amount : false;
    }

}
