<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>


    {{-- from to add img in profile  --}}
    <div class="py-5 d-flex justify-content-center">
        <div class="card p-4 shadow-lg" style="max-width: 400px; width: 100%;">
            <form action="{{ route('profile.img.post') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    {{-- @dd(auth()->user()->getFirstMediaUrl('profile')) --}}
                    <p class="fw-bold mb-1">{{ auth()->user()->name }}</p>
                    <p class="text-muted mb-3">{{ auth()->user()->email }}</p>
                    {{-- display the img  --}}

                    <img src="{{ $profileImageUrl }}" class="card-img-top" alt="..." />
                    <img src="{{ auth()->user()->getFirstMediaUrl('profile') }}" class="card-img-top" alt="..." />

                    
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Profile Image</label>
                    <input type="file" class="form-control" name="image" id="image" accept="image/*">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-warning">Submit</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
