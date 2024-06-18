<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <form action="{{route('voucher.create')}}" method="post">
            <input type="text" name="voucher_count" id="voucher_count" value="{{old('voucher_count')}}"/>
            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded float-right">Create</button>
        </form>
    </x-slot>

    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Voucher Code</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($vouchers) > 0)
                    @foreach ($vouchers as $voucher)
                        @if($voucher->user->id == Auth::user()->id)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$voucher->voucher_code}}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('voucher.destroy', ['id' => $voucher->id]) }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @else
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4">
                            No Available Vouchers.
                        </td>
                        <td></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</x-app-layout>