import { normalizedTime, getDate, getCurrentHour } from '../helper/time.js';
import { WORKING_TIME_SLOTS } from '../helper/base.js';

const morningSlots = document.querySelector('#morning-slots');
const afternoonSlots = document.querySelector('#afternoon-slots');
const morningPeriod = document.querySelector('.schedule-period.morning');
const afternoonPeriod = document.querySelector('.schedule-period.afternoon');

const resetSelect = () => {
    document.querySelectorAll('.time-slot').forEach((e) => e.classList.remove('time-slot--selected'))
}

const renderTimeSlot = ({ start, end }, appointmentList, onSelect) => {
    const sameStart = appointmentList.some(
        ({ gioKham, cancelledBy }) => {
            return (normalizedTime(gioKham) === start) && (cancelledBy !== "PATIENT")
        }
    );

    const button = document.createElement('button');

    button.type = 'button';
    button.className = `time-slot ${sameStart ? 'time-slot--unavailable' : 'time-slot--available'}`;

    button.value = start;
    button.dataset.time = start;
    button.textContent = `${start} - ${end}`;
    button.disabled = sameStart;

    if (!sameStart) {
        button.onclick = () => {
            resetSelect()
            button.classList.add('time-slot--selected')
            onSelect(start)
        };
    }

    return button;
};

export const renderSchedulePeriods = (unavailableList = [], onSelect, values) => {
    morningSlots.innerHTML = '';
    afternoonSlots.innerHTML = '';

    legalTimeSlot(values.date);

    WORKING_TIME_SLOTS.forEach((timeSlot, index) => {
        const button = renderTimeSlot(timeSlot, unavailableList, onSelect);

        if (index < 6) {
            morningSlots.append(button);
        } else {
            afternoonSlots.append(button);
        }
    });
};

const legalTimeSlot = (date, timeOff) => {
    morningPeriod.classList.remove('unavailable');
    afternoonPeriod.classList.remove('unavailable');

    if (date !== getDate()) return;

    if (timeOff === 'FULL_DAY') {
        afternoonPeriod.classList.add('unavailable');
        morningPeriod.classList.add('unavailable');
        return;
    }

    if (getCurrentHour() >= 7 || timeOff === 'MORNING') {
        morningPeriod.classList.add('unavailable');
    }

    if (getCurrentHour() >= 13 || timeOff === 'AFTERNOON') {
        afternoonPeriod.classList.add('unavailable');
    }
};