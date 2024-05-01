<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transactions;
class Audit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'wallet_id',
        'description',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transactions::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
