<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Events\DepositMade;
use Illuminate\Support\Facades\DB;

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

}
