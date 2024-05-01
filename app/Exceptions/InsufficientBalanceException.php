<?php

namespace App\Exceptions;

use Exception;
use App\Classes\ApiResponseClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InsufficientBalanceException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        // Registra a exceção em um arquivo de log
        Log::info($this->message, ['exception' => $this]);
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        // Chama o método rollback da classe ApiResponseClass
        ApiResponseClass::rollback($this, $this->message);
    }
}
