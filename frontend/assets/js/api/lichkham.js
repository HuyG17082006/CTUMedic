import apiRequest from "./apiRequest.js";

export const getAppointmentList = async (params) => {
    const queryParams = params instanceof URLSearchParams
        ? params
        : new URLSearchParams(params);

    const result = await apiRequest(`/LichKham/lay_lich_kham.php?${queryParams.toString()}`);
  
    return result.list
}

export const getDoctorSchedule = async (values) => {

    const queryParams = values instanceof URLSearchParams
        ? values
        : new URLSearchParams(values);

    const result = await apiRequest(`/LichKham/lay_lich_kham_bac_si.php?${queryParams.toString()}`);
    return result.list
}

export const appointmentBook = async (value) => {

}