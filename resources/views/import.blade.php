<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import') }}
        </h2>
        <div class="container mt-4">
            <div>
                <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="messages">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                    <div class="fields">
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="import_csv" name="import_csv" accept=".csv">
                            <label class="input-group-text" for="import_csv">Upload</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Import CSV</button>
                </form>
            </div>
        </div>
    </x-slot>
</x-app-layout>

