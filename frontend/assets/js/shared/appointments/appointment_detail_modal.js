import { getAppointmentDetail } from "../../api/appointments.js";
import { normalVietNameseDate, normalizedTime } from "../../helper/time.js";
import { setFields } from '../../helper/set_text.js'

import { showCancelModal } from "./appointment_cancel_modal.js";
import { showRescheduleModal } from "./reschedule_modal.js";
import { showCompleteModal } from "./appointment_complete_modal.js";
import { isWaitingForUpdate } from "./appointment_status.js";

const appointmentModal = document.querySelector('.appointment-modal');
const cancelBtn = appointmentModal.querySelector('[data-action-cancel]');
const rescheduleBtn = appointmentModal.querySelector('[data-action-reschedule]');
const completeBtn = appointmentModal.querySelector('[data-action-complete]');
// Lấy phần tử trong modal

let currentAppointment = null;
let refresh = null;

const statusMap = {
    CONFIRMED: 'Đã xác nhận',
    CANCELLED: 'Đã hủy',
    COMPLETED: 'Đã hoàn thành',
    NO_SHOW: 'Vắng mặt'
};

const cancelledByMap = {
    PATIENT: 'Bệnh nhân',
    DOCTOR: 'Bác sĩ',
    MANAGER: 'Quản lý'
};

const renderAppointmentDetail = (appointment) => {

    const {
        id,
        ngayKham,
        gioKham,
        liDoKham,
        tenBenhNhan,
        SDT,
        ngaySinh,
        tenBacSi,
        chuyenKhoa,
        trangThai,
        cancelledBy,
        cancelledAt,
        cancellationReason
    } = appointment;

    setFields(appointmentModal, {
        "[data-detail-appointment-id]": `#${id}`,
        "[data-detail-date]": normalVietNameseDate(ngayKham),
        "[data-detail-time]": normalizedTime(gioKham),
        "[data-detail-reason]": liDoKham || "Không có thông tin",

        "[data-detail-patient-name]": tenBenhNhan,
        "[data-detail-patient-phone]": SDT,
        "[data-detail-patient-birth]": ngaySinh
            ? normalVietNameseDate(ngaySinh)
            : "—",

        "[data-detail-doctor-name]": tenBacSi,
        "[data-detail-doctor-specialty]": chuyenKhoa
    });

    const status = appointmentModal.querySelector('[data-detail-status]');

    status.textContent =
        statusMap[trangThai] || 'WAITING-UPDATE';

    status.className =
        `appointment-status appointment-status--${trangThai.toLowerCase()}`;

    const cancellationSection = appointmentModal.querySelector(
        "[data-detail-cancellation]"
    );

    const isCancelled = trangThai === "CANCELLED";

    cancellationSection.hidden = !isCancelled;

    if (!isCancelled) return;

    setFields(appointmentModal, {
        "[data-detail-cancelled-by]":
            cancelledByMap[cancelledBy] || cancelledBy,

        "[data-detail-cancelled-at]": cancelledAt
            ? new Date(
                cancelledAt.replace(" ", "T")
            ).toLocaleString("vi-VN")
            : "—",

        "[data-detail-cancellation-reason]":
            cancellationReason || "Không có lý do"
    });

};

// Mở modal và lấy dữ liệu
export const showAppointmentDetail = async (
    appointmentId,
    role,
    renderAppointmentList,
    canAction = true
) => {
    console.log(1)
    if (!appointmentModal) return;
    console.log(1)
    const appointment = await getAppointmentDetail(appointmentId);
    console.log(1)
    if (!appointment) return;
    console.log(1)
    currentAppointment = appointment;
    refresh = renderAppointmentList;

    renderAppointmentDetail(appointment, role);

    toggleActionButton(appointment, role)

    appointmentModal.hidden = false;
    appointmentModal.setAttribute('aria-hidden', 'false');
}

const toggleActionButton = (appointment, role) => {
    const waitingForUpdate = isWaitingForUpdate(appointment);
    const isConfirmed = appointment.trangThai === 'CONFIRMED';

    cancelBtn.hidden = !isConfirmed || waitingForUpdate;
    rescheduleBtn.hidden =
        role !== 'patient' || !isConfirmed || waitingForUpdate;
    completeBtn.hidden =
        role === 'patient' || (!isConfirmed && !waitingForUpdate);
}

const closeAppointmentDetail = () => {
    appointmentModal.hidden = true;
    appointmentModal.setAttribute('aria-hidden', 'true');
}

appointmentModal.querySelector(".appointment-modal__close")?.addEventListener("click",closeAppointmentDetail);

cancelBtn?.addEventListener("click", () => {
    if (currentAppointment) {
        showCancelModal(currentAppointment, refresh);
    }
});

rescheduleBtn?.addEventListener("click", () => {
    if (currentAppointment) {
        showRescheduleModal(currentAppointment, refresh);
    }
});

completeBtn?.addEventListener("click", () => {
    if (currentAppointment) {
        showCompleteModal(currentAppointment, refresh);
    }
});