{{-- Isian unggah dokumen (mis. PDF laporan) dengan petunjuk dan pesan error. --}}
@props([
    'bag',
    'name' => 'file',
    'label' => 'Berkas PDF',
    'accept' => 'application/pdf,.pdf',
    'required' => false,
    'hint' => 'Format PDF, maksimal 2 MB.',
])

@php
    $error = $errors->getBag($bag)->first($name);
    $id = $bag.'-'.$name;
    $describedBy = trim(($hint ? $id.'-hint ' : '').($error ? $id.'-error' : ''));
@endphp

<div {{ $attributes->class(['dash-field', 'dash-file-field', 'has-error' => $error]) }}>
    <label for="{{ $id }}">
        {{ $label }}
        @unless ($required)
            <span class="dash-optional">opsional</span>
        @endunless
    </label>
    <input id="{{ $id }}" name="{{ $name }}" type="file" accept="{{ $accept }}"
        @required($required)
        @if ($error) aria-invalid="true" @endif
        @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif>
    @if ($hint)
        <p class="dash-hint" id="{{ $id }}-hint">{{ $hint }}</p>
    @endif
    @if ($error)
        <p class="dash-error" id="{{ $id }}-error">{{ $error }}</p>
    @endif
</div>
