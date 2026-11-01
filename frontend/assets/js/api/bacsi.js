import apiRequest from "./apiRequest.js";

export const getDoctorList = async (specialty) => {

    if (!specialty) return;
    
    const result = await apiRequest(`/BacSi/lay_danh_sach.php?specialty=${encodeURIComponent(specialty)}`)

    return result.list
};