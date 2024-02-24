<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'account_number', 'balance'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function transactions(){
        return $this->hasMany(Transaction::class, 'account_id');
    }

    private function generateUniqueAccountNumber()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = 10;
        $maxAttempts = 10;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $accountNumber = '';

            for ($i = 0; $i < $length; $i++) {
                $index = mt_rand(0, strlen($characters) - 1);
                $accountNumber .= $characters[$index];
            }

            // Check if the generated account number already exists
            $existingAccount = Account::where('account_number', $accountNumber)->first();
            if (!$existingAccount) {
                return $accountNumber;
            }
        }

        throw new Exception('Failed to generate a unique account number. Please try again.');
    }
}
