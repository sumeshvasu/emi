<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Loan Details') }}
        </h2>
    </x-slot>
    @if ($loan['emi'] == 0)
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" style="overflow-x: auto;">
                    <div class="max-w-xl">
                        <x-primary-button onclick="window.location='{{ route('loan.details', [1]) }}'">
                            {{ __('Process Data') }}
                        </x-primary-button>
                        <table class="min-w-full border divide-y divide-gray-200" style="margin-top: 10px;">
                            <thead>
                                <tr>
                                    <th class="bg-gray-100 px-6 py-3 text-sm text-left border">
                                        <span
                                            class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Client_Id</span>
                                    </th>
                                    <th class="bg-gray-100 px-6 py-3 text-sm text-left border">
                                        <span
                                            class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">num_of_payment</span>
                                    </th>
                                    <th class="bg-gray-100 px-6 py-3 text-sm text-left border">
                                        <span
                                            class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">first_payment_date</span>
                                    </th>
                                    <th class="bg-gray-100 px-6 py-3 text-sm text-left border">
                                        <span
                                            class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">last_payment_date</span>
                                    </th>
                                    <th class="bg-gray-100 px-6 py-3 text-sm text-left border">
                                        <span
                                            class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">loan_amount</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                                @foreach ($loan['loan_details'] as $row)
                                    <tr class="bg-white">
                                        <td class="border px-6 py-3 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $row->clientid }}
                                        </td>
                                        <td class="border px-6 py-3 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $row->num_of_payment }}
                                        </td>
                                        <td class="border px-6 py-3 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $row->first_payment_date }}
                                        </td>
                                        <td class="border px-6 py-3 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $row->last_payment_date }}
                                        </td>
                                        <td class="border px-6 py-3 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $row->loan_amount }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($loan['emi'] == 1)
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" style="overflow-x: auto;">
                    <div class="max-w-xl">
                        @include('loan.emi')
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
