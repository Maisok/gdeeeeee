<div class="w-full md:w-1/4 p-4 chat-list"> <!-- Список чатов занимает всю ширину на маленьких экранах и 1/4 на больших -->
    <h6 class="text-lg font-semibold mb-4">Чаты</h6>
    <ul class="space-y-2">
        <!-- Отображаем чаты -->
        @foreach($userChats as $userChat)
            <li class="p-3 border rounded-md chat-item">
                <a href="{{ route('chat.show', ['chat' => $userChat]) }}" class="flex items-center space-x-2">
                    <!-- Отображаем аватар пользователя -->
                    <img src="{{ $userChat->user1_id == auth()->id() ? $userChat->user2->avatar_url : $userChat->user1->avatar_url }}" alt="Аватар" class="w-10 h-10 rounded-full avatar">
                    <!-- Отображаем имя пользователя жирным черным шрифтом -->
                    <strong class="text-black">{{ $userChat->user1_id == auth()->id() ? $userChat->user2->username : $userChat->user1->username }}</strong>:
                    <!-- Отображаем текст последнего сообщения -->
                    @if($userChat->last_message)
                        {{ Str::limit($userChat->last_message->message, 20, '...') }}
                    @else
                        Нет сообщений
                    @endif
                    <!-- Отображаем счетчик непрочитанных сообщений -->
                    @if($userChat->unread_count > 0)
                        <span class="bg-blue-500 text-white rounded-full px-2 py-1 text-xs">{{ $userChat->unread_count }}</span>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</div>