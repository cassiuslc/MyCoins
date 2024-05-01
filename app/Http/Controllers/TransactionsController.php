<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionsRequest;
use App\Http\Requests\UpdateTransactionsRequest;
use App\Models\Transactions;
use App\Http\Requests\TransactionRequest;
use App\Services\TransactionService;

class TransactionsController extends Controller
{
    private $transaction;
    public function __construct(TransactionService $transaction) {
        $this->transaction = $transaction;
    }

    /**
     * Transfer the given amount from the payer's account to the payee's account.
     *
     * @param TransactionRequest $request The request containing value, payer_id, and payee_id.
     * @return Some_Return_Value
     */
    public function transfer(TransactionRequest $request)
    {
        $data = $request->only(['value', 'payer_id', 'payee_id']);

        return $this->transaction->transfer($data['payer_id'], $data['payee_id'], $data['value']);
    }

}
