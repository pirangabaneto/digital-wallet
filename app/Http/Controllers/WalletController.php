<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Events\DepositMade;
use Illuminate\Support\Facades\DB;
use App\Models\Wallet;
use App\Events\TransferMade;

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
            ->where('type', 'deposit')
            ->orderBy('created_at', 'asc')
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
            ->where('type', 'transfer')
            ->orderBy('created_at', 'asc')
            ->with('wallet.user', 'to_wallet.user')
            ->get();

        return view('transfer.index', compact('transfers', 'wallet'));
    }

}
