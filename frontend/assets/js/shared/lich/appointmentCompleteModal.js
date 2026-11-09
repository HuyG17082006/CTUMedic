import { completeAppointment } from "../../api/lichkham.js";
import finishedAction from "../../helper/finishedAction.js";
import { normalVietNameseDate } from "../../helper/time.js";
import { setFields } from "../../helper/setText.js";

const completeModal = document.querySelector("#complete-appointment-modal");
const confirmCompleteButton = document.querySelector("#confirm-complete-appointment");

let currentAppointment = null;

export const showCompleteModal = appointment => {
    if (!completeModal) return;

    currentAppointment = appointment;

    completeModal.hidden = false;
    completeModal.setAttribute("aria-hidden", "false");

    setFields(completeModal, {
        "#complete-appointment-code": appointment.id,
        "#complete-appointment-patient": appointment.hoTenBenhNhan,
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

    const { status, message } = await completeAppointment(
        currentAppointment.id
    );

    finishedAction(status, message);

    closeCompleteModal();
});

completeModal?.querySelectorAll("[data-complete-close]").forEach(btn => btn.addEventListener("click", closeCompleteModal));