@php
    $bag = 'agenda-'.$agenda->id;
    $label = 'agenda “'.$agenda->title.'” tanggal '.$agenda->date->locale('id')->translatedFormat('j F Y');
@endphp

<div @class(['dash-item', 'is-hidden' => $agenda->status === \App\Enums\PublicationStatus::Draft])>
    <form method="POST" action="{{ route('dashboard.jadwal.agendas.update', $agenda) }}" class="dash-item-form dash-grid-six">
        @csrf
        @method('PUT')
        <x-dashboard.field :bag="$bag" name="title" label="Nama agenda" :value="$agenda->title" required class="dash-span-2" />
        <x-dashboard.field :bag="$bag" name="date" label="Tanggal" type="date" :value="$agenda->date->toDateString()" required />
        <x-dashboard.field :bag="$bag" name="time" label="Waktu" :value="$agenda->time" />
        <x-dashboard.field :bag="$bag" name="speaker" label="Khotib / pengisi" :value="$agenda->speaker" class="dash-span-2" />
        <x-dashboard.field :bag="$bag" name="location" label="Tempat" :value="$agenda->location" class="dash-span-2" />
        <x-dashboard.field :bag="$bag" name="status" label="Status" type="select" :options="$statusOptions" :value="$agenda->status->value" required />
        <x-dashboard.field :bag="$bag" name="description" label="Keterangan" type="textarea" rows="1" :value="$agenda->description" class="dash-span-3" />
        <x-dashboard.item-actions :delete-form="'hapus-'.$bag" :label="$label" class="dash-actions-row" />
    </form>
    <x-dashboard.delete-form :id="'hapus-'.$bag" :action="route('dashboard.jadwal.agendas.destroy', $agenda)" :label="$label" />
</div>
