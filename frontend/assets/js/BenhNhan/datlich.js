import { getDoctorList } from '../api/bacsi.js';
import { getAppointmentList } from '../api/lichkham.js';
import { normalizedTime, getCurrentHour, getDate } from '../helper/time.js'

const specialtySelectBox = document.querySelector('#booking-specialty');
const doctorSelectBox = document.querySelector('#booking-doctor');
const dateSelectBox = document.querySelector('#booking-date');
const reasonText = document.querySelector('#booking-reason');
const bookingForm = document.querySelector('.booking-filter-form');
const submitBookingBtn = document.querySelector('.booking-submit__button');

const morningSlots = document.querySelector('#morning-slots');
const afternoonSlots = document.querySelector('#afternoon-slots');

const specialtyList = window.SPECIALTY_LIST;
const timeList = window.WORKING_TIME_SLOTS;

let value = {
    specialty: specialtySelectBox.value,
    doctorId: doctorSelectBox.value,
    date: dateSelectBox.value,
    time: null,
    reason: reasonText.value,
    tableType: "timeSlots"
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

    doctorSelectBox.innerHTML = ''

    if (!toggleDoctorSelectBox(specialty))
        return;

    const doctorList = await getDoctorList(specialty)

    if (!doctorList)
        return;

    doctorList.forEach((doctor) => doctorSelectBox.append(renderDoctor(doctor)))
}

const renderDoctor = (doctor) => {
    const option = document.createElement('option');

    option.innerText = doctor.hoTen;
    option.value = doctor.id;

    return option
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

const setBookingTime = (e) => {
    value.time = e.target.value;
};

const renderTimeSlots = async () => {

    value.specialty = specialtySelectBox.value
    value.doctorId = doctorSelectBox.value
    value.date = dateSelectBox.value
    value.reason = reasonText.value

    afternoonSlots.innerHTML = ""
    morningSlots.innerHTML = ""
    legalTimeSlot()
    const unavailableList = await getAppointmentList(value);

    let timeSlotNumber = 0;

    timeList.forEach(({start, end}) => {
        timeSlotNumber += 1;
        let sameStart = false;

        if (unavailableList) {
            sameStart = unavailableList.some(({ gioKham }) => normalizedTime(gioKham) == start);
        }

        const timeSlot = renderTimeSlot({ start, end }, sameStart)

        if (timeSlotNumber > 6) {
            afternoonSlots.append(timeSlot)
        } else {
            morningSlots.append(timeSlot)
        }
    })
}

const renderTimeSlot = ({ start, end }, sameStart) => {
    const button = document.createElement('button');

    button.type = 'button';
    button.className = `time-slot ${sameStart
        ? 'time-slot--unavailable'
        : 'time-slot--available'
        }`;

    button.value = start;
    button.dataset.time = start;
    button.textContent = `${start} - ${end}`;
    button.disabled = sameStart;

    button.onclick = setBookingTime

    return button;
}


const legalTimeSlot = () => {
    console.log(value.date == getDate())
    console.log(getDate(), value.date)
    if (value.date == getDate()) {

        if (getCurrentHour() >= 7) {
            document.querySelector('.booking-period.morning').classList.add('unavailable')
        }

        if (getCurrentHour() >= 13) {
            document.querySelector('.booking-period.afternoon').classList.add('unavailable')
        }

    }
}

specialtySelectBox.addEventListener('change', async (e) => {
    value.specialty = e.target.value
    await renderDoctorList(value.specialty)
})
bookingForm.addEventListener('submit', async (e) => {
    e.preventDefault()
    await renderTimeSlots()
})
renderSpecialtyList();
renderDoctorList();


