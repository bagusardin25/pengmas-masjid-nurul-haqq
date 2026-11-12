@props(['deleteForm' => null, 'label' => null, 'submitLabel' => 'Simpan'])

<div {{ $attributes->class(['dash-item-actions']) }}>
    <button type="submit" @class(['dash-button', 'dash-button-primary' => $deleteForm, 'dash-button-secondary' => ! $deleteForm])>{{ $submitLabel }}</button>
    @if ($deleteForm)
        <button type="submit" form="{{ $deleteForm }}" class="dash-button dash-button-danger" aria-label="Hapus {{ $label }}">Hapus</button>
    @endif
</div>
