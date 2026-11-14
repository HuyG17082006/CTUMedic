import apiRequest from "./api_request.js";

export const getAppointmentList = async (params) => {
    const queryParams = params instanceof URLSearchParams
        ? params
        : new URLSearchParams(params);

    const result = await apiRequest(`/appointments/get_appointments.php?${queryParams.toString()}`);

    return result
}

export const getDoctorSchedule = async (values) => {

    const queryParams = values instanceof URLSearchParams
        ? values
        : new URLSearchParams(values);

    const result = await apiRequest(`/appointments/get_doctor_appointments.php?${queryParams.toString()}`);
    return result.list
}

export const createAppointment = async (values) => {

    const result = await apiRequest(`/appointments/create_appointment.php`, {
        method: "POST",
        body: JSON.stringify(values)
    })

    return result
}

export const getAppointmentDetail = async (id) => {

    const result = await apiRequest(
        `/appointments/get_appointment_details.php?id=${id}`
    );

    return result.data;
}

export const cancelAppointment = async (appointmentId, cancellationReason) => {

    return await apiRequest(
        '/appointments/cancel_appointment.php',
        {
            method: 'POST',
            body: JSON.stringify({
                appointmentId,
                cancellationReason
            })
        }
    );

}

export const completeAppointment = async (appointmentId) => {

    return await apiRequest(
        '/appointments/complete_appointment.php',
        {
            method: 'POST',
            body: JSON.stringify({
                appointmentId
            })
        }
    );

}

export const noShowAppointment = async (appointmentId) => {
    return await apiRequest(
        '/appointments/mark_no_show.php',
        {
            method: 'POST',
            body: JSON.stringify({
                appointmentId
            })
        }
    );
};

export const rescheduleAppointment = async (appointmentId, date, time) => {

    const result = await apiRequest(
        '/appointments/reschedule_appointment.php',
        {
            method: 'POST',
            body: JSON.stringify({
                appointmentId,
                ngayKham: date,
                gioKham: time
            })
        }
    );

    return result;

}