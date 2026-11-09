import { getDoctorList } from "../../api/bacsi.js";
import { getAppointmentList } from "../../api/lichkham.js";
import { normalVietNameseDate } from "../../helper/time.js";
import { initPagination } from "../pagination.js";
import { showAppointmentDetail } from "./appointmentModal.js";
import { showCompleteModal } from "./appointmentCompleteModal.js";
import { showCancelModal } from "./appointmentCancelModal.js";

const specialtySelectBox = document.querySelector('.specialty-select-box');
const doctorSelectBox = document.querySelector('.doctor-select-box');
const dateInputBox = document.querySelector('.date-input-box');
const statusSelectBox = document.querySelector('.status-select-box');
const filterBtnSubmit = document.querySelector('.appointment-filter__submit');
const filterBtnReset = document.querySelector('.appointment-filter__reset');

const appointmentTable = document.querySelector('.appointment-table');
const appointmentBody = appointmentTable.querySelector('[data-appointment-list]')

const paginationElement = document.querySelector('[data-pagination]');

const pagination = initPagination({
    container: paginationElement,

    async onPageChange(page) {
        params.pageNumber = page;

        await renderAppointmentList();
    }
});


const specialtyList = window.SPECIALTY_LIST;

const currentUrlParams = new URL(window.location.href).searchParams;

let params = {
    specialty: currentUrlParams.get('specialty') || '',
    date: currentUrlParams.get('date') || '',
    doctorId: currentUrlParams.get('doctorId') || '',
    status: currentUrlParams.get('status') || '',
    pageNumber: Number(currentUrlParams.get('pageNumber')) || 1,
}

const renderUrlValue = () => {
    dateInputBox.value = params.date;
    doctorSelectBox.value = params.doctorId;
    specialtySelectBox.value = params.specialty;
    statusSelectBox.value = params.status;
}

const changeUrl = (params) => {
    const url = new URL(window.location.href)


    Object.entries(params).forEach(([key, value]) => {
        if (value)
            url.searchParams.set(key, value);
        else
            url.searchParams.delete(key);
    });

    window.history.replaceState({}, '', url.toString());
};

const renderSpecialtyList = () => {
    specialtyList.forEach(specialty => {
        const option = document.createElement('option');

        option.value = specialty;
        option.textContent = specialty;

        specialtySelectBox.append(option);
    });
};

const renderDoctorList = async (specialty) => {

    if (!toggleDoctorSelectBox(specialty))
        return;

    const doctorList = await getDoctorList(specialty)

    if (doctorList)
        doctorList.forEach((doctor) => doctorSelectBox.append(renderDoctor(doctor)))

    if (params.doctorId)
        doctorSelectBox.value = params.doctorId

}

const renderDoctor = (doctor) => {
    const option = document.createElement('option');

    option.innerText = doctor.hoTen;
    option.value = doctor.id;

    return option
}

const toggleDoctorSelectBox = (specialty) => {
    doctorSelectBox.innerHTML = '';

    if (specialty) {
        doctorSelectBox.disabled = false

        const option = document.createElement('option')
        option.value = '';
        option.innerText = 'Chọn bác sĩ'

        doctorSelectBox.append(option);

        return true;
    }
    else {

        const option = document.createElement('option');
        option.value = '';
        option.innerText = 'Chọn chuyên khoa trước';
        option.selected = true;

        doctorSelectBox.disabled = true

        doctorSelectBox.append(option);

        return false;
    }
};

const appointmentColumns = [
    'maLichKham',
    'benhNhan',
    'bacSi',
    'chuyenKhoa',
    'ngayKham',
    'gioKham',
    'trangThai'
];

const status = {
    CONFIRMED: ['Đã xác nhận', 'appointment-status--confirmed'],
    CANCELLED: ['Đã hủy', 'appointment-status--cancelled'],
    COMPLETED: ['Đã khám', 'appointment-status--completed']
};

const renderAppointmentList = async () => {
    changeUrl(params);

    const tableType = appointmentTable.dataset.tableType;
    const role = appointmentTable.dataset.role;
    const data = await getAppointmentList({ ...params, tableType });
    const appointmentList = data.list ?? [];
    const columns = appointmentColumns.filter(checkExistColumn);
    const fragment = document.createDocumentFragment();

    if (appointmentList.length === 0) {
        const row = document.createElement('tr');
        const cell = document.createElement('td');

        cell.colSpan = columns.length + 1;
        cell.className = 'appointment-table__empty';
        cell.textContent = 'Chưa có dữ liệu lịch khám.';

        row.appendChild(cell);
        fragment.appendChild(row);
    } else {
        appointmentList.forEach(appointment => {
            fragment.appendChild(createAppointmentRow(appointment, columns, tableType, role));
        });
    }

    appointmentBody.replaceChildren(fragment);
    pagination.update(data.pagination.pageNumber, data.pagination.totalPages);
};

const createAppointmentRow = (appointment, columns, tableType, role) => {
    const row = document.createElement('tr');
    row.dataset.appointmentId = appointment.id;

    const values = {
        maLichKham: `LK${String(appointment.id).padStart(4, '0')}`,
        benhNhan: appointment.hoTenBenhNhan,
        bacSi: appointment.hoTenBacSi,
        chuyenKhoa: appointment.chuyenKhoa,
        ngayKham: normalVietNameseDate(appointment.ngayKham),
        gioKham: appointment.gioKham
    };

    columns.forEach(key => {
        const cell = document.createElement('td');

        if (key === 'trangThai') {
            const [label, className] = status[appointment.trangThai]
                ?? ['Không xác định', ''];

            const badge = document.createElement('span');
            badge.className = `appointment-status ${className}`;
            badge.textContent = label;

            cell.appendChild(badge);
        } else {
            cell.textContent = values[key] ?? '—';
        }

        row.appendChild(cell);
    });

    const actionCell = document.createElement('td');
    actionCell.className = 'appointment-table__actions';

    const createButton = (label, icon, className, callback) => {
        const button = document.createElement('button');

        button.type = 'button';
        button.className = `appointment-action ${className}`;
        button.innerHTML = `<i class="fa-solid ${icon}"></i><span>${label}</span>`;
        button.addEventListener('click', callback);

        return button;
    };
    console.log(role)
    actionCell.appendChild(
        createButton('Chi tiết', 'fa-eye', 'appointment-action--detail',
            () => showAppointmentDetail(appointment.id, role))
    );

    if (tableType === 'appointments') {
        actionCell.appendChild(
            createButton('Hủy lịch', 'fa-ban', 'appointment-action--cancel',
                () => showCancelModal(appointment))
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
                () => showCompleteModal(appointment)
            )
        );
    }

    row.appendChild(actionCell);

    return row;
};

const checkExistColumn = key => {
    return appointmentTable.querySelector(
        `thead th[data-column="${key}"]`
    ) !== null;
};

const render = async () => {
    renderSpecialtyList()
    renderUrlValue()
    await renderAppointmentList()
    if (params.specialty) {
        await renderDoctorList(params.specialty);
    }
}

await render();


dateInputBox.addEventListener('change', (e) => {
    params.date = e.target.value
})
doctorSelectBox.addEventListener('change', (e) => {
    params.doctorId = e.target.value
})

if (statusSelectBox)
    statusSelectBox.addEventListener('change', (e) => {
        params.status = e.target.value
    })
specialtySelectBox.addEventListener('change', async (e) => {
    params.specialty = e.target.value
    params.doctorId = ''
    doctorSelectBox.value = ''
    await renderDoctorList(params.specialty);
})
filterBtnSubmit.addEventListener('click', async () => {
    params.pageNumber = 1
    await renderAppointmentList()
})
filterBtnReset.addEventListener('click', async () => {
    params.pageNumber = 1
    specialtySelectBox.value = '';
    dateInputBox.value = '';

    if (statusSelectBox)
        statusSelectBox.value = '';

    await renderDoctorList('');
    await renderAppointmentList();
});