@props([
    'bag',
    'checked' => true,
    'name' => 'is_active',
    'label' => 'Tampil di situs',
    'onText' => 'Tampil',
    'offText' => 'Disembunyikan',
])

@php
    $errorBag = $errors->getBag($bag);
    $isChecked = $errorBag->any() ? (bool) old($name) : (bool) $checked;
    $id = $bag.'-'.$name;
@endphp

<div class="dash-field dash-check" x-data="{ on: @js($isChecked) }">
    <span class="dash-check-label" id="{{ $id }}-label">{{ $label }}</span>
    <label class="dash-switch" for="{{ $id }}">
        <input type="hidden" name="{{ $name }}" value="0">
        <input id="{{ $id }}" type="checkbox" name="{{ $name }}" value="1" x-model="on" @checked($isChecked)
            aria-labelledby="{{ $id }}-label">
        <span class="dash-switch-text" x-text="on ? @js($onText) : @js($offText)">{{ $isChecked ? $onText : $offText }}</span>
    </label>
</div>
