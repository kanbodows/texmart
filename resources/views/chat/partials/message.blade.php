<div class="message {{ $message->from_user_id === Auth::id() ? 'text-end' : '' }}" data-message-id="{{ $message->id }}">
    <div class="d-inline-block">
        <div class="message-content p-2 rounded {{ $message->from_user_id === Auth::id() ? 'bg-primary text-white' : 'bg-light' }}"
             style="display: inline-block;">
            {{ $message->message }}

            @if($message->file)
                @if(Str::startsWith($message->file_type, 'image/'))
                    <img src="{{ asset('storage/' . $message->file_path) }}" class="img-fluid rounded mt-2" alt="Изображение">
                @else
                    <a href="{{ asset('storage/' . $message->file_path) }}"
                       class="message-attachment"
                       target="_blank"
                       download>
                        <i class="fas fa-{{
                            Str::startsWith($message->file_type, 'video/') ? 'video' :
                            ($message->file_type === 'application/pdf' ? 'file-pdf' :
                            (Str::contains($message->file_type, 'word') ? 'file-word' :
                            (Str::contains($message->file_type, ['sheet', 'excel']) ? 'file-excel' : 'file')))
                        }}"></i>
                        {{ $message->file_name }}
                    </a>
                @endif
            @endif

            <div class="message-time small {{ $message->from_user_id === Auth::id() ? 'text-white-50' : 'text-muted' }}">
                {{ $message->created_at->format('H:i') }}
                @if($message->from_user_id === Auth::id())
                    <i class="fas fa-check{{ $message->is_read ? '-double' : '' }} ms-1"></i>
                @endif
            </div>
        </div>
    </div>
</div>
