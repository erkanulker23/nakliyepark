@extends('layouts.app')

@section('title', 'İletişim - NakliyePark')
@section('meta_description', 'NakliyePark ile iletişime geçin. Sorularınız ve talepleriniz için bize ulaşın.')

@section('content')
<div class="page-container py-8 sm:py-12">
    <header class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">İletişim</h1>
        <p class="text-zinc-500 dark:text-zinc-400 mt-1">Sorularınız veya talepleriniz için bize ulaşın.</p>
    </header>

    <div class="grid lg:grid-cols-2 gap-8 lg:gap-12">
        {{-- İletişim bilgileri (admin panelinden) --}}
        <div class="space-y-6">
            @if($contact_phone || $contact_email || $contact_address || $contact_whatsapp || $contact_hours)
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">İletişim bilgileri</h2>
                    <ul class="space-y-4">
                        @if($contact_phone ?? null)
                            <li class="flex items-start gap-3">
                                <span class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </span>
                                <div>
                                    <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Telefon</span>
                                    <a href="tel:{{ preg_replace('/\s+/', '', $contact_phone) }}" class="block text-zinc-900 dark:text-white font-medium mt-0.5 hover:text-emerald-600 dark:hover:text-emerald-400">{{ $contact_phone }}</a>
                                </div>
                            </li>
                        @endif
                        @if($contact_email ?? null)
                            <li class="flex items-start gap-3">
                                <span class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </span>
                                <div>
                                    <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">E-posta</span>
                                    <a href="mailto:{{ $contact_email }}" class="block text-zinc-900 dark:text-white font-medium mt-0.5 hover:text-emerald-600 dark:hover:text-emerald-400 break-all">{{ $contact_email }}</a>
                                </div>
                            </li>
                        @endif
                        @if($contact_whatsapp ?? null)
                            <li class="flex items-start gap-3">
                                <span class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.865 9.865 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                </span>
                                <div>
                                    <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">WhatsApp</span>
                                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $contact_whatsapp) }}" target="_blank" rel="noopener noreferrer" class="block text-zinc-900 dark:text-white font-medium mt-0.5 hover:text-emerald-600 dark:hover:text-emerald-400">{{ $contact_whatsapp }}</a>
                                </div>
                            </li>
                        @endif
                        @if($contact_address ?? null)
                            <li class="flex items-start gap-3">
                                <span class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </span>
                                <div>
                                    <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Adres</span>
                                    <p class="text-zinc-900 dark:text-white mt-0.5 whitespace-pre-line">{{ $contact_address }}</p>
                                </div>
                            </li>
                        @endif
                        @if($contact_hours ?? null)
                            <li class="flex items-start gap-3">
                                <span class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                                <div>
                                    <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Çalışma saatleri</span>
                                    <p class="text-zinc-900 dark:text-white mt-0.5">{{ $contact_hours }}</p>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            @else
                <p class="text-sm text-zinc-500 dark:text-zinc-400">İletişim bilgileri henüz eklenmemiş. Aşağıdaki form ile mesaj gönderebilirsiniz.</p>
            @endif
        </div>

        {{-- İletişim formu --}}
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 sm:p-8 shadow-sm">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Mesaj gönderin</h2>
            @if(session('success'))
                <div class="mb-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-200 px-4 py-3 text-sm border border-emerald-200 dark:border-emerald-800">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-800 dark:text-red-200 px-4 py-3 text-sm border border-red-200 dark:border-red-800">{{ session('error') }}</div>
            @endif
            <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Adınız soyadınız *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="input-touch w-full rounded-xl" placeholder="Adınız soyadınız">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">E-posta *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="input-touch w-full rounded-xl" placeholder="ornek@email.com">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="subject" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Konu</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" class="input-touch w-full rounded-xl" placeholder="Konu (isteğe bağlı)">
                    @error('subject')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="message" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Mesajınız *</label>
                    <textarea name="message" id="message" rows="5" required class="input-touch w-full rounded-xl min-h-[120px]" placeholder="Mesajınızı yazın">{{ old('message') }}</textarea>
                    @error('message')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-start gap-2">
                    <input type="checkbox" name="kvkk_consent" id="kvkk_consent" value="1" {{ old('kvkk_consent') ? 'checked' : '' }} required class="mt-1 rounded border-zinc-300 dark:border-zinc-600 text-emerald-600 focus:ring-emerald-500">
                    <label for="kvkk_consent" class="text-sm text-zinc-600 dark:text-zinc-400">Kişisel verilerimin <a href="{{ route('kvkk.aydinlatma') }}" target="_blank" rel="noopener noreferrer" class="underline hover:text-emerald-600">KVKK Aydınlatma Metni</a> kapsamında işlenmesini kabul ediyorum. *</label>
                </div>
                @error('kvkk_consent')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <button type="submit" class="btn-primary w-full sm:w-auto">Gönder</button>
            </form>
        </div>
    </div>
</div>
@endsection
