import { normalVietNameseDate } from "../../helper/time.js";
import { showAppointmentDetail } from "./appointment_detail_modal.js";
import { showCompleteModal } from "./appointment_complete_modal.js";
import { showCancelModal } from "./appointment_cancel_modal.js";
import { isWaitingForUpdate } from "./appointment_status.js";

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

    const waitingForUpdate = isWaitingForUpdate(appointment);

    if (tableType === 'appointments' && !waitingForUpdate) {

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
        (
            appointment.trangThai === 'CONFIRMED' ||
            waitingForUpdate
        )
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