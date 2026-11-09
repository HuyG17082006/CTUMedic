import { normalizedTime, getDate, getCurrentHour } from '../helper/time.js';

const timeList = window.WORKING_TIME_SLOTS;

const morningSlots = document.querySelector('#morning-slots');
const afternoonSlots = document.querySelector('#afternoon-slots');

const morningPeriod = document.querySelector('.booking-period.morning');
const afternoonPeriod = document.querySelector('.booking-period.afternoon');

const renderTimeSlot = ({ start, end }, unavailableList, onSelect) => {
    const sameStart = unavailableList.some(
        ({ gioKham, cancelledBy }) => {
            
            return (normalizedTime(gioKham) === start) && (cancelledBy === "PATIENT")
        }
    );

    const button = document.createElement('button');

    button.type = 'button';
    button.className = `time-slot ${
        sameStart ? 'time-slot--unavailable' : 'time-slot--available'
    }`;

    button.value = start;
    button.dataset.time = start;
    button.textContent = `${start} - ${end}`;
    button.disabled = sameStart;

    if (!sameStart) {
        button.onclick = () => onSelect(start);
    }

    return button;
};

export const renderBookingPeriods = (unavailableList = [], onSelect, values) => {
    morningSlots.innerHTML = '';
    afternoonSlots.innerHTML = '';

    legalTimeSlot(values.date)

    timeList.forEach((timeSlot, index) => {
        const button = renderTimeSlot(timeSlot, unavailableList, onSelect);

        if (index < 6) {
            morningSlots.append(button);
        } else {
            afternoonSlots.append(button);
        }
    });
};

const legalTimeSlot = (date) => {

    morningPeriod.classList.remove('unavailable');
    afternoonPeriod.classList.remove('unavailable');

    if (date !== getDate()) return;

    if (getCurrentHour() >= 7) {
        morningPeriod.classList.add('unavailable');
    }

    if (getCurrentHour() >= 13) {
        afternoonPeriod.classList.add('unavailable');
    }
};