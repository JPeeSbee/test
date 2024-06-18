@props(['errors'])

@if ($errors->any())
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-red-200 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @foreach ($errors->all() as $error)
                        <span>&bull;{{ $error }}</span><br/>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

@if(session('success'))
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-green-300 dark:bg-green-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        </div>
    </div>
    {{ session()->forget('success'); }}
@endif

@if(session('fail'))
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-red-300 dark:bg-red-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <span>{{ session('fail') }}</span>
                </div>
            </div>
        </div>
    </div>
    {{ session()->forget('fail'); }}
@endif
