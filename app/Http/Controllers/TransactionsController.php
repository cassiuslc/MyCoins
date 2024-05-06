<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionsRequest;
use App\Http\Requests\UpdateTransactionsRequest;
use App\Models\Transactions;
use App\Http\Requests\TransactionRequest;
use App\Services\TransactionService;
use App\Repositories\Auth\UserReposiotry;

class TransactionsController extends Controller
{
    private $transaction;
    private $user;
    public function __construct(TransactionService $transaction, UserReposiotry $user ) {
        $this->transaction = $transaction;
        $this->user = $user;
    }

    /**
     * Transfer the given amount from the payer's account to the payee's account.
     *
     * @param TransactionRequest $request The request containing value, payer_id, and payee_id.
     * @return Some_Return_Value
     */
    public function transfer(TransactionRequest $request)
    {
        $data = $request->only(['value', 'payer', 'payee']);

        $payer = $this->user->getById($data['payer']);
        $payee = $this->user->getById($data['payee']);

        return $this->transaction->transfer($payer, $payee, $data['value']);
    }

}
