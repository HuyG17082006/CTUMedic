import { params } from "./state.js";

import {
    renderUrlValue,
    renderSpecialtyList,
    renderDoctorList,
    bindFilterEvents
} from "./appointment_filter.js";

import {
    renderAppointmentList
} from "./appointment_table.js";

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