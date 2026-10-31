import { getDoctorList } from '../api/bacsi.js';
import { getAppointmentList } from '../api/lichkham.js';
import { normalizedTime, getCurrentHour, getDate } from '../helper/time.js'

const specialtySelectBox = document.querySelector('#booking-specialty');
const doctorSelectBox = document.querySelector('#booking-doctor');
const dateSelectBox = document.querySelector('#booking-date');
const reasonText = document.querySelector('#booking-reason');
const submitBtn = document.querySelector('.booking-filter-form__submit');

const morningSlots = document.querySelector('#morning-slots');
const afternoonSlots = document.querySelector('#afternoon-slots');

const specialtyList = window.SPECIALTY_LIST;
const timeList = window.WORKING_TIME_SLOTS;

let value = {
    specialty: specialtySelectBox.value,
    doctorId: doctorSelectBox.value,
    date: dateSelectBox.value,
    reason: reasonText.value,
    tableType: "timeSlots"
}
console.log(value)
const renderSpecialtyList = () => {
    specialtyList.forEach((specialty) => {

        const option = document.createElement('option');

        option.innerText = specialty;
        option.value = specialty;

        specialtySelectBox.append(option)

    })
}

const renderDoctorList = async (specialty) => {

    const doctorList = await getDoctorList(specialty)

    if (!doctorList)
        return;

    doctorSelectBox.innerHTML = ''

    doctorList.forEach((doctor) => {
        const option = document.createElement('option');

        option.innerText = doctor.hoTen;
        option.value = doctor.id;

        doctorSelectBox.append(option)
    })
}

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

    timeList.forEach(({ start, end }) => {
        timeSlotNumber += 1;
        let sameStart = false;

        if (unavailableList) {
            sameStart = unavailableList.some(({ gioKham }) => normalizedTime(gioKham) == start);
        }

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

        if (timeSlotNumber > 6) {
            afternoonSlots.append(button)
        } else {
            morningSlots.append(button)
        }
    })
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

specialtySelectBox.addEventListener('change', async (e) => await renderDoctorList(e.target.value))
submitBtn.addEventListener('click', async (e) => await renderTimeSlots())

renderSpecialtyList();

