@props([
    'headers' => [],
    'data' => null
])

<table class="w-full text-sm text-left">
    @if(count($headers))
        <thead class="border-b border-gray-200 text-gray-700">
        <tr>
            @foreach ($headers as $header)
                <th class="px-4 py-2.5 font-medium whitespace-nowrap">
                    {{ $header }}
                </th>
            @endforeach
        </tr>
        </thead>
    @endif

    <tbody class="divide-y divide-gray-200">
    {{ $slot }}
    </tbody>
</table>
