{{--
    Form hapus tanpa isi. Tombolnya ada di dalam form ubah (lihat item-actions)
    dan terhubung lewat atribut form="{{ $id }}", karena form tidak boleh bersarang.
--}}
@props(['id', 'action', 'label'])

<form id="{{ $id }}" method="POST" action="{{ $action }}" hidden
    onsubmit="return confirm(@js('Hapus '.$label.'? Data yang dihapus tidak bisa dikembalikan.'))">
    @csrf
    @method('DELETE')
</form>
