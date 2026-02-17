@extends('layouts.nakliyeci')

@section('title', 'Deftere yaz')
@section('page_heading', 'Deftere yaz')
@section('page_subtitle', 'Yük veya boş dönüş ilanı ekleyin')

@section('content')
<div class="max-w-xl">
    <div class="admin-card p-6">
        <p class="text-sm text-slate-500 mb-6">Firmanızın boş dönüş veya yük ilanını paylaşın. Günde 200'den fazla ilan ekleniyor.</p>
        <form method="POST" action="{{ route('nakliyeci.ledger.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="admin-form-group">
                    <label class="admin-label">Nereden (il) *</label>
                    <select name="from_city" id="ledger-from_city" required class="admin-input" data-old="{{ old('from_city') }}">
                        <option value="">İl seçin</option>
                    </select>
                    @error('from_city')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Nereye (il) *</label>
                    <select name="to_city" id="ledger-to_city" required class="admin-input" data-old="{{ old('to_city') }}">
                        <option value="">İl seçin</option>
                    </select>
                    @error('to_city')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Açıklama</label>
                <textarea name="description" rows="6" class="admin-input min-h-[140px] resize-y" placeholder="Yük veya boş dönüşünüzü kısaca anlatın: nereden–nereye, tarih, hacim, yük tipi, araç tipi veya iletişim bilgisi gibi detayları yazabilirsiniz." maxlength="2000">{{ old('description') }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="admin-btn-primary">Deftere yaz</button>
                <a href="{{ route('nakliyeci.ledger') }}" class="admin-btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
(function() {
    var fromSelect = document.getElementById('ledger-from_city');
    var toSelect = document.getElementById('ledger-to_city');
    if (!fromSelect || !toSelect) return;
    var apiUrl = '{{ route("api.turkey.provinces") }}';
    fetch(apiUrl).then(function(r) { return r.json(); }).then(function(res) {
        var data = res.data || [];
        function fill(s) {
            if (!s) return;
            while (s.options.length > 1) s.removeChild(s.lastChild);
            data.forEach(function(p) {
                var o = document.createElement('option');
                o.value = p.name;
                o.textContent = p.name;
                s.appendChild(o);
            });
            var oldVal = s.getAttribute('data-old');
            if (oldVal) s.value = oldVal;
        }
        fill(fromSelect);
        fill(toSelect);
    }).catch(function() {
        [fromSelect, toSelect].forEach(function(s) {
            if (s && s.options.length === 1) {
                var o = document.createElement('option');
                o.value = '';
                o.textContent = 'İller yüklenemedi';
                s.appendChild(o);
            }
        });
    });
})();
</script>
@endpush
@endsection
