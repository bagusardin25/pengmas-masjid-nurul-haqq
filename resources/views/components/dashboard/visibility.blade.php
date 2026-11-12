@props(['bag', 'checked' => true])

@php
    $errorBag = $errors->getBag($bag);
    $isChecked = $errorBag->any() ? (bool) old('is_active') : (bool) $checked;
    $id = $bag.'-is_active';
@endphp

<div class="dash-field dash-check" x-data="{ on: @js($isChecked) }">
    <span class="dash-check-label" id="{{ $id }}-label">Tampil di situs</span>
    <label class="dash-switch" for="{{ $id }}">
        <input type="hidden" name="is_active" value="0">
        <input id="{{ $id }}" type="checkbox" name="is_active" value="1" x-model="on" @checked($isChecked)
            aria-labelledby="{{ $id }}-label">
        <span class="dash-switch-text" x-text="on ? 'Tampil' : 'Disembunyikan'">{{ $isChecked ? 'Tampil' : 'Disembunyikan' }}</span>
    </label>
</div>
