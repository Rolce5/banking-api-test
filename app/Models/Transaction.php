<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAW = 'withdraw';
    const TYPE_TRANSFER = 'transfer';
    public function account(){
        return $this->belongsTo(Account::class, 'account_id');
    }
}
