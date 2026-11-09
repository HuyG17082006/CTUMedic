Bối cảnh hiện tại:
- Số role: patient, doctor, manager
- Chức năng : hủy lịch khám
+ đã biết : lịch khám là có định, được chia ra 2 buổi sáng chiều và mỗi buổi là 6 ca
+ ở phía bệnh nhân : bệnh nhân có thể đặt lịch ngay và gán là confirmed nếu lịch dặt hợp lệ
+ Bệnh nhân hủy -> cancelled
+ Bác sĩ hủy -> nếu giờ đó có bệnh nhân đặt -> chuyển sang cancelled
             -> Nếu giờ đó chưa có ai đặt (chưa xuát hiện trong db) -> tạo với benhNhanId trống và trạng thái cancelled