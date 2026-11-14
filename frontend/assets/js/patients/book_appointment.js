import { getFilterDoctorList } from '../api/doctors.js';
import { getDoctorSchedule, createAppointment } from '../api/appointments.js';
import finishedAction from '../helper/finished_action.js';
import { renderSchedulePeriods } from '../shared/schedule_periods.js';
import { SPECIALTY_LIST } from '../helper/base.js'

const specialtySelectBox = document.querySelector('#booking-specialty');
const doctorSelectBox = document.querySelector('#booking-doctor');
const dateSelectBox = document.querySelector('#booking-date');
const reasonText = document.querySelector('#booking-reason');
const bookingForm = document.querySelector('.booking-filter-form');
const submitBookingBtn = document.querySelector('.booking-submit__button');

let values = {
    specialty: specialtySelectBox.value,
    doctorId: doctorSelectBox.value,
    date: dateSelectBox.value,
    time: null,
    reason: '',
    tableType: "timeSlots"
}

const hasEmptyField = (obj) => {
    return Object.values(obj).some(
        value =>
            value == null ||
            (typeof value === 'string' && value.trim() === '')
    );
};

const renderSpecialtyList = () => {

    SPECIALTY_LIST.forEach((specialty) => {

        const option = document.createElement('option');

        option.innerText = specialty;
        option.value = specialty;

        specialtySelectBox.append(option)
    })

}
const renderDoctorList = async (specialty) => {


    if (!toggleDoctorSelectBox(specialty))
        return;

    const doctorList = await getFilterDoctorList(specialty)

    if (!doctorList)
        return;
    console.log(doctorList)
    doctorList.forEach((doctor) => doctorSelectBox.append(renderDoctor(doctor)))
}

const renderDoctor = (doctor) => {
    const option = document.createElement('option');

    option.innerText = doctor.hoTen;
    option.value = doctor.id;

    return option
}

const toggleDoctorSelectBox = (specialty) => {
    doctorSelectBox.innerHTML = ''
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

const selectBookingTime = (time) => {
    values.time = time;
};

const renderTimeSlots = async () => {
    const unavailableList = await getDoctorSchedule(values);

    renderSchedulePeriods(unavailableList, selectBookingTime, values)
}

const booking = async () => {

    if (hasEmptyField(values))
        return alert("Vui lòng nhập đầy đủ thông tin")

    const { status, message } = await createAppointment(values)
    finishedAction(status, message)
}

dateSelectBox.addEventListener('change', async (e) => {
    values.date = e.target.value
})
doctorSelectBox.addEventListener('change', async (e) => {
    values.doctorId = e.target.value
})
specialtySelectBox.addEventListener('change', async (e) => {
    values.specialty = e.target.value;
    values.doctorId = '';
    values.time = null;
    await renderDoctorList(values.specialty);
});
bookingForm.addEventListener('submit', async (e) => {
    e.preventDefault()
    await renderTimeSlots()
})
reasonText.addEventListener('input', (e) => values.reason = e.target.value)
submitBookingBtn.addEventListener('click', booking)

const render = async () => {
    renderSpecialtyList();
    if (values.specialty)
        await renderDoctorList(values.specialty);
}

await render();

