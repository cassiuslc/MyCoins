<?php

namespace App\Services;

use App\Models\User;
use App\Exceptions\MerchantTransactionException;
use App\Interfaces\BalanceServiceInterface;
use Illuminate\Support\Facades\DB;
use App\Classes\ApiResponseClass;
class TransactionService
{
    private $balanceService;
    private $authorizerService;

    public function __construct(BalanceServiceInterface $balanceService)
    {
        $this->balanceService = $balanceService;
        //$this->authorizerService = $authorizerService;
    }

    /**
     * Transfer the given amount from the payer's account to the payee's account.
     *
     * @param User $payer The payer's account.
     * @param User $payee The payee's account.
     * @param float $amount The amount to transfer.
     * @throws MerchantTransactionException If the payee is a merchant.
     * @return bool Returns true if the transfer is successful, false otherwise.
     */
    public function transfer(User $payer, User $payee, float $amount) : bool
    {
        // Verificar se o payee é um lojista
        if ($payee->type === 'merchant') {
            throw new MerchantTransactionException('Merchants cannot receive transfers.');
        }
        
        DB::beginTransaction();

        try {
            // Retirar valor do saldo do pagador
            $result_payer = $this->balanceService->debit($payer, $amount);

            //TODO Consular API autorizadora para efetuar o pagamento
            //$authorization = $this->authorizerService->authorize($payer, $payee, $amount);
    
            // Atualizar o saldo do recebedor
            $result_payee = $this->balanceService->credit($payee, $amount);
    
            // Confirmar a transação
            DB::commit();

            $result = $result_payer && $result_payee;
            
            ApiResponseClass::sendResponse($result,'Transação realizada com sucesso',200);
        } catch (\Exception $e) {
            ApiResponseClass::rollback($e);
            return false;
        }   
    }
}