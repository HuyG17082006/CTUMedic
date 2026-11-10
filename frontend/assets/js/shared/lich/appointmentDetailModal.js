import { getAppointmentDetail } from "../../api/lichkham.js";
import { normalVietNameseDate, normalizedTime } from "../../helper/time.js";
import { setFields } from '../../helper/setText.js'

import { showCancelModal } from "./appointmentCancelModal.js";
import { showRescheduleModal } from "./rescheduleModal.js";
import { showCompleteModal } from "./appointmentCompleteModal.js";

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
    COMPLETED: 'Đã hoàn thành'
};

const cancelledByMap = {
    PATIENT: 'Bệnh nhân',
    DOCTOR: 'Bác sĩ'
};

const renderAppointmentDetail = (appointment, role) => {

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

    toggleActionButton(appointment, role)

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
        statusMap[trangThai] || trangThai;

    status.className =
        `appointment-status appointment-status--${trangThai.toLowerCase()}`;

    const cancellationSection = appointmentModal.querySelector(
        "[data-detail-cancellation]"
    );

    const isCancelled = trangThai === "CANCELLED";

    cancellationSection.hidden = !isCancelled;

    if (!isCancelled) return;


    setFields({
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
export const showAppointmentDetail = async (appointmentId, role, renderAppointmentList) => {

    if (!appointmentModal) return;

    const appointment = await getAppointmentDetail(appointmentId);

    if (!appointment) return;

    currentAppointment = appointment;
    refresh = renderAppointmentList;

    renderAppointmentDetail(appointment, role);

    appointmentModal.hidden = false;
    appointmentModal.setAttribute('aria-hidden', 'false');
}

const toggleActionButton = (appointment, role) => {
    if (appointment.trangThai !== 'CONFIRMED') {
        cancelBtn.hidden = true
        rescheduleBtn.hidden = true
        completeBtn.hidden = true
        return;
    }

    if (role === 'patient') {
        rescheduleBtn.hidden = false
    }

    if (role !== 'patient') {
        completeBtn.hidden = false
    }

    cancelBtn.hidden = false
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