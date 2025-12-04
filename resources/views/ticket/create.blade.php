<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Ticket') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

            
                <form method="POST" action="{{ route('tickets.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Title</label>
                        <input type="text" name="title" class="w-full rounded border-gray-300" required value="{{ old('title') }}">
                        @error('title') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
                    </div>


                    <div class="mb-4">
                            <label class="block font-semibold mb-1">Category</label>
                            <select name="category_id" class="w-full rounded border-gray-300">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $id => $category)
                                    <option value="{{ $id }}" @if(old('category_id') == $id) selected @endif>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Report Type</label>
                        <select id="report_type_id" name="report_type_id" class="w-full rounded border-gray-300">
                            <option value="">-- Select Report Type --</option>
                        </select>
                            @error('report_type_id') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
                    </div>


                    {{-- <div class="mb-4">
                            <label class="block font-semibold mb-1">Report Type</label>
                            <select name="report_types_id" class="w-full rounded border-gray-300">
                                <option value="">-- Select Report Type --</option>
                                @foreach($reportTypes as $id => $reportTypes)
                                    <option value="{{ $id }}" @if(old('report_types_id') == $id) selected @endif>
                                        {{ $reportTypes }}
                                    </option>
                                @endforeach
                            </select>
                            @error('report_types_id') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
                    </div> --}}

                   {{-- <div class="mb-4">
                            <label class="block font-semibold mb-1">Report Type</label>
                            <select name="report_type_id" id="report_type_dropdown" class="w-full rounded border-gray-300">
                                <option value="">-- Select Report Type --</option>
                            </select>
                    </div> --}}


                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Description</label>
                        <textarea name="description" class="w-full rounded border-gray-300" rows="4">{{ old('description') }}</textarea>
                        @error('description') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
                    </div>




                    <div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Create Ticket</button>
                        <a href="{{ route('tickets.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categorySelect = document.querySelector('select[name="category_id"]');
            const reportTypeSelect = document.querySelector('#report_type_id');

            categorySelect.addEventListener('change', function () {
                let categoryId = this.value;

                reportTypeSelect.innerHTML = '<option value="">-- Loading... --</option>';

                if (categoryId) {
                    fetch(`/get-report-types/${categoryId}`)
                        .then(response => response.json())
                        .then(data => {
                            reportTypeSelect.innerHTML = '<option value="">-- Select Report Type --</option>';
                            data.forEach(type => {
                                reportTypeSelect.innerHTML += `<option value="${type.id}">${type.name}</option>`;
                            });
                        });
                } else {
                    reportTypeSelect.innerHTML = '<option value="">-- Select Report Type --</option>';
                }
            });
        });
    </script>


    {{--<script>
        document.getElementById('category_dropdown').addEventListener('change', function () {
            let categoryId = this.value;
            fetch('/get-report-types/' + categoryId)
                .then(response => response.json())
                .then(data => {
                    let reportDropdown = document.getElementById('report_type_dropdown');
                    reportDropdown.innerHTML = '<option value="">-- Select Report Type --</option>';
                    for (let id in data) {
                        reportDropdown.innerHTML += `<option value="${id}">${data[id]}</option>`;
                    }
                });
        });
    </script>--}}
    
</x-app-layout>