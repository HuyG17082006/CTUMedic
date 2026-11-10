import { normalVietNameseDate } from "../../helper/time.js";
import { showAppointmentDetail } from "./appointmentDetailModal.js";
import { showCompleteModal } from "./appointmentCompleteModal.js";
import { showCancelModal } from "./appointmentCancelModal.js";

const status = {
    CONFIRMED: [
        'Đã xác nhận',
        'appointment-status--confirmed'
    ],

    CANCELLED: [
        'Đã hủy',
        'appointment-status--cancelled'
    ],

    COMPLETED: [
        'Đã khám',
        'appointment-status--completed'
    ],

    WAITING_UPDATE: [
        'Chờ cập nhật',
        'appointment-status--waiting-update'
    ],

    NO_SHOW: [
        'Vắng mặt',
        'appointment-status--no-show'
    ]
};

const isWaitingForUpdate = (appointment) => {

    if (appointment.trangThai !== 'CONFIRMED')
        return false;

    const appointmentTime = new Date(
        `${appointment.ngayKham}T${appointment.gioKham}`
    );

    appointmentTime.setMinutes(
        appointmentTime.getMinutes() + 40
    );

    return new Date() > appointmentTime;
};

const getAppointmentStatus = (appointment) => {

    if (isWaitingForUpdate(appointment))
        return status.WAITING_UPDATE;

    return status[appointment.trangThai] ?? [
        'Không xác định',
        ''
    ];
};

export const createAppointmentRow = (
    appointment,
    columns,
    tableType,
    role,
    renderAppointmentList
) => {

    const row = document.createElement('tr');

    row.dataset.appointmentId = appointment.id;

    const values = {
        maLichKham: appointment.id,
        benhNhan: appointment.tenBenhNhan,
        bacSi: appointment.tenBacSi,
        chuyenKhoa: appointment.chuyenKhoa,
        ngayKham: normalVietNameseDate(appointment.ngayKham),
        gioKham: appointment.gioKham
    };

    columns.forEach(key => {

        const cell = document.createElement('td');

        if (key === 'trangThai') {

            const [label, className] =
                getAppointmentStatus(appointment);

            const badge =
                document.createElement('span');

            badge.className =
                `appointment-status ${className}`;

            badge.textContent = label;

            cell.appendChild(badge);

        } else {

            cell.textContent =
                values[key] ?? '—';
        }

        row.appendChild(cell);
    });

    const actionCell =
        document.createElement('td');

    actionCell.className =
        'appointment-table__actions';

    actionCell.appendChild(
        createButton(
            'Chi tiết',
            'fa-eye',
            'appointment-action--detail',
            () => showAppointmentDetail(
                appointment.id,
                role,
                renderAppointmentList
            )
        )
    );

    if (tableType === 'appointments') {

        actionCell.appendChild(
            createButton(
                'Hủy lịch',
                'fa-ban',
                'appointment-action--cancel',
                () => showCancelModal(
                    appointment,
                    renderAppointmentList
                )
            )
        );
    }

    if (
        ['doctor', 'manager'].includes(role) &&
        appointment.trangThai === 'CONFIRMED'
    ) {

        actionCell.appendChild(
            createButton(
                'Hoàn thành',
                'fa-check',
                'appointment-action--complete',
                () => showCompleteModal(
                    appointment,
                    renderAppointmentList
                )
            )
        );
    }

    row.appendChild(actionCell);

    return row;
};

const createButton = (
    label,
    icon,
    className,
    callback
) => {

    const button =
        document.createElement('button');

    button.type = 'button';

    button.className =
        `appointment-action ${className}`;

    button.innerHTML =
        `<i class="fa-solid ${icon}"></i><span>${label}</span>`;

    button.addEventListener(
        'click',
        callback
    );

    return button;
};