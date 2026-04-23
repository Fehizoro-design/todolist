<div>
    <a role="button" {{ $attributes->merge([
    'class' => 'inline-flex items-center px-2 py-1 rounded-md font-semibold text-xs uppercase tracking-widest transition ease-in-out duration-150 focus:outline-none focus:ring ring-gray-300 disabled:opacity-25 ' .
        ($attributes->get('color') ?? 'bg-gray-800 border-transparent text-white hover:bg-gray-700 active:bg-gray-900 focus:border-gray-900') .
        ($attributes->get('delete-cursor') ? ' cursor-not-allowed' : '')
]) }}>
        {{ $slot }}
    </a>
    <!-- Live as if you were to die tomorrow. Learn as if you were to live forever. - Mahatma Gandhi -->
</div>