@props(['user', 'size' => 'w-22 h-22'])

@if ($user->image)
    <img class="{{ $size }} rounded-full" src="{{ $user->imageUrl() }}" alt="{{ $user->name }}">
@else
    <img class="{{ $size }} rounded-full" src="/img/profile.jpg" alt="">    
@endif