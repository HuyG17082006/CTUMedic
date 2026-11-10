import { params } from "./state.js";

import {
    renderUrlValue,
    renderSpecialtyList,
    renderDoctorList,
    bindFilterEvents
} from "./appointmentFilter.js";

import {
    renderAppointmentList
} from "./appointmentTable.js";

const init = async () => {

    renderSpecialtyList();

    renderUrlValue();

    if (params.specialty) {
        await renderDoctorList(
            params.specialty
        );
    }

    await renderAppointmentList();

    bindFilterEvents();
};

await init();