import { getDoctorList } from '../api/bacsi.js';
import { getDoctorSchedule } from '../api/lichkham.js';
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

let values = {
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
    values.time = e.target.value;
};

const renderTimeSlots = async () => {

    values.specialty = specialtySelectBox.value
    values.doctorId = doctorSelectBox.value
    values.date = dateSelectBox.value
    values.reason = reasonText.value

    afternoonSlots.innerHTML = ""
    morningSlots.innerHTML = ""

    legalTimeSlot()

    const unavailableList = await getDoctorSchedule(values);

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

    if (values.date == getDate()) {

        if (getCurrentHour() >= 7) {
            document.querySelector('.booking-period.morning').classList.add('unavailable')
        }

        if (getCurrentHour() >= 13) {
            document.querySelector('.booking-period.afternoon').classList.add('unavailable')
        }

    } else {
        document.querySelector('.booking-period.morning').classList.remove('unavailable')
        document.querySelector('.booking-period.afternoon').classList.remove('unavailable')
    }
}

specialtySelectBox.addEventListener('change', async (e) => {
    values.specialty = e.target.value
    await renderDoctorList(values.specialty)
})
bookingForm.addEventListener('submit', async (e) => {
    e.preventDefault()
    await renderTimeSlots()
})
renderSpecialtyList();
renderDoctorList();


