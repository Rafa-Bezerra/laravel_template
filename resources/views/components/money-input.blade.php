@props(['value' => '', 'name', 'id' => $name, 'disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge([
        'id' => $id,
        'name' => $name,
        'type' => 'text',
        'value' => old($name, $value),
        'class' => 'monetario border-gray-300 dark:border-gray-700 bg-gray-200 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm'
    ]) }}
>
