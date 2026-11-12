{{--
    Isian unggah foto dengan pratinjau. Harus berada di dalam elemen dengan
    x-data="photoUpload()" (lihat resources/js/app.js) yang juga membungkus tombol kirim,
    agar tombol bisa dinonaktifkan selama foto diproses.
--}}
@props([
    'bag',
    'name' => 'image',
    'label' => 'Foto',
    'required' => false,
    'hint' => 'JPG, PNG, atau WebP. Foto besar otomatis diperkecil sebelum diunggah.',
])

@php
    $error = $errors->getBag($bag)->first($name);
    $id = $bag.'-'.$name;
    $describedBy = trim($id.'-hint '.($error ? $id.'-error' : ''));
@endphp

<div {{ $attributes->class(['dash-field', 'dash-photo-field', 'has-error' => $error]) }}>
    <label for="{{ $id }}">
        {{ $label }}
        @unless ($required)
            <span class="dash-optional">opsional</span>
        @endunless
    </label>
    <div class="dash-photo-picker">
        <div class="dash-photo-preview" aria-hidden="true">
            <template x-if="preview">
                <img :src="preview" alt="">
            </template>
            <svg x-show="!preview" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                <path d="m21 15-5-5L5 21"></path>
            </svg>
        </div>
        <div class="dash-photo-input">
            <input id="{{ $id }}" name="{{ $name }}" type="file" accept="image/jpeg,image/png,image/webp"
                @change="pick($event)" @required($required)
                @if ($error) aria-invalid="true" @endif
                aria-describedby="{{ $describedBy }}">
            <p class="dash-hint" id="{{ $id }}-hint">
                <span x-show="busy">Memproses foto…</span>
                <span x-show="!busy && info" x-text="info"></span>
                <span x-show="!busy && !info">{{ $hint }}</span>
            </p>
            @if ($error)
                <p class="dash-error" id="{{ $id }}-error">{{ $error }}</p>
            @endif
        </div>
    </div>
</div>
