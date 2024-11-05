
let originalTotal = 0;
let currentTotal = 0;
let selectedSeats = [];
let currentShowtimeId = null;

function applyVoucher() {
    const voucherCode = document.getElementById('voucher_code').value;
    let total = Number(originalTotal);
    const voucher = vouchers.find(v => v.code === voucherCode && customerVouchers.includes(v.id));
    let discountValue = total * (voucher.value / 100)
    if (voucher) {
        total = total - discountValue;
    }
    document.getElementById('total_amount').innerText = new Intl.NumberFormat('vi-VN').format(total) + ' VND';
    document.getElementById('hidden_total_amount').value = total;
    document.getElementById('hidden_voucher_code').value = voucherCode;
    const discountAmountElement = document.getElementById('discount-amount');
    discountAmountElement.innerText = '- ' + new Intl.NumberFormat('vi-VN').format(discountValue)+ ' VND';
    discountAmountElement.classList.remove('hidden');
}

function setDefaultValues() {
    const totalAmountInput = document.getElementById('hidden_total_amount');
    if (!totalAmountInput.value) {
        totalAmountInput.value = originalTotal;

    }
}

document.addEventListener('DOMContentLoaded', function() {
    const dateSelector = document.getElementById('date-selector');
    const movieId = document.getElementById('movie-id').value;

    // Automatically select today's date
    const today = new Date().toISOString().split('T')[0];
    const todayItem = dateSelector.querySelector(`[data-date="${today}"]`);
    if (todayItem) {
        todayItem.classList.add('bg-blue-600', 'text-white');
        fetchShowtimes(today, movieId);
    }

    dateSelector.addEventListener('click', function(event) {
        const item = event.target.closest('.date-item');
        if (item) {
            const selectedDate = item.getAttribute('data-date');
            fetchShowtimes(selectedDate, movieId);
            const activeItem = dateSelector.querySelector('.bg-blue-600');

            if (activeItem) {
                activeItem.classList.remove('bg-blue-600', 'text-white');
                activeItem.classList.add('bg-gray-200');
            }
            item.classList.remove('bg-gray-200');
            item.classList.add('bg-blue-600', 'text-white');
        }
    });
});

async function fetchShowtimes(date, movieId) {
    try {
        const response = await fetch(`/showtimes?date=${date}&movie_id=${movieId}`);
        const data = await response.json();
        const timeslotContainer = document.getElementById('timeslot-container');
        timeslotContainer.innerHTML = '';
        data.showtimes.forEach(showtime => {
            const button = document.createElement('button');
            button.classList.add('border', 'border-gray-300', 'px-2', 'py-2', 'rounded',
                'hover:bg-gray-300', 'showtime-button');
            button.textContent = showtime.start_time;
            button.addEventListener('click', function() {
                document.getElementById('hidden_schedule_id').value = data.scheduleId;
                document.getElementById('hidden_showtime_id').value = showtime.id;
                if (currentShowtimeId !== showtime.id) {
                    clearHiddenInputsAndResetSelection();
                    currentShowtimeId = showtime.id;
                }
                openSeatSelectionModal(date, movieId, showtime.id);
            });
            timeslotContainer.appendChild(button);
        });

    } catch (error) {
        console.error('Error fetching showtimes:', error);
    }
}

function clearHiddenInputsAndResetSelection() {
    document.getElementById('hidden_seats_selected').value = '';
    document.getElementById('hidden_total_amount').value = '';
    document.getElementById('hidden_voucher_code').value = '';
    selectedSeats = [];
    currentTotal = 0;
    const totalPriceElement = document.getElementById('total_price');
    totalPriceElement.textContent = '0 VND';
    const seatElements = document.querySelectorAll('.seat.selected');
    seatElements.forEach(seat => {
        seat.classList.remove('selected', 'bg-blue-500', 'text-white');
        seat.classList.add('hover:bg-gray-300');
    });
}

function openSeatSelectionModal(date, movieId, showtimeId) {
    fetchSeats(date, movieId, showtimeId).then(() => {
        selectedSeats.forEach(seatId => {
            const seatDiv = document.querySelector(`[data-seat-id="${seatId}"]`);
            if (seatDiv) {
                seatDiv.classList.add('selected', 'bg-blue-500', 'text-white');
                seatDiv.classList.remove('hover:bg-gray-300');
            }
        });
    });
    document.getElementById('seatSelectionModal').classList.remove('hidden');
}

async function fetchSeats(date, movieId, showtimeId) {
    const response = await fetch(`/seats?date=${date}&movie_id=${movieId}&showtime_id=${showtimeId}`);
    const data = await response.json();
    const seats = data.seats;
    console.log(seats);

    const seatsContainer = document.getElementById('seats-container');
    seatsContainer.innerHTML = '';

    seats.forEach((seat) => {
        const seatDiv = document.createElement('div');
        seatDiv.classList.add('border', 'border-gray-300', 'px-2', 'py-2',
            'rounded', 'cursor-pointer','flex','justify-center','items-center');
        seatDiv.textContent = seat.seat_number;
        seatDiv.setAttribute('data-seat-id', seat.id);
        seatDiv.setAttribute('data-seat-price', data.price);
        if (seat.tickets.some(ticket => ticket.status === 'ordered')) {
            seatDiv.classList.add('bg-gray-500','text-white');
            seatDiv.classList.remove('hover:bg-gray-300');
            seatDiv.classList.add('cursor-not-allowed');
        } else {
            seatDiv.classList.add('hover:bg-gray-300');
            seatDiv.addEventListener('click', function() {
                toggleSeatSelection(seatDiv);
            });
        }

        seatsContainer.appendChild(seatDiv);
    });
}


function toggleSeatSelection(seatDiv) {
    const seatId = seatDiv.getAttribute('data-seat-id');
    const seatPrice = parseFloat(seatDiv.getAttribute('data-seat-price'));
    if (seatDiv.classList.contains('selected')) {
        seatDiv.classList.remove('selected');
        seatDiv.classList.remove('bg-blue-500', 'text-white');
        seatDiv.classList.add('hover:bg-gray-300');
        selectedSeats = selectedSeats.filter(id => id !== seatId);
        const hiddenSeatsSelected = document.getElementById('hidden_seats_selected').value.split(',');
        const updatedSeats = hiddenSeatsSelected.filter(id => id !== seatId);
        document.getElementById('hidden_seats_selected').value = updatedSeats.join(',');
        updateTotalPrice(-seatPrice);
    } else {
        seatDiv.classList.add('selected');
        seatDiv.classList.add('bg-blue-500', 'text-white');
        seatDiv.classList.remove('hover:bg-gray-300');
        selectedSeats.push(seatId);
        document.getElementById('hidden_seats_selected').value = selectedSeats.join(',');
        updateTotalPrice(seatPrice);
    }
}

function updateTotalPrice(priceChange) {
    const totalPriceElement = document.getElementById('total_price');
    currentTotal += priceChange;
    totalPriceElement.textContent = new Intl.NumberFormat('vi-VN').format(currentTotal) + ' VND';
}

function bookSeats() {
    originalTotal = document.getElementById('total_price').textContent.replace(' VND', '').replace(/\./g, '');
    document.getElementById('default-price').innerText = 'Price: ' + new Intl.NumberFormat('vi-VN').format(
        originalTotal) + ' VND';
    document.getElementById('total_amount').innerText = document.getElementById('total_price').textContent;
    closeSeatSelectionModal();
}

function closeSeatSelectionModal() {
    document.getElementById('seatSelectionModal').classList.add('hidden');
}

function openVoucherModal() {
    document.getElementById('voucherSelectionModal').classList.remove('hidden');
}

function closeVoucherModal() {
    document.getElementById('voucherSelectionModal').classList.add('hidden');
}

function selectVoucher(voucherCode) {
    document.getElementById('voucher_code').value = voucherCode;
    applyVoucher();
    closeVoucherModal();
}
