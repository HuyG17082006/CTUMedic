export const getAppointmentList = async (params) => {
    try {
        const queryParams = params instanceof URLSearchParams
            ? params
            : new URLSearchParams(params);
        console.log(params)
        const response = await fetch(
            `/dashboard/CTUMedic/backend/api/LichKham/lay_lich_kham.php?${queryParams.toString()}`,
            {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            }
        );

        const result = await response.json();

        if (!response.ok) {
            throw new Error(
                result.message || 'Không thể lấy danh sách lịch khám.'
            );
        }

        return result.list;

    } catch (error) {
        console.error('Lỗi lấy danh sách lịch khám:', error);
        return null;
    }
}