import { cancelAppointment } from "../../api/lichkham.js";
import finishedAction from "../../helper/finishedAction.js";
import { normalVietNameseDate } from "../../helper/time.js";
import { setFields } from "../../helper/setText.js";

const cancelModal = document.querySelector("#cancel-appointment-modal");
const cancelReason = document.querySelector("#cancel-appointment-reason");
const confirmCancelButton = document.querySelector("#confirm-cancel-appointment");

let currentAppointmentId = null;

export const showCancelModal = appointment => {
    if (!cancelModal) return;

    currentAppointmentId = appointment.id;

    cancelModal.hidden = false;
    cancelModal.setAttribute("aria-hidden", "false");

    setFields(cancelModal, {
        "#cancel-appointment-code": appointment.id,
        "#cancel-appointment-patient": appointment.hoTenBenhNhan,
        "#cancel-appointment-date": normalVietNameseDate(appointment.ngayKham),
        "#cancel-appointment-time": appointment.gioKham
    });
};

const closeCancelModal = () => {
    currentAppointmentId = null;

    cancelModal.hidden = true;
    cancelModal.setAttribute("aria-hidden", "true");

    cancelReason.value = "";
};

confirmCancelButton?.addEventListener("click", async () => {
    if (!currentAppointmentId) return;

    const { status, message } = await cancelAppointment(currentAppointmentId,cancelReason.value.trim());

    finishedAction(status, message);
});

cancelModal?.querySelectorAll("[data-cancel-close]").forEach(btn =>btn.addEventListener("click", closeCancelModal));
