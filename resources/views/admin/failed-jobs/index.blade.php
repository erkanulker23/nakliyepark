@extends('layouts.admin')

@section('title', 'Başarısız Kuyruk İşleri')
@section('page_heading', 'Başarısız Kuyruk İşleri')

@section('content')
@if(session('success'))
    <div class="mb-4 px-4 py-2 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">{{ session('success') }}</div>
@endif
@if($totalCount > 0)
    <div class="mb-4 flex flex-wrap items-center gap-2">
        <form method="POST" action="{{ route('admin.failed-jobs.retry-all') }}" class="inline">
            @csrf
            <button type="submit" class="admin-btn-secondary text-sm">Tümünü yeniden dene</button>
        </form>
        <form method="POST" action="{{ route('admin.failed-jobs.flush') }}" class="inline" onsubmit="return confirm('Tüm başarısız işler kalıcı olarak silinecek. Emin misiniz?');">
            @csrf
            <button type="submit" class="text-red-600 hover:underline text-sm font-medium">Tümünü sil</button>
        </form>
        <span class="text-slate-600 text-sm">Toplam <strong>{{ $totalCount }}</strong> başarısız iş</span>
    </div>
@endif
<div class="admin-card overflow-hidden">
    <table class="w-full admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kuyruk / Bağlantı</th>
                <th>Başarısız tarihi</th>
                <th class="text-right">İşlem</th>
            </tr>
        </thead>
        <tbody>
            @forelse($failedJobs as $job)
                <tr>
                    <td class="font-mono text-sm">{{ $job->id }}</td>
                    <td class="text-slate-600 text-sm">
                        <span class="font-medium">{{ $job->queue ?? 'default' }}</span>
                        <span class="text-slate-400"> · {{ $job->connection }}</span>
                    </td>
                    <td class="text-slate-600 text-sm whitespace-nowrap">{{ \Carbon\Carbon::parse($job->failed_at)->format('d.m.Y H:i') }}</td>
                    <td class="text-right">
                        <form method="POST" action="{{ route('admin.failed-jobs.retry', $job->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-emerald-600 hover:underline text-sm font-medium">Yeniden dene</button>
                        </form>
                        <form method="POST" action="{{ route('admin.failed-jobs.destroy', $job->id) }}" class="inline ml-2" onsubmit="return confirm('Bu kaydı silmek istediğinize emin misiniz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm font-medium">Sil</button>
                        </form>
                    </td>
                </tr>
                @if(!empty($job->exception))
                    <tr class="bg-slate-50 dark:bg-slate-800/50">
                        <td colspan="4" class="px-4 py-2">
                            <details class="text-xs font-mono text-slate-600 dark:text-slate-400 max-h-32 overflow-auto">
                                <summary class="cursor-pointer hover:underline">Hata detayı</summary>
                                <pre class="mt-1 whitespace-pre-wrap break-all">{{ Str::limit($job->exception, 1500) }}</pre>
                            </details>
                        </td>
                    </tr>
                @endif
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">Başarısız iş yok.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($failedJobs->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">{{ $failedJobs->links() }}</div>
    @endif
</div>
@endsection
