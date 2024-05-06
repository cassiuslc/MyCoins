<?php

namespace App\Interfaces;

interface AuthorizerServiceInterface
{
  function authorize(): bool;
}
