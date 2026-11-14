import { completeAppointment, noShowAppointment } from "../../api/appointments.js";
import finishedAction from "../../helper/finished_action.js";
import { normalVietNameseDate } from "../../helper/time.js";
import { setFields } from "../../helper/set_text.js";

const completeModal = document.querySelector("#complete-appointment-modal");
const confirmCompleteButton = document.querySelector("#confirm-complete-appointment");
const confirmNoShowButton = document.querySelector('#confirm-no-show-appointment')

let currentAppointment = null;
let refresh = null;

export const showCompleteModal = (appointment, reRender) => {
    if (!completeModal) return;

    currentAppointment = appointment;
    refresh = reRender;
    completeModal.hidden = false;
    completeModal.setAttribute("aria-hidden", "false");

    setFields(completeModal, {
        "#complete-appointment-code": appointment.id,
        "#complete-appointment-patient": appointment.tenBenhNhan,
        "#complete-appointment-date": normalVietNameseDate(appointment.ngayKham),
        "#complete-appointment-time": appointment.gioKham
    });
};

const closeCompleteModal = () => {
    currentAppointment = null;

    completeModal.hidden = true;
    completeModal.setAttribute("aria-hidden", "true");
};

confirmCompleteButton?.addEventListener("click", async () => {
    if (!currentAppointment) return;
    await submitComplete();
    closeCompleteModal();
});

confirmNoShowButton?.addEventListener("click", async () => {
    if (!currentAppointment) return;
    await submitNoShow();
    closeCompleteModal();
});

const submitNoShow = async () => {
    const { status, message } = await noShowAppointment(
        currentAppointment.id
    );

    finishedAction(status, message, refresh);
}

const submitComplete = async () => {
    const { status, message } = await completeAppointment(
        currentAppointment.id
    );

    finishedAction(status, message, refresh);
}

completeModal?.querySelectorAll("[data-complete-close]").forEach(btn => btn.addEventListener("click", closeCompleteModal));