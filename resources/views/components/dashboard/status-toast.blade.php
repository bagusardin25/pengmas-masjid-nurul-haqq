{{-- Pemberitahuan singkat setelah data disimpan. --}}
@if (session('status'))
    <div class="dash-alert dash-toast" role="status"
        x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M20 6 9 17l-5-5"></path>
        </svg>
        {{ session('status') }}
        <button type="button" class="dash-toast-close" @click="show = false" aria-label="Tutup pemberitahuan">&times;</button>
    </div>
@endif
