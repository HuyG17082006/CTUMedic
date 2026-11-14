import {
    getDoctorSchedule,
    rescheduleAppointment
} from "../../api/appointments.js";

import {
    renderSchedulePeriods
} from "../schedule_periods.js"

import { renderAppointmentList } from "./appointment_table.js";

import { normalVietNameseDate } from "../../helper/time.js";
import { setFields } from "../../helper/set_text.js";
import finishedAction from "../../helper/finished_action.js";

const rescheduleModal = document.querySelector('#reschedule-modal');

const morningContainer = document.querySelector('#morning-slots');
const afternoonContainer = document.querySelector('#afternoon-slots');

const confirmButton = document.querySelector('.reschedule-button--confirm');
const selectedTimeElement = document.querySelector('#reschedule-selected-time');

let currentAppointment = null;
let selectedTime = null;

const selectTime = (time) => {
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

    rescheduleModal.hidden = false;
    rescheduleModal.setAttribute('aria-hidden', 'false');

    confirmButton.disabled = true;

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

    renderSchedulePeriods(
        unavailableList,
        selectTime,
        { date: appointment.ngayKham }
    );
};

const submitBooking = async () => {

    if (!currentAppointment || !selectedTime)
        return;

    const { status, message } = await rescheduleAppointment(
        currentAppointment.id,
        currentAppointment.ngayKham,
        selectedTime
    );

    finishedAction(status, message, renderAppointmentList)

}

confirmButton?.addEventListener('click', async () => {
    await submitBooking()
    closeRescheduleModal()
});