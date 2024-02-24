<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\DepositeRequest;
use App\Http\Requests\TransferRequest;
use App\Http\Requests\CreateAccountRequest;

class AccountController extends Controller
{
    public function create(CreateAccountRequest $request)
    {
        $customer = auth()->user();

        $account = new Account();
        $account->customer_id = $customer->id;
        $account->account_number = $this->generateUniqueAccountNumber();
        $account->balance = $request->input('initial_deposit', 0);

        $account->save();

        return response()->json($account, 201);
    }

    public function deposit(DepositeRequest $request, Account $account)
    {   
        $amount = $request->input('amount');

        $account->balance += $amount;
        $account->save();

        $transaction = new Transaction();
        $transaction->account_id = $account->id;
        $transaction->type = Transaction::TYPE_DEPOSIT;
        $transaction->amount = $amount;
        $transaction->save();

        return response()->json(['message' => 'Deposit successful']);
    }

    public function withdraw(DepositeRequest $request, Account $account)
    {
        $amount = $request->input('amount');

        if ($amount > $account->balance) {
            return response()->json(['message' => 'Insufficient funds'], 400);
        }

        $account->balance -= $amount;
        $account->save();

        $transaction = new Transaction();
        $transaction->account_id = $account->id;
        $transaction->type = Transaction::TYPE_WITHDRAW;
        $transaction->amount = $amount;
        $transaction->save();

        return response()->json(['message' => 'Withdrawal successful']);
    }

    public function transfer(TransferRequest $request)
    {
        $fromAccount = Account::findOrFail($request->input('from_account_id'));
        $toAccount = Account::findOrFail($request->input('to_account_id'));
        $amount = $request->input('amount');

        if ($amount > $fromAccount->balance) {
            return response()->json(['message' => 'Insufficient funds'], 400);
        }

        $fromAccount->balance -= $amount;
        $fromAccount->save();

        $toAccount->balance += $amount;
        $toAccount->save();

        $transaction = new Transaction();
        $transaction->account_id = $fromAccount->id;
        $transaction->type = Transaction::TYPE_TRANSFER;
        $transaction->amount = $amount;
        $transaction->related_account_id = $toAccount->id;
        $transaction->save();

        return response()->json(['message' => 'Transfer successful']);
    }

    public function getBalance(Account $account)
    {
        return response()->json(['balance' => $account->balance]);
    }

    public function getTransactions(Account $account)
    {
        $transactions = $account->transactions()->get();
        return response()->json($transactions);
    }
}
