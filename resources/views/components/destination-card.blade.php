@props(['destination'])

<article class="cursor-pointer bg-white flex flex-col overflow-hidden hover:border-[#0E7C9E] transition-colors duration-150" style="border: 1px solid #D8E1EA; border-radius: 6px;" onclick="window.location.href='{{ route('detail', ['id' => $destination->id]) }}'">
    <div class="relative w-full overflow-hidden bg-gray-100" style="padding-bottom: 75%">
        <img src="{{ $destination->image ?? 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop&auto=format' }}" alt="{{ $destination->name }}" class="absolute inset-0 w-full h-full object-cover" loading="lazy" />
        <span class="absolute top-2 left-2 flex items-center gap-1 text-[11px] font-semibold px-2 py-0.5 rounded-sm" style="background: #EEF3F8; color: #123B6D; border-radius: 4px;">
            <span>{{ $destination->category === 'Wisata' ? '⛰' : '🏪' }}</span> {{ $destination->category }}
        </span>
    </div>
    <div class="p-4 flex flex-col gap-2 flex-1">
        <h3 class="text-[17px] font-semibold leading-snug" style="color: #12181F">{{ $destination->name }}</h3>
        <div class="flex items-center gap-1.5">
            <span class="text-[13px] font-semibold" style="color: #0E7C9E">★ {{ number_format($destination->rating, 1) }}</span>
            <span class="text-[13px]" style="color: #4A5A6B">({{ $destination->review_count }} ulasan)</span>
        </div>
        <div class="flex items-center gap-1.5 mt-auto">
            <svg width="13" height="13" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M8 1.5A4.5 4.5 0 0 0 3.5 6c0 3.5 4.5 8.5 4.5 8.5S12.5 9.5 12.5 6A4.5 4.5 0 0 0 8 1.5ZM8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4Z" fill="#4A5A6B"/></svg>
            <span class="text-[13px]" style="color: #4A5A6B">{{ $destination->location }}</span>
        </div>
    </div>
</article>
