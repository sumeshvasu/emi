<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('EMI Details') }}
    </h2>
</x-slot>
<x-primary-button onclick="window.location='{{ route('loan.details', [0]) }}'">
    {{ __('Back') }}
</x-primary-button>
<table class="min-w-full border divide-y divide-gray-200" style="margin-top: 10px;">
    <thead>
        <tr>
            @foreach ($loan['emi_table_column'] as $column)
                <th class="bg-gray-100 px-6 py-3 text-sm text-left border">
                    <span
                        class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">{{ $column }}</span>
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200 divide-solid">
        @foreach ($loan['emi_list'] as $row)
            <tr class="bg-white">
                @foreach ($row as $column)
                    <td class="border px-6 py-3 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                        {{ $column }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
