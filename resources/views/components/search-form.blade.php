<div class="ad-list bg-white shadow-lg p-4 rounded-lg">
    <form id="main-form" action="{{ route('adverts.search') }}" method="GET" class="flex flex-wrap gap-4 items-center" data-brands-url="{{ route('get.brands') }}">
        <input type="hidden" name="city" value="{{ request()->get('city') }}">

        <!-- Search Query Input -->
        <input
            type="text"
            name="search_query"
            placeholder="Введите название или номер детали"
            value="{{ request()->get('search_query') }}"
            class="w-full md:w-auto flex-grow px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-500"
        />

        <!-- Brand Input with Autocomplete -->
        <input
            type="text"
            id="brand-input"
            name="brand_input"
            placeholder="Введите марку"
            class="w-full md:w-auto flex-grow px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-500"
        />
        <input type="hidden" id="brand" name="brand" value="{{ request()->get('brand') }}">

        <!-- Model Input with Autocomplete -->
        <input
            type="text"
            id="model-input"
            name="model_input"
            placeholder="Введите модель"
            class="w-full md:w-auto flex-grow px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-500"
        />
        <input type="hidden" id="model" name="model" value="{{ request()->get('model') }}">

        <!-- Year Select -->
        <select
            id="year"
            name="year"
            class="w-full md:w-auto flex-grow px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-500"
        >
            <option value="">Выберите год выпуска</option>
            @for($i = 2000; $i <= date('Y'); $i++)
            <option value="{{ $i }}" {{ request()->get('year') == $i ? 'selected' : '' }}>
                {{ $i }}
            </option>
            @endfor
        </select>

        <!-- Show Button -->
        <button
            type="button"
            id="show-button"
            class="w-full md:w-auto px-4 py-2 bg-blue-500 text-white font-semibold rounded focus:outline-none focus:ring focus:ring-blue-500"
        >
            Показать
        </button>
    </form>

    <!-- Modifications Container -->
    <div id="modifications-container" class="modification mt-4">
        <label class="font-medium">Модификации:</label>
        <div id="modifications" class="flex flex-wrap gap-2"></div>
    </div>
</div>

<!-- Import jQuery and Other JavaScript Libraries -->
<script src="{{ asset('js/search-form.js') }}" defer></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script>
    $(document).ready(function() {
        // Настройка автодополнения для марки
        $('#brand-input').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: '{{ route('get.brands') }}',
                    type: 'GET',
                    data: { term: request.term },
                    success: function(data) {
                        response(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                    }
                });
            },
            select: function(event, ui) {
                $('#brand').val(ui.item.value); // Устанавливаем значение в скрытое поле
                updateModels(ui.item.value);
            }
        });

        // Настройка автодополнения для модели
        $('#model-input').autocomplete({
            source: function(request, response) {
                var brand = $('#brand').val();
                var modelTerm = request.term;
                $.ajax({
                    url: '{{ route('get.models') }}',
                    type: 'GET',
                    data: { term: modelTerm, brand: brand },
                    success: function(data) {
                        response(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                    }
                });
            },
            select: function(event, ui) {
                $('#model').val(ui.item.value); // Устанавливаем значение в скрытое поле
            }
        });

        // Обработчик для кнопки "Показать"
        $('#show-button').on('click', function() {
            var formData = $('#main-form').serialize();
            window.location.href = '{{ route('adverts.search') }}?' + formData;
        });

        function updateModels(brand) {
            $('#model-input').val(''); // Очищаем поле модели
            $('#model').val(''); // Очищаем скрытое поле модели
            $('#model-input').autocomplete("search", ""); // Сбрасываем автодополнение для модели
        }

        // Обработчик для изменения марки
        $('#brand-input').on('change', function() {
            var brand = $(this).val();
            $('#brand').val(brand); // Устанавливаем значение в скрытое поле
            updateModels(brand);
        });

        // Обработчик для изменения модели
        $('#model-input').on('input', function() {
            var model = $(this).val();
            $('#model').val(model); // Устанавливаем значение в скрытое поле
            $('#model-input').autocomplete("search", model); // Обновляем автодополнение для модели
        });
    });
</script>