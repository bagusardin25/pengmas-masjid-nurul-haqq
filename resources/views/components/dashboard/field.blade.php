{{--
    Field form dashboard dengan label, petunjuk, dan pesan error.
    Isian lama (old) hanya dipakai bila error bag milik form ini berisi error,
    karena satu halaman bisa memuat banyak form dengan nama field yang sama.
--}}
@props([
    'label',
    'name',
    'bag',
    'value' => null,
    'type' => 'text',
    'options' => [],
    'placeholder' => null,
    'hint' => null,
    'rows' => 3,
    'required' => false,
    'showOptional' => true,
])

@php
    $errorBag = $errors->getBag($bag);
    $current = $errorBag->any() ? old($name) : $value;
    $error = $errorBag->first($name);
    $id = $bag.'-'.$name;
    $describedBy = trim(($hint ? $id.'-hint ' : '').($error ? $id.'-error' : ''));
@endphp

<div {{ $attributes->class(['dash-field', 'has-error' => $error]) }}>
    <label for="{{ $id }}">
        {{ $label }}
        @unless ($required || ! $showOptional)
            <span class="dash-optional">opsional</span>
        @endunless
    </label>

    @if ($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}"
            @if ($placeholder) placeholder="{{ $placeholder }}" @endif
            @required($required)
            @if ($error) aria-invalid="true" @endif
            @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif>{{ $current }}</textarea>
    @elseif ($type === 'select')
        <select id="{{ $id }}" name="{{ $name }}"
            @required($required)
            @if ($error) aria-invalid="true" @endif
            @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif>
            @foreach ($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" @selected((string) $current === (string) $optionValue)>{{ $optionLabel }}</option>
            @endforeach
        </select>
    @else
        <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" value="{{ $current }}"
            @if ($placeholder) placeholder="{{ $placeholder }}" @endif
            @if ($type === 'number') min="0" max="9999" inputmode="numeric" @endif
            @required($required)
            @if ($error) aria-invalid="true" @endif
            @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif>
    @endif

    @if ($hint)
        <p class="dash-hint" id="{{ $id }}-hint">{{ $hint }}</p>
    @endif
    @if ($error)
        <p class="dash-error" id="{{ $id }}-error">{{ $error }}</p>
    @endif
</div>
