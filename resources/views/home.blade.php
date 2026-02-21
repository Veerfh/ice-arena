<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ледовый каток</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href='{{ asset('css/style.css') }}'/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
</head>
<body>
    <header>
        <div class="logo">
            <h1>Ледовая Арена</h1>
        </div>
        <nav>
            <ul>
                <li><a href="#prices">Цены</a></li>
                <li><a href="#skates">Коньки</a></li>
            </ul>
        </nav>
        <button class="btn-ticket" onclick="openTicketModal()">Купить билет</button>
    </header>

    <main>
        <section class="hero">
            <h2>ЛЕДОВАЯ АРЕНА</h2>
            <p>- лучшее место для активного отдыха всей семьей</p>
        </section>

        <section id="prices">
            <h3>Наши цены</h3>
            <div class="price-cards">
                <div class="price-card">
                    <h4>Входной билет</h4>
                    <p class="price">300 ₽</p>
                    <p>Вход на каток на весь день</p>
                </div>
                <div class="price-card">
                    <h4>Аренда коньков</h4>
                    <p class="price">150 ₽/час</p>
                    <p>Профессиональные коньки</p>
                </div>
            </div>
        </section>

        <section class="booking-section">
            <button class="btn-booking" onclick="openBookingModal()">Забронировать коньки</button>
        </section>

        <section id="skates">
            <h3>Доступные коньки</h3>
            <div class="skates-grid">
                @foreach($skates as $skate)
                <div class="skate-card">
                    <h4>{{ $skate->brand }} {{ $skate->model }}</h4>
                    <p>Размер: {{ $skate->size }}</p>
                    <p>В наличии: {{ $skate->quantity }}</p>
                </div>
                @endforeach
            </div>
        </section>
    </main>

    <div id="ticketModal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeTicketModal()">&times;</span>
            <h3>Покупка билета</h3>
            <form id="ticketForm">
                <div>
                    <label>ФИО:</label>
                    <input type="text" name="full_name" placeholder="Введите ФИО" required>
                </div>
                <div>
                    <label>Email:</label>
                    <input type="email" name="email" placeholder="Введите email" required>
                </div>
                <div>
                    <label>Телефон:</label>
                    <input type="text" name="phone" class="phone-mask" placeholder="+7 (999) 999-99-99" required>
                </div>
                <p id="totalAmount">Сумма к оплате: 300 ₽</p>
                <button type="submit">Оплатить</button>
            </form>
        </div>
    </div>

    <div id="bookingModal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeBookingModal()">&times;</span>
            <h3>Бронирование коньков</h3>
            <form id="bookingForm">
                <div>
                    <label>ФИО:</label>
                    <input type="text" name="full_name" placeholder="Введите ФИО" required>
                </div>
                <div>
                    <label>Телефон:</label>
                    <input type="text" name="phone" class="phone-mask" placeholder="+7 (999) 999-99-99" required>
                </div>
                <div>
                    <label>Количество часов:</label>
                    <select name="hours" required>
                        <option value="1">1 час</option>
                        <option value="2">2 часа</option>
                        <option value="3">3 часа</option>
                        <option value="4">4 часа</option>
                    </select>
                </div>
                <div>
                    <label>
                        <input type="checkbox" name="need_skates" id="needSkates"> Нужны коньки
                    </label>
                </div>
                <div id="skatesSelection" style="display: none;">
                    <div>
                        <label>Выберите коньки:</label>
                        <select name="skate_id" id="skateSelect">
                            <option value="">Выберите модель</option>
                            @foreach($skates as $skate)
                            <option value="{{ $skate->id }}" data-sizes="{{ $skate->size }}">{{ $skate->brand }} {{ $skate->model }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Размер:</label>
                        <input type="number" name="skate_size" id="skateSize" min="26" max="47">
                    </div>
                </div>
                <p id="totalAmount">Итого: 300 ₽</p>
                <button type="submit">Забронировать</button>
            </form>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('.phone-mask').inputmask('+7 (999) 999-99-99');

        $('#needSkates').change(function() {
            if(this.checked) {
                $('#skatesSelection').show();
                updateTotal();
            } else {
                $('#skatesSelection').hide();
                updateTotal();
            }
        });

        $('select[name="hours"], select[name="skate_id"]').change(updateTotal);
        
        function updateTotal() {
            let total = 300;
            if($('#needSkates').is(':checked')) {
                let hours = parseInt($('select[name="hours"]').val()) || 1;
                total += 150 * hours;
            }
            $('#totalAmount').text('Итого: ' + total + ' ₽');
        }

        $('#ticketForm, #bookingForm').submit(function(e) {
            e.preventDefault();
            
            $('.phone-mask').inputmask('remove');
            
            let formData = new FormData(this);
            
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            
            if ($(this).is('#bookingForm') && !$('#needSkates').is(':checked')) {
                formData.delete('need_skates');
            }
            
            $.ajax({
                url: $(this).is('#ticketForm') ? '{{ route("ticket.purchase") }}' : '{{ route("booking.create") }}',
                method: 'POST',
                data: formData,
                processData: false,  // КРИТИЧНО!
                contentType: false,  // КРИТИЧНО!
                success: function(response) {
                    $('.phone-mask').inputmask('+7 (999) 999-99-99'); // Восстанавливаем маски
                    window.location.href = response.payment_url;
                },
                error: function(xhr) {
                    $('.phone-mask').inputmask('+7 (999) 999-99-99'); // Восстанавливаем маски
                    console.log('Full error:', xhr.responseText);
                    let errorMsg = xhr.responseJSON?.errors || xhr.responseJSON?.error || 'Серверная ошибка';
                    alert('Ошибка: ' + JSON.stringify(errorMsg));
                }
            });
        });
    });

    function openTicketModal() {
        document.getElementById('ticketModal').style.display = 'block';
    }

    function closeTicketModal() {
        document.getElementById('ticketModal').style.display = 'none';
    }

    function openBookingModal() {
        document.getElementById('bookingModal').style.display = 'block';
    }

    function closeBookingModal() {
        document.getElementById('bookingModal').style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('ticketModal')) {
            closeTicketModal();
        }
        if (event.target == document.getElementById('bookingModal')) {
            closeBookingModal();
        }
    }
</script>

</body>
</html>