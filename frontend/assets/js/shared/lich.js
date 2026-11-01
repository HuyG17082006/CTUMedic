import { getDoctorList } from "../api/bacsi.js";
import { getAppointmentList } from "../api/lichkham.js";

const specialtySelectBox = document.querySelector('.specialty-select-box');
const doctorSelectBox = document.querySelector('.doctor-select-box');
const dateInputBox = document.querySelector('.date-input-box');
const statusSelectBox = document.querySelector('.status-select-box');
const filterBtnSubmit = document.querySelector('.appointment-filter__submit');
const filterBtnReset = document.querySelector('.appointment-filter__reset');

const appointmentTable = document.querySelector('.appointment-table');
const appointmentBody = appointmentTable.querySelector('[data-appointment-list]')
const appointmentHead = appointmentTable.querySelectorAll('th');

const specialtyList = window.SPECIALTY_LIST;

const currentUrlParams = new URL(window.location.href).searchParams;

let params = {
    specialty: currentUrlParams.get('specialty') || '',
    date: currentUrlParams.get('date') || '',
    doctorId: currentUrlParams.get('doctorId') || '',
    status: currentUrlParams.get('status') || '',
    pageNumber: Number(currentUrlParams.get('pageNumber')) || 1,
}

const changeUrl = (params) => {
    const url = new URL(window.location.href)
    url.searchParams.set('pageNumber', '1');

    Object.entries(params).forEach(([key, value]) => {
        if (value)
            url.searchParams.set(key, value);
        else
            url.searchParams.delete(key);
    });

    window.history.replaceState({}, '', url.toString());
};

const renderUrlValue = () => {
    const currentUrlParams = new URL(window.location.href).searchParams;

    dateInputBox.value = currentUrlParams.get('date') || '';
    doctorSelectBox.value = currentUrlParams.get('doctorId') || '';
    specialtySelectBox.value = currentUrlParams.get('specialty') || '';
    statusSelectBox.value = currentUrlParams.get('status') || ''
}

const renderSpecialtyList = () => {
    specialtyList.forEach((specialty) => specialtySelectBox.append(renderSpecialty(specialty)))
}

const renderSpecialty = (specialty) => {
    const option = document.createElement('option');

    option.innerText = specialty;
    option.value = specialty;

    return option
}

const renderDoctorList = async (specialty) => {
    console.log(1)
    doctorSelectBox.innerHTML = '';

    if (!toggleDoctorSelectBox(specialty))
        return;

    const doctorList = await getDoctorList(specialty)

    if (doctorList)
        doctorList.forEach((doctor) => doctorSelectBox.append(renderDoctor(doctor)))

    if (params.doctorId)
        doctorSelectBox.value = params.doctorId

}

const toggleDoctorSelectBox = (specialty) => {
    if (specialty) {
        doctorSelectBox.disabled = false

        const option = document.createElement('option')
        option.value = '';
        option.innerText = 'Chọn bác sĩ'

        doctorSelectBox.append(option);

        return true;
    }
    else {
        doctorSelectBox.innerHTML = '';

        const option = document.createElement('option');
        option.value = '';
        option.innerText = 'Chọn chuyên khoa trước';
        option.selected = true;

        doctorSelectBox.disabled = true

        doctorSelectBox.append(option);

        return false;
    }
};

const renderDoctor = (doctor) => {
    const option = document.createElement('option');

    option.innerText = doctor.hoTen;
    option.value = doctor.id;

    return option
}

const checkExistColumn = (key) => {

    const isExist = [...appointmentHead].some(th => th.dataset.column === key)

    return isExist;

}

const renderAppointmentList = async () => {
    changeUrl(params)

    const appointmentList = await getAppointmentList({
        ...params,
        tableType : appointmentTable.dataset.tableType
    })
    console.log(appointmentList)
    appointmentBody.replaceChildren()

    if (appointmentList && !appointmentList.length) {
        const row = document.createElement('tr');
        const cell = document.createElement('td');

        cell.colSpan = appointmentBody.closest('table').querySelectorAll('thead th').length;
        cell.className = 'appointment-table__empty';
        cell.textContent = 'Chưa có dữ liệu lịch khám.';

        row.appendChild(cell);
        appointmentBody.appendChild(row);

        return;
    }

    const columns = {
        maLichKham: item => `LK${String(item.id).padStart(4, '0')}`,
        benhNhan: item => item.hoTenBenhNhan,
        bacSi: item => item.hoTenBacSi,
        chuyenKhoa: item => item.chuyenKhoa,
        ngayKham: item => item.ngayKham,
        gioKham: item => item.gioKham,
        trangThai: item => item.trangThai
    };

    appointmentList.forEach((appointment) => {

        const row = document.createElement('tr');

        Object.keys(columns).forEach(key => {

            if (checkExistColumn(key)) {
                const cell = document.createElement('td');
                cell.textContent = columns[key](appointment) ?? '—';
                row.appendChild(cell);
            }

        });

        row.dataset.appointmentId = appointment.id;
        appointmentBody.appendChild(row);

    })
}


renderSpecialtyList()
renderUrlValue()
await renderAppointmentList()
if (params.specialty) {
    await renderDoctorList(params.specialty);
}

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

filterBtnSubmit.addEventListener('click', async () => await renderAppointmentList())
filterBtnReset.addEventListener('click', async () => {

    params = {
        specialty: '',
        date: '',
        doctorId: '',
        status: '',
        pageNumber: 1,
    };

    specialtySelectBox.value = '';
    dateInputBox.value = '';

    if (statusSelectBox)
        statusSelectBox.value = '';

    await renderDoctorList('');

    await renderAppointmentList();
});