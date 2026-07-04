<div class="space-y-6">
    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <div class="grid gap-3 text-sm text-gray-600 sm:grid-cols-2">
            <div>
                <div class="font-medium text-gray-900">Tên</div>
                <div>{{ $record->original_name }}</div>
            </div>
            <div>
                <div class="font-medium text-gray-900">Loại file</div>
                <div>{{ $record->type_label }}</div>
            </div>
            <div>
                <div class="font-medium text-gray-900">MIME</div>
                <div>{{ $record->mime_type }}</div>
            </div>
            <div>
                <div class="font-medium text-gray-900">Dung lượng</div>
                <div>{{ app(\App\Services\MediaService::class)->formatStorageSize((int) $record->size) }}</div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-gray-50 p-4">
        @if ($record->is_image)
            <img src="{{ app(\App\Services\MediaService::class)->previewImageSource($record) }}" alt="{{ $record->alt ?? $record->original_name }}" class="mx-auto max-h-[70vh] rounded-lg object-contain" />
        @elseif ($record->is_pdf)
            <object data="{{ app(\App\Services\MediaService::class)->previewPdfSource($record) }}" type="application/pdf" class="h-[88vh] w-full rounded-lg border border-gray-200 bg-white">
                <div class="p-6 text-sm text-gray-600">
                    Trình duyệt của bạn không hiển thị được PDF trực tiếp.
                    <a href="{{ $record->url }}" target="_blank" rel="noopener" class="ml-1 font-medium text-blue-600 underline">
                        Mở file PDF
                    </a>
                </div>
            </object>
        @elseif ($record->is_video)
            <video controls class="mx-auto w-full rounded-lg bg-black" style="max-height: 70vh;">
                <source src="{{ $record->url }}" type="{{ $record->mime_type }}">
                Trình duyệt của bạn không hỗ trợ video.
            </video>
        @else
            <div class="rounded-lg border border-dashed border-gray-300 bg-white p-6 text-sm text-gray-600">
                Không hỗ trợ preview trực tiếp cho loại tệp này.
                <a href="{{ $record->url }}" target="_blank" rel="noopener" class="ml-1 font-medium text-blue-600 underline">
                    Mở tệp
                </a>
            </div>
        @endif
    </div>

    @if ($record->caption)
        <div class="rounded-xl border border-gray-200 bg-white p-4 text-sm text-gray-700">
            <div class="font-medium text-gray-900">Mô tả</div>
            <div class="mt-2 whitespace-pre-line">{{ $record->caption }}</div>
        </div>
    @endif
</div>