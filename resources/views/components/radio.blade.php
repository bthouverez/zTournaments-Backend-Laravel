<div>
    <input type="radio" id="{{ $value }}" name="{{ $name }}" value="{{ $value }}" class="hidden peer">
    <label for="{{ $value }}"
        {{ $attributes->merge(['class' => 'p-2 inline-flex items-center justify-between w-full text-gray-500 bg-white border border-gray-500
        cursor-pointer peer-checked:border-blue-600 peer-checked:text-white  peer-checked:bg-blue-500
        hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700']) }}>
        <div class="w-full truncate">{{ $slot }}</div>
    </label>
</div>
