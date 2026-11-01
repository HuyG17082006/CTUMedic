export const getDoctorList = async (specialty) => {

    if (!specialty) return;
    
    try {
        const request = await fetch(
            `/dashboard/CTUMedic/backend/api/BacSi/lay_danh_sach.php?specialty=${encodeURIComponent(specialty)}`
        );

        if (!request.ok) {
            throw new Error('Lỗi khi lấy danh sách bác sĩ');
        }

        const result = await request.json();

        if (result.success)
            return result.list

    } catch (error) {
        console.error('Lỗi khi lấy danh sách bác sĩ:', error);
    }
};