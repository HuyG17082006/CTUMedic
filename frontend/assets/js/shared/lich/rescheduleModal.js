import {
    getDoctorSchedule,
    rescheduleAppointment
} from "../../api/lichkham.js";

import {
    renderBookingPeriods
} from "../booking-periods.js"

import { normalVietNameseDate } from "../../helper/time.js";
import { setFields } from "../../helper/setText.js";
import finishedAction from "../../helper/finishedAction.js";

const rescheduleModal = document.querySelector('#reschedule-modal');

const morningContainer = document.querySelector('#morning-slots');
const afternoonContainer = document.querySelector('#afternoon-slots');

const confirmButton = document.querySelector('#confirm-reschedule');
const selectedTimeElement = document.querySelector('#reschedule-selected-time');

const currentDateElement = document.querySelector('#reschedule-current-date');
const currentTimeElement = document.querySelector('#reschedule-current-time');

let currentAppointment = null;
let selectedTime = null;

const selectAnotherTime = (time) => {
    selectedTime = time;
    selectedTimeElement.textContent = time
    confirmButton.disabled = false
};

export const showRescheduleModal = async (appointment) => {
    if (!rescheduleModal || !appointment) return;

    currentAppointment = appointment
    selectedTime = null

    setFields(rescheduleModal, {
        '#reschedule-current-date': normalVietNameseDate(appointment.ngayKham),
        '#reschedule-current-time': appointment.gioKham,
        '#reschedule-selected-time': 'Chưa chọn'
    });

    confirmButton.disabled = true;

    rescheduleModal.hidden = false;
    rescheduleModal.setAttribute('aria-hidden', 'false');

    rescheduleModal
        .querySelector('.reschedule-modal__close')
        ?.focus();

    await loadAvailableTimeSlots(appointment);
};

const closeRescheduleModal = () => {
    document.activeElement?.blur();

    rescheduleModal.hidden = true;
    rescheduleModal.setAttribute('aria-hidden', 'true');

    currentAppointment = null;
    selectedTime = null;

    selectedTimeElement.textContent = 'Chưa chọn';

    if (morningContainer) morningContainer.innerHTML = '';
    if (afternoonContainer) afternoonContainer.innerHTML = '';
};

rescheduleModal
    ?.querySelectorAll('[data-reschedule-close]')
    .forEach(element => {
        element.addEventListener('click', closeRescheduleModal);
    });

const loadAvailableTimeSlots = async (appointment) => {
    const unavailableList = await getDoctorSchedule({
        doctorId: appointment.bacSiId,
        date: appointment.ngayKham,
    });

    renderBookingPeriods(
        unavailableList,
        selectAnotherTime,
        { date: appointment.ngayKham }
    );
};

confirmButton?.addEventListener('click', async () => {
    if (!currentAppointment || !selectedTime) return;

    const { status, messsage } = await rescheduleAppointment(
        currentAppointment.id,
        currentAppointment.ngayKham,
        selectedTime
    );

    finishedAction(status, messsage)
});