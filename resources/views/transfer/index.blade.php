<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Transfer History
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-[#298097] text-white rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-500 text-white rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-500 text-white rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($transfers->isEmpty())
                        <p class="mt-4">No transfers found.</p>
                    @else
                    <table class="min-w-full mt-6 table-auto">
                        <table class="min-w-full mt-6 table-auto text-center">
                            <thead>
                                <tr class="border-b">
                                    <th class="px-4 py-2">From User</th>
                                    <th class="px-4 py-2">To User</th>
                                    <th class="px-4 py-2">Date</th>
                                    <th class="px-4 py-2">Amount</th>
                                    <th class="px-4 py-2">Outcome / Income</th>
                                    <th class="border border-gray-600 px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transfers as $transfer)
                                    <tr class="border-b {{ $transfer->type === 'transfer_reverse' ? 'bg-red-200' : '' }}">
                                        
                                        <td class="px-4 py-2">
                                                {{ $transfer->wallet->user->name }}
                                        </td>

                                        <td class="px-4 py-2">
                                            {{ $transfer->to_wallet->user->name }}
                                        </td>
                        
                                        <td class="px-4 py-2">{{ $transfer->created_at->format('Y-m-d H:i:s') }}</td>
                        
                                        <td class="px-4 py-2">
                                            @if ($transfer->wallet_id == $wallet->id)
                                                <span class="text-yellow-500">-{{ number_format($transfer->amount, 2, ',', '.') }}</span>
                                            @else
                                                <span class="text-green-500">+{{ number_format($transfer->amount, 2, ',', '.') }}</span>
                                            @endif
                                        </td>
                        
                                        <td class="px-4 py-2">
                                            @if ($transfer->wallet_id == $wallet->id)
                                                Outcome
                                            @else
                                                Income
                                            @endif
                                        </td>

                                        <td class="px-4 py-2">
                                            @if ($transfer->type !== 'transfer_reverse')
                                                <form action="{{ route('transfer.reverse', $transfer->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reverse this transfer?');">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">
                                                        Reverse
                                                    </button>
                                                </form>
                                            @else
                                                Transfer Reversed
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>                        
                    
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
