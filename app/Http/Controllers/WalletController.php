<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Events\DepositMade;
use Illuminate\Support\Facades\DB;
use App\Models\Wallet;
use App\Events\TransferMade;
use App\Events\TransactionReversed;

class WalletController extends Controller
{
    public function index()
    {
        $wallet = Auth::user()->wallet;
        return view('wallet.index', compact('wallet'));
    }

    public function getDepositForm()
    {
        return view('deposit.create');
    }
    
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);
        
        $amount = $request->input('amount');

        $wallet = Auth::user()->wallet;
        
        DB::beginTransaction();

        try {
            $wallet->balance += $amount;
            $wallet->save();
            
            event(new DepositMade($wallet, $amount));
            DB::commit();

            return redirect()->route('wallet.index', $wallet)->with('success', 'Deposit successful!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred while processing the deposit.']);
        }
    }

    public function depositHistory()
    {
        $user = auth()->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return back()->with('error', 'Wallet not found.');
        }

        $deposits = Transaction::where('wallet_id', $wallet->id)
            ->whereIn('type', ['deposit', 'deposit_reverse'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('deposit.index', compact('deposits'));
    }

    public function getTransferForm()
    {
        $wallet = auth()->user()->wallet; 
        $wallets = Wallet::where('id', '!=', $wallet->id)->get(); 

        return view('transfer.create', compact('wallet', 'wallets'));
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'to_wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $toWallet = Wallet::findOrFail($request->input('to_wallet_id'));
        $amount = $request->input('amount');

        $fromWallet = Auth::user()->wallet;

        if ($fromWallet->balance < $amount) {
            return back()->withErrors(['error' => 'Insufficient balance for the transfer.']);
        }

        DB::beginTransaction();

        try {
            $fromWallet->balance -= $amount;
            $fromWallet->save();

            $toWallet->balance += $amount;
            $toWallet->save();

            event(new TransferMade($fromWallet, $toWallet, $amount));

            DB::commit();

            return redirect()->route('wallet.index', $fromWallet)->with('success', 'Transfer successful!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred while processing the transfer.']);
        }
    }

    public function transferHistory()
    {
        $user = auth()->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return back()->with('error', 'Wallet not found.');
        }

        $transfers = Transaction::where(function ($query) use ($wallet) {
            $query->where('wallet_id', $wallet->id)
                  ->orWhere('to_wallet_id', $wallet->id);
        })
            ->whereIn('type', ['transfer', 'transfer_reverse'])
            ->orderBy('created_at', 'desc')
            ->with('wallet.user', 'to_wallet.user')
            ->get();

        return view('transfer.index', compact('transfers', 'wallet'));
    }

    public function depositReverse(Request $request, $depositId)
    {
        $transaction = Transaction::with(['wallet', 'to_wallet'])->findOrFail($depositId);
        $wallet = auth()->user()->wallet;
        
        if ($transaction->wallet_id !== $wallet->id && $transaction->to_wallet_id !== $wallet->id) {
            return back()->withErrors(['error' => 'Unauthorized action.']);
        }
        
        DB::beginTransaction();

        try {
            if ($transaction->wallet->balance < $transaction->amount) {
                return back()->withErrors(['error' => 'Insufficient balance to reverse deposit.']);
            }
            
            $transaction->wallet->decrement('balance', $transaction->amount);
            
            event(new TransactionReversed($transaction,'deposit_reverse'));
            
            DB::commit();

            return redirect()->route('wallet.index', $wallet)->with('success', 'Deposit successfully reversed!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred while processing the deposit reverse.']);
        }

    }

    public function transferReverse(Request $request, $transferId)
    {
        $transaction = Transaction::with(['wallet', 'to_wallet'])->findOrFail($transferId);
        $wallet = auth()->user()->wallet;
        
        if ($transaction->wallet_id !== $wallet->id) {
            return back()->withErrors(['error' => 'Unauthorized action. You can only reverse transactions you have made!']);
        }

        if ($transaction->to_wallet->balance < $transaction->amount) {
            return back()->withErrors(['error' => 'Insufficient balance to reverse transfer.']);
        }

        DB::beginTransaction();
        
        try {
            $fromWallet = $transaction->wallet;
            $toWallet = $transaction->to_wallet;

            $toWallet->decrement('balance', $transaction->amount);
            $fromWallet->increment('balance', $transaction->amount);
            
            event(new TransactionReversed($transaction,'transfer_reverse'));
            
            DB::commit();

            return redirect()->route('wallet.index', $wallet)->with('success', 'Transfer successfully reversed!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred while processing the transfer reverse.']);
        }
    }


}
