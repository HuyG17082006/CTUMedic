import { getDoctorList } from "../../api/bacsi.js";
import { renderAppointmentList } from "./appointmentTable.js";
import {
    params,
    resetParams
} from "./state.js";

const specialtySelectBox = document.querySelector('.specialty-select-box');
const doctorSelectBox = document.querySelector('.doctor-select-box');
const dateInputBox = document.querySelector('.date-input-box');
const statusSelectBox = document.querySelector('.status-select-box');
const filterBtnSubmit = document.querySelector('.appointment-filter__submit');
const filterBtnReset = document.querySelector('.appointment-filter__reset');

const specialtyList = window.SPECIALTY_LIST;

export const renderUrlValue = () => {
    dateInputBox.value = params.date;
    doctorSelectBox.value = params.doctorId;
    specialtySelectBox.value = params.specialty;

    if (statusSelectBox) {
        statusSelectBox.value = params.status;
    }
};

export const renderSpecialtyList = () => {
    specialtyList.forEach(specialty => {
        const option = document.createElement('option');

        option.value = specialty;
        option.textContent = specialty;

        specialtySelectBox.append(option);
    });
};

export const renderDoctorList = async (specialty) => {

    if (!toggleDoctorSelectBox(specialty))
        return;

    const doctorList = await getDoctorList(specialty);

    if (doctorList) {
        doctorList.forEach(doctor => {
            doctorSelectBox.append(
                renderDoctor(doctor)
            );
        });
    }

    if (params.doctorId) {
        doctorSelectBox.value = params.doctorId;
    }
};

const renderDoctor = (doctor) => {
    const option = document.createElement('option');

    option.innerText = doctor.hoTen;
    option.value = doctor.id;

    return option;
};

const toggleDoctorSelectBox = (specialty) => {

    doctorSelectBox.innerHTML = '';

    if (specialty) {

        doctorSelectBox.disabled = false;
        const option = document.createElement('option');
        option.value = '';
        option.innerText = 'Chọn bác sĩ';
        doctorSelectBox.append(option);

        return true;
    }

    const option = document.createElement('option');

    option.value = '';
    option.innerText = 'Chọn chuyên khoa trước';
    option.selected = true;
    doctorSelectBox.disabled = true;

    doctorSelectBox.append(option);

    return false;
};

export const bindFilterEvents = () => {

    dateInputBox.addEventListener(
        'change',
        (e) => {
            params.date = e.target.value;
        }
    );

    doctorSelectBox.addEventListener(
        'change',
        (e) => {
            params.doctorId = e.target.value;
        }
    );

    if (statusSelectBox) {
        statusSelectBox.addEventListener(
            'change',
            (e) => {
                params.status = e.target.value;
            }
        );
    }

    specialtySelectBox.addEventListener(
        'change',
        async (e) => {

            params.specialty = e.target.value;
            params.doctorId = '';

            doctorSelectBox.value = '';

            await renderDoctorList(
                params.specialty
            );
        }
    );

    filterBtnSubmit.addEventListener(
        'click',
        async () => {

            params.pageNumber = 1;

            await renderAppointmentList();
        }
    );

    filterBtnReset.addEventListener(
        'click',
        async () => {

            resetParams();

            specialtySelectBox.value = '';
            dateInputBox.value = '';

            if (statusSelectBox) {
                statusSelectBox.value = '';
            }

            await renderDoctorList('');

            await renderAppointmentList();
        }
    );
};