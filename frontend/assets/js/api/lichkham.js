import apiRequest from "./apiRequest.js";

export const getAppointmentList = async (params) => {
    const queryParams = params instanceof URLSearchParams
        ? params
        : new URLSearchParams(params);

    const result = await apiRequest(`/LichKham/lay_lich_kham.php?${queryParams.toString()}`);

    return result
}

export const getDoctorSchedule = async (values) => {

    const queryParams = values instanceof URLSearchParams
        ? values
        : new URLSearchParams(values);

    const result = await apiRequest(`/LichKham/lay_lich_kham_bac_si.php?${queryParams.toString()}`);
    return result.list
}

export const appointmentBook = async (values) => {

    const result = await apiRequest(`/LichKham/dat_lich.php`, {
        method: "POST",
        body: JSON.stringify(values)
    })

    return result
}

export const getAppointmentDetail = async (id) => {

    const result = await apiRequest(
        `/LichKham/chi_tiet_lich_kham.php?id=${id}`
    );

    return result.data;
}

export const cancelAppointment = async (appointmentId, cancellationReason) => {

    const result = await apiRequest(
        '/LichKham/huy_lich_kham.php',
        {
            method: 'POST',
            body: JSON.stringify({
                appointmentId,
                cancellationReason
            })
        }
    );

    return result;
}

export const completeAppointment = async (appointmentId) => {

    const result = await apiRequest(
        '/LichKham/hoan_thanh.php',
        {
            method: 'POST',
            body: JSON.stringify({
                appointmentId
            })
        }
    );

    return result;
}

export const rescheduleAppointment = async (appointmentId, date, time) => {
    console.log(appointmentId, date, time)
    const result = await apiRequest(
        '/LichKham/doi_lich_kham.php',
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