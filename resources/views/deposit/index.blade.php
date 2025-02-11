<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Deposit History
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-500 text-white rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-500 text-white rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($deposits->isEmpty())
                        <p class="text-gray-300">No deposits found.</p>
                    @else
                        <table class="w-full border-collapse border border-gray-600 text-center">
                            <thead>
                                <tr class="bg-gray-700 text-white">
                                    <th class="border border-gray-600 px-4 py-2">Date</th>
                                    <th class="border border-gray-600 px-4 py-2">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deposits as $deposit)
                                    <tr class="border border-gray-600">
                                        <td class="px-4 py-2">{{ $deposit->created_at->format('m/d/Y H:i') }}</td>
                                        <td class="px-4 py-2 text-green-500">$ {{ number_format($deposit->amount, 2, '.', ',') }}</td>
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
