import { completeAppointment, noShowAppointment } from "../../api/appointments.js";
import finishedAction from "../../helper/finished_action.js";
import { normalVietNameseDate } from "../../helper/time.js";
import { setFields } from "../../helper/set_text.js";

import { closeAppointmentDetail } from "./appointment_detail_modal.js";

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

const runAppointmentAction = async (action, failureMessage) => {
    if (!currentAppointment) return;

    try {
        const result = await action(currentAppointment.id);
        if (result.status !== 'success') {
            alert(result.message || failureMessage);
            return;
        }

        closeCompleteModal();
        closeAppointmentDetail();
        await finishedAction(result.status, result.message, refresh);
    } catch (error) {
        alert(error.message || failureMessage);
    }
};

confirmCompleteButton?.addEventListener("click", async () => {
    await runAppointmentAction(
        completeAppointment,
        'Không thể hoàn thành lịch khám.'
    );
});

confirmNoShowButton?.addEventListener("click", async () => {
    await runAppointmentAction(
        noShowAppointment,
        'Không thể đánh dấu bệnh nhân vắng mặt.'
    );
});

completeModal?.querySelectorAll("[data-complete-close]").forEach(btn => btn.addEventListener("click", closeCompleteModal));
