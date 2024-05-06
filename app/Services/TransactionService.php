<?php

namespace App\Services;

use App\Models\User;
use App\Exceptions\MerchantTransactionException;
use App\Interfaces\BalanceServiceInterface;
use App\Interfaces\AuthorizerServiceInterface;
use Illuminate\Support\Facades\DB;
use App\Classes\ApiResponseClass;
use Exception;
class TransactionService
{
    private $balanceService;
    private $authorizerService;

    public function __construct(BalanceServiceInterface $balanceService, AuthorizerServiceInterface $authorizerService)
    {
        $this->balanceService = $balanceService;
        $this->authorizerService = $authorizerService;
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
    public function transfer(User $payer, User $payee, float $amount)
    {
        // Verificar se o payee é um lojista
        if ($payer->type === 'merchant') {
            ApiResponseClass::throw('error','Merchants cannot receive transfers.');
        }

        try {
            DB::beginTransaction();
            // Retirar valor do saldo do pagador
            $result_payer = $this->balanceService->debit($payer->id, $amount);

            //Consultar API autorizadora para efetuar o pagamento
            if(!$this->authorizerService->authorize()){
                throw new Exception('The payment was not authorized.');
            }

            // Atualizar o saldo do recebedor
            $result_payee = $this->balanceService->credit($payee->id, $amount);

            // Confirmar a transação
            DB::commit();

            $result = [
                'payer' => $result_payer,
                'payee' => $result_payee
            ];

            return ApiResponseClass::sendResponse($result,'Transação realizada com sucesso',200);
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e, $e->getMessage());
        }   
    }
}