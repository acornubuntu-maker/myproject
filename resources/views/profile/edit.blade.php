@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-2xl">
  <h1 class="text-2xl font-semibold mb-4">Edit Profile</h1>

  @if(session('success'))
    <div class="mb-4 text-green-700">{{ session('success') }}</div>
  @endif

  <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded-lg shadow">
    @csrf
    @method('PATCH')

    <div>
      <label class="block mb-2 text-sm font-medium text-gray-700">Name</label>
      <input name="name" value="{{ old('name', $user->name) }}" required
             class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#123456]" />
      @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="block mb-2 text-sm font-medium text-gray-700">Profile Photo</label>
      <div class="flex items-center gap-4">
        <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-100 border">
          @if($user->profile_photo_url)
            <img id="photo-preview" src="{{ $user->profile_photo_url }}" alt="photo" class="object-cover w-full h-full" />
          @else
            <div id="photo-preview" class="w-full h-full flex items-center justify-center text-gray-400">No photo</div>
          @endif
        </div>

        <div class="flex-1">
          <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="block w-full text-sm text-gray-600" />
          @error('profile_photo') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
          <p class="text-xs text-gray-500 mt-2">Max 2MB. JPG, PNG or GIF.</p>
        </div>
      </div>
    </div>

    <div>
      <label class="block mb-2 text-sm font-medium text-gray-700">Change Password</label>
      <input name="password" type="password" placeholder="New password (leave blank to keep current)"
             class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#123456]" />
      @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="block mb-2 text-sm font-medium text-gray-700">Confirm Password</label>
      <input name="password_confirmation" type="password" placeholder="Confirm new password"
             class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#123456]" />
    </div>

    <div class="flex justify-end gap-3">
      <a href="{{ url()->previous() }}" class="px-4 py-2 border rounded">Cancel</a>
      <button type="submit" class="px-4 py-2 text-white rounded" style="background-color:#123456;">Save changes</button>
    </div>
  </form>
</div>

<script>
  (function(){
    const input = document.getElementById('profile_photo');
    const preview = document.getElementById('photo-preview');

    input?.addEventListener('change', (e) => {
      const file = e.target.files && e.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = () => {
        if (preview.tagName === 'IMG') {
          preview.src = reader.result;
        } else {
          preview.innerHTML = '';
          const img = document.createElement('img');
          img.src = reader.result;
          img.className = 'object-cover w-full h-full';
          preview.appendChild(img);
        }
      };
      reader.readAsDataURL(file);
    });
  })();
</script>
@endsection
