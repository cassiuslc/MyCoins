<?php

namespace App\Interfaces;

interface BalanceServiceInterface
{
  function debit($id, $amount);
  
  function credit($id, $amount);

  function balance($id);
}
