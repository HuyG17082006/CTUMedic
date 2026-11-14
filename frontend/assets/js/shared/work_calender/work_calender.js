
const calendarElement = document.querySelector('#work-schedule-calendar');
const currentMonthElement = document.querySelector('#current-month');
const prevMonthButton = document.querySelector('#prev-month');
const nextMonthButton = document.querySelector('#next-month');
const leaveDateInput = document.querySelector('#leave-date');

// Điều chỉnh URL theo vị trí thực tế của API trong project
const API_URL = '/dashboard/CTUMedic/backend/api/work_calender/get.php';

const today = new Date();

let currentDate = new Date(
    today.getFullYear(),
    today.getMonth(),
    1
);

let leaveList = [];
let selectedDate = null;

// Tên tháng
const MONTH_NAMES = [
    'Tháng 1', 'Tháng 2', 'Tháng 3',
    'Tháng 4', 'Tháng 5', 'Tháng 6',
    'Tháng 7', 'Tháng 8', 'Tháng 9',
    'Tháng 10', 'Tháng 11', 'Tháng 12'
];

const DAY_NAMES = [
    'Thứ 2', 'Thứ 3', 'Thứ 4',
    'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'
];

// Chuyển Date thành YYYY-MM-DD theo local timezone
function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

// Lấy dữ liệu lịch nghỉ theo tháng
async function fetchLeaveList() {
    const month = currentDate.getMonth() + 1;
    const year = currentDate.getFullYear();

    try {
        const params = new URLSearchParams({
            month,
            year
        });

        const response = await fetch(`${API_URL}?${params}`);

        if (!response.ok) {
            throw new Error('Không thể tải dữ liệu lịch nghỉ.');
        }

        const data = await response.json();

        if (data.status !== 'success') {
            throw new Error(data.message || 'Lấy lịch nghỉ thất bại.');
        }

        leaveList = data.list || [];

        renderCalendar();

    } catch (error) {
        console.error(error);

        calendarElement.innerHTML = `
            <div class="calendar-error">
                Không thể tải lịch nghỉ. Vui lòng thử lại.
            </div>
        `;
    }
}

// Lấy danh sách nghỉ theo ngày
function getLeaveByDate(date) {
    return leaveList.filter(item => item.ngayNghi === date);
}

// Chuyển buổi nghỉ thành nội dung hiển thị
function getSessionLabel(session) {
    const labels = {
        MORNING: 'Buổi sáng',
        AFTERNOON: 'Buổi chiều',
        FULL_DAY: 'Cả ngày'
    };

    return labels[session] || 'Không xác định';
}

// Tạo HTML hiển thị lịch nghỉ trong một ngày
function renderLeaveEvents(date) {
    const events = getLeaveByDate(date);

    if (events.length === 0) {
        return '';
    }

    return events.map(item => {
        const isSystemLeave = item.bacSiId === null;

        const eventClass = isSystemLeave
            ? 'system-leave'
            : `leave-${item.buoi.toLowerCase()}`;

        const title = isSystemLeave
            ? 'Toàn hệ thống'
            : (item.hoTen || 'Bác sĩ nghỉ');

        return `
            <div
                class="calendar-leave-event ${eventClass}"
                title="${title} - ${getSessionLabel(item.buoi)}"
            >
                <span>${getSessionLabel(item.buoi)}</span>
                <small>${title}</small>
            </div>
        `;
    }).join('');
}

// Render lịch tháng
function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    currentMonthElement.textContent =
        `${MONTH_NAMES[month]} năm ${year}`;

    const firstDay = new Date(year, month, 1);

    // Chuyển Sunday = 0 thành vị trí cuối tuần
    const startDay = (firstDay.getDay() + 6) % 7;

    const daysInMonth = new Date(year, month + 1, 0).getDate();

    const previousMonthDays = new Date(year, month, 0).getDate();

    let html = `
        <div class="work-calendar-grid">
            ${DAY_NAMES.map(day => `
                <div class="calendar-weekday">${day}</div>
            `).join('')}
    `;

    const totalCells = Math.ceil((startDay + daysInMonth) / 7) * 7;

    for (let i = 0; i < totalCells; i++) {
        let day;
        let date;
        let isOtherMonth = false;

        if (i < startDay) {
            day = previousMonthDays - startDay + i + 1;

            date = formatDate(
                new Date(year, month - 1, day)
            );

            isOtherMonth = true;

        } else if (i >= startDay + daysInMonth) {
            day = i - startDay - daysInMonth + 1;

            date = formatDate(
                new Date(year, month + 1, day)
            );

            isOtherMonth = true;

        } else {
            day = i - startDay + 1;

            date = formatDate(
                new Date(year, month, day)
            );
        }

        const events = getLeaveByDate(date);

        const isToday = date === formatDate(today);
        const isSelected = date === selectedDate;

        const classes = [
            'calendar-day',
            isOtherMonth ? 'other-month' : '',
            isToday ? 'today' : '',
            isSelected ? 'selected' : '',
            events.length ? 'has-leave' : ''
        ].filter(Boolean).join(' ');

        html += `
            <button
                type="button"
                class="${classes}"
                data-date="${date}"
            >
                <span class="calendar-day-number">${day}</span>

                <div class="calendar-day-events">
                    ${renderLeaveEvents(date)}
                </div>
            </button>
        `;
    }

    html += '</div>';

    calendarElement.innerHTML = html;
}

// Chọn ngày trên lịch
calendarElement.addEventListener('click', event => {
    const dayElement = event.target.closest('.calendar-day');

    if (!dayElement) return;

    const date = dayElement.dataset.date;

    selectedDate = date;

    if (leaveDateInput) {
        leaveDateInput.value = date;
    }

    // Nếu chọn ngày thuộc tháng khác thì chuyển sang tháng đó
    const selected = new Date(`${date}T00:00:00`);

    if (
        selected.getMonth() !== currentDate.getMonth() ||
        selected.getFullYear() !== currentDate.getFullYear()
    ) {
        currentDate = new Date(
            selected.getFullYear(),
            selected.getMonth(),
            1
        );

        fetchLeaveList();
        return;
    }

    renderCalendar();
});

// Chuyển tháng trước
prevMonthButton.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);

    fetchLeaveList();
});

// Chuyển tháng sau
nextMonthButton.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);

    fetchLeaveList();
});

// Khi người dùng tự nhập ngày trong form
leaveDateInput?.addEventListener('change', () => {
    if (!leaveDateInput.value) return;

    selectedDate = leaveDateInput.value;

    const date = new Date(`${selectedDate}T00:00:00`);

    currentDate = new Date(
        date.getFullYear(),
        date.getMonth(),
        1
    );

    fetchLeaveList();
});

// Khởi tạo
fetchLeaveList();
