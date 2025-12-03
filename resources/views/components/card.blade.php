<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-sm font-medium text-gray-500">{{ $title }}</h3>
    <p class="mt-2 text-3xl font-bold">{{ $value }}</p>
    {!! $slot ?? '' !!}
</div>