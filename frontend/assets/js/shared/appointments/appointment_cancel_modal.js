import { cancelAppointment } from "../../api/appointments.js";
import finishedAction from "../../helper/finished_action.js";
import { normalVietNameseDate } from "../../helper/time.js";
import { setFields } from "../../helper/set_text.js";

const cancelModal = document.querySelector("#cancel-appointment-modal");
const cancelReason = document.querySelector("#cancel-appointment-reason");
const confirmCancelButton = document.querySelector("#confirm-cancel-appointment");

let currentAppointmentId = null;
let refresh = null;

export const showCancelModal = (appointment, reRender) => {
    if (!cancelModal) return;
    console.log(appointment)
    currentAppointmentId = appointment.id;
    refresh = reRender;
    cancelModal.hidden = false;
    cancelModal.setAttribute("aria-hidden", "false");

    setFields(cancelModal, {
        "#cancel-appointment-code": appointment.id,
        "#cancel-appointment-patient": appointment.tenBenhNhan,
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

const submitCancel = async () => {
    
    const { status, message } = await cancelAppointment(
        currentAppointmentId,
        cancelReason.value.trim()
    );

    finishedAction(status, message, refresh);
}

confirmCancelButton?.addEventListener("click", async () => {
    if (!currentAppointmentId) return;

    await submitCancel();
    closeCancelModal();
});

cancelModal?.querySelectorAll("[data-cancel-close]").forEach(btn =>btn.addEventListener("click", closeCancelModal));
