<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            New Transfer
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-500 text-white rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-500 text-white rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <p>Your current balance: ${{ number_format($wallet->balance, 2, '.', ',') }}</p>

                    <form action="{{ route('wallet.transfer') }}" method="POST" class="mt-4">
                        @csrf

                        <div class="mb-4">
                            <label for="to_wallet_id" class="block text-sm font-medium text-gray-200">Recipient Wallet</label>
                            <select name="to_wallet_id" id="to_wallet_id" class="w-full mt-1 p-2 border rounded">
                                <option value="">-- Select Wallet --</option>
                                @foreach ($wallets as $recipientWallet)
                                    <option value="{{ $recipientWallet->id }}">
                                        {{ $recipientWallet->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-200">Amount</label>
                            <input type="number" step="0.01" name="amount" id="amount" class="w-full mt-1 p-2 border rounded" required>
                        </div>

                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                            Transfer
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
