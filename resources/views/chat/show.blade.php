@extends('frontend.layouts.app')

@push('after-styles')
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Emoji Mart CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@emoji-mart/data" />
    <style>
        .message-content {
            border-radius: 1rem;
            /* max-width: 80%; */
            display: inline-block;
            word-break: break-word;
        }
        .message.text-end .message-content {
            border-bottom-right-radius: 0.25rem;
        }
        .message:not(.text-end) .message-content {
            border-bottom-left-radius: 0.25rem;
        }
        .messages {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        #messages-container {
            background-color: #f8f9fa;
        }
        .card-footer {
            background-color: white;
            border-top: 1px solid rgba(0,0,0,.125);
        }
        .btn-link {
            text-decoration: none;
        }
        .avatar img, .avatar div {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .file-preview {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            border-radius: 0.5rem;
            margin-top: 0.5rem;
        }
        .file-input {
            display: none;
        }
        .file-button {
            cursor: pointer;
            padding: 0.375rem 0.75rem;
            border-right: 1px solid #dee2e6;
        }
        .file-name {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
        .message-attachment {
            display: block;
            margin-top: 0.5rem;
            padding: 0.5rem;
            background: rgba(0,0,0,0.05);
            border-radius: 0.25rem;
            text-decoration: none;
            color: inherit;
        }
        .message-attachment i {
            margin-right: 0.5rem;
        }
        .preview-container {
            position: relative;
            display: inline-block;
        }
        .remove-preview {
            position: absolute;
            top: -0.5rem;
            right: -0.5rem;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 1.5rem;
            height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.75rem;
        }
        .emoji-button {
            cursor: pointer;
            padding: 0.375rem 0.75rem;
            border-right: 1px solid #dee2e6;
            color: #6c757d;
            transition: color 0.2s;
        }

        .emoji-button:hover {
            color: #0d6efd;
        }

        .emoji-picker {
            position: absolute;
            bottom: 100%;
            left: 0;
            margin-bottom: 10px;
            z-index: 1000;
            display: none;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        /* Стили для мобильных устройств */
        @media (max-width: 768px) {
            .emoji-picker {
                position: fixed;
                left: 50%;
                bottom: 80px;
                transform: translateX(-50%);
                width: 95%;
                max-width: 350px;
            }
        }

        /* Убираем outline у кнопки эмодзи при фокусе */
        .emoji-button:focus {
            outline: none;
        }

        em-emoji-picker {
            height: 350px;
            min-width: 350px;
        }
    </style>
@endpush

@section('content')
<audio id="message-sent-sound" preload="auto">
    <source src="{{ asset('audio/message-sent.mp3') }}" type="audio/mpeg">
</audio>
<audio id="message-received-sound" preload="auto">
    <source src="{{ asset('audio/message-received.mp3') }}" type="audio/mpeg">
</audio>
<br>
<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('chat.index') }}" class="btn btn-link text-muted p-0 me-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div class="flex-shrink-0">
                            @if($otherUser->avatar)
                                <img src="{{ $otherUser->avatar }}" alt="{{ $otherUser->name }}"
                                     class="rounded-circle" width="40" height="40">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0">{{ $otherUser->name }}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="height: 400px; overflow-y: auto;" id="messages-container">
                    <div class="messages">
                        @foreach($messages as $message)
                            @include('chat.partials.message', ['message' => $message])
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    <form id="message-form" action="{{ route('chat.store', $otherUser->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div id="preview-area" class="mb-2" style="display: none;">
                            <div class="preview-container">
                                <img id="file-preview" class="file-preview">
                                <div class="remove-preview" onclick="removePreview()">
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>
                            <div class="file-name" id="file-name"></div>
                        </div>
                        <div class="input-group">
                            <label class="file-button" for="file-input">
                                <i class="fas fa-paperclip"></i>
                            </label>
                            <div class="emoji-button" id="emoji-button">
                                <i class="far fa-smile"></i>
                            </div>
                            <div class="emoji-picker" id="emoji-picker">
                                <em-emoji-picker></em-emoji-picker>
                            </div>
                            <input type="file" id="file-input" name="file" class="file-input" accept="image/*,video/*,application/pdf,.doc,.docx,.xls,.xlsx">
                            <input type="text" name="message" class="form-control" placeholder="Введите сообщение..." required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<br>

@endsection

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/emoji-mart@latest/dist/browser.js"></script>

    <script>
    $(document).ready(async function() {
        const $messagesContainer = $('#messages-container');
        const $messageForm = $('#message-form');
        const $fileInput = $('#file-input');
        const $previewArea = $('#preview-area');
        const $filePreview = $('#file-preview');
        const $fileName = $('#file-name');
        const $messageSentSound = $('#message-sent-sound')[0];
        const $messageReceivedSound = $('#message-received-sound')[0];
        const $emojiButton = $('#emoji-button');
        const $emojiPicker = $('#emoji-picker');
        const $messageInput = $messageForm.find('input[name="message"]');

        // Инициализация Emoji Picker
        const pickerOptions = {
            onEmojiSelect: (emoji) => {
                const cursorPos = $messageInput[0].selectionStart;
                const textBefore = $messageInput.val().substring(0, cursorPos);
                const textAfter = $messageInput.val().substring(cursorPos);

                $messageInput.val(textBefore + emoji.native + textAfter);
                $messageInput.focus();
                $messageInput[0].setSelectionRange(cursorPos + emoji.native.length, cursorPos + emoji.native.length);

                $emojiPicker.hide();
            },
            locale: 'ru',
            theme: 'light',
            autoFocus: true,
            categories: [
                'frequent',
                'people',
                'nature',
                'foods',
                'activity',
                'places',
                'objects',
                'symbols',
                'flags'
            ]
        };

        // Создаем пикер
        const picker = new EmojiMart.Picker(pickerOptions);
        $emojiPicker.append(picker);

        // Обработчик клика по кнопке эмодзи
        $emojiButton.on('click', function(e) {
            e.stopPropagation();
            $emojiPicker.toggle();
        });

        // Закрываем пикер при клике вне его
        $(document).on('click', function(e) {
            if (!$emojiPicker.is(e.target) && !$emojiButton.is(e.target) && $emojiPicker.has(e.target).length === 0) {
                $emojiPicker.hide();
            }
        });

        // Инициализация Echo
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ config('broadcasting.connections.pusher.key') }}',
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
            forceTLS: true,
            auth: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        });

        // Подписка на канал чата
        Echo.private('chat.{{ Auth::id() }}')
            .listen('NewChatMessage', (e) => {
                if (e.message.from_user_id == {{ $otherUser->id }}) {
                    $('.messages').append(e.messageHtml);
                    $messagesContainer.scrollTop($messagesContainer[0].scrollHeight);
                    playSound($messageReceivedSound);

                    if (document.hidden && "Notification" in window && Notification.permission === "granted") {
                        new Notification(e.sender.name, {
                            body: e.message.message,
                            icon: e.sender.avatar || '/path/to/default-avatar.png'
                        });
                    }

                    $.ajax({
                        url: `/chat/${e.message.from_user_id}/read`,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                }
            });

        // Обработка файлов
        $fileInput.on('change', function() {
            const file = this.files[0];
            if (file) {
                $fileName.text(file.name);
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $filePreview.attr('src', e.target.result);
                        $previewArea.show();
                    }
                    reader.readAsDataURL(file);
                } else {
                    $filePreview.attr('src', getFileIcon(file.type));
                    $previewArea.show();
                }
            }
        });

        window.removePreview = function() {
            $fileInput.val('');
            $previewArea.hide();
            $filePreview.attr('src', '');
            $fileName.text('');
        }

        function getFileIcon(fileType) {
            if (fileType.startsWith('video/')) return 'https://cdn-icons-png.flaticon.com/512/337/337944.png';
            if (fileType === 'application/pdf') return 'https://cdn-icons-png.flaticon.com/512/337/337946.png';
            if (fileType.includes('word')) return 'https://cdn-icons-png.flaticon.com/512/337/337932.png';
            if (fileType.includes('sheet') || fileType.includes('excel')) return 'https://cdn-icons-png.flaticon.com/512/337/337958.png';
            return 'https://cdn-icons-png.flaticon.com/512/4725/4725970.png';
        }

        // Отправка сообщения
        $messageForm.on('submit', function(e) {
            e.preventDefault();
            const $input = $(this).find('input[name="message"]');
            const $submitButton = $(this).find('button[type="submit"]');

            if (!$input.val().trim() && !$fileInput[0].files.length) return;

            $submitButton.prop('disabled', true);

            const formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(data) {
                    if (data.success) {
                        $('.messages').append(data.message);
                        $messageForm[0].reset();
                        removePreview();
                        $messagesContainer.scrollTop($messagesContainer[0].scrollHeight);
                        playSound($messageSentSound);
                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                    alert('Ошибка при отправке сообщения');
                },
                complete: function() {
                    $submitButton.prop('disabled', false);
                    $input.focus();
                }
            });
        });

        function playSound(audio) {
            audio.pause();
            audio.currentTime = 0;
            if (!document.hidden) {
                audio.play().catch(error => {
                    console.log("Audio playback error:", error);
                });
            }
        }

        // Прокрутка к последнему сообщению
        $messagesContainer.scrollTop($messagesContainer[0].scrollHeight);

        // Enter для отправки
        $messageInput.on('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                $messageForm.submit();
            }
        });

        // Запрос разрешения на уведомления
        if ("Notification" in window) {
            Notification.requestPermission();
        }
    });
    </script>
@endpush
