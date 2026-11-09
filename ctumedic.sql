CREATE DATABASE ctumedic;

USE ctumedic;

CREATE TABLE taiKhoan (
    id CHAR(36) PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    passwordHash VARCHAR(255) NOT NULL,
    isActive BOOLEAN NOT NULL DEFAULT TRUE,
    role ENUM('manager', 'doctor', 'patient') DEFAULT 'patient' NOT NULL
);

CREATE TABLE benhNhan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    taiKhoanId CHAR(36) NOT NULL UNIQUE,
    hoTen VARCHAR(100) NOT NULL,
    ngaySinh DATE,
    email VARCHAR(255) UNIQUE,
    SDT VARCHAR(20) UNIQUE,
    CCCD VARCHAR(12) UNIQUE,

    FOREIGN KEY (taiKhoanId) REFERENCES taiKhoan(id) ON DELETE CASCADE
);

CREATE TABLE bacSi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    taiKhoanId CHAR(36) NOT NULL UNIQUE,
    hoTen VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    chuyenKhoa VARCHAR(100) NOT NULL,

    FOREIGN KEY (taiKhoanId) REFERENCES taiKhoan(id) ON DELETE CASCADE
);

CREATE TABLE lichKham (
    id INT AUTO_INCREMENT PRIMARY KEY,

    bacSiId INT NOT NULL,
    benhNhanId INT NULL,

    ngayKham DATE NOT NULL,
    gioKham TIME NOT NULL,

    liDoKham VARCHAR(500) NULL,

    trangThai ENUM(
        'CONFIRMED',
        'CANCELLED',
        'COMPLETED'
    ) NOT NULL DEFAULT 'CONFIRMED',

    cancelledBy ENUM(
        'PATIENT',
        'DOCTOR'
    ) NULL,

    cancelledAt DATETIME NULL,

    cancellationType ENUM(
        'CANCELLATION',
        'RESCHEDULE'
    ) NULL,

    cancellationReason VARCHAR(500) NULL,

    rescheduledFromId INT NULL,

    createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (bacSiId)
        REFERENCES bacSi(id)
        ON DELETE RESTRICT,

    FOREIGN KEY (benhNhanId)
        REFERENCES benhNhan(id)
        ON DELETE SET NULL,

    FOREIGN KEY (rescheduledFromId)
        REFERENCES lichKham(id)
        ON DELETE SET NULL,

    INDEX idx_lichKham_bacSi_ngay_gio (
        bacSiId,
        ngayKham,
        gioKham
    ),

    INDEX idx_lichKham_benhNhan (
        benhNhanId,
        ngayKham
    ),

    INDEX idx_lichKham_trangThai (
        trangThai,
        ngayKham
    ),

    INDEX idx_lichKham_rescheduledFrom (
        rescheduledFromId
    )
);

-- ==========================
-- TAI KHOAN
-- ==========================

INSERT INTO taiKhoan(id, username, passwordHash, role)
VALUES
('11111111-1111-1111-1111-111111111111','bn001','$2a$10$hash1','patient'),
('22222222-2222-2222-2222-222222222222','bn002','$2a$10$hash2','patient'),
('33333333-3333-3333-3333-333333333333','bn003','$2a$10$hash3','patient'),
('44444444-4444-4444-4444-444444444444','bn004','$2a$10$hash4','patient'),

('55555555-5555-5555-5555-555555555555','bs001','$2a$10$hash5','doctor'),
('66666666-6666-6666-6666-666666666666','bs002','$2a$10$hash6','doctor'),
('77777777-7777-7777-7777-777777777777','bs003','$2a$10$hash7','doctor'),
('88888888-8888-8888-8888-888888888888','bs004','$2a$10$hash8','doctor');

-- ==========================
-- BENH NHAN
-- ==========================

INSERT INTO benhNhan
(taiKhoanId, hoTen, ngaySinh, email, SDT, CCCD)
VALUES
('11111111-1111-1111-1111-111111111111','Nguyễn Văn An','1998-04-15','an@gmail.com','0901000001','123456789001'),
('22222222-2222-2222-2222-222222222222','Trần Thị Bình','1992-09-20','binh@gmail.com','0901000002','123456789002'),
('33333333-3333-3333-3333-333333333333','Lê Minh Cường','1985-01-10','cuong@gmail.com','0901000003','123456789003'),
('44444444-4444-4444-4444-444444444444','Phạm Thu Dung','2000-07-25','dung@gmail.com','0901000004','123456789004');

-- ==========================
-- BAC SI
-- ==========================

INSERT INTO bacSi
(taiKhoanId, hoTen, email, chuyenKhoa)
VALUES
('55555555-5555-5555-5555-555555555555',
'BS Nguyễn Hoàng Long',
'long@ctumedic.vn',
'Nội tổng quát'),

('66666666-6666-6666-6666-666666666666',
'BS Trần Quang Huy',
'huy@ctumedic.vn',
'Tim mạch'),

('77777777-7777-7777-7777-777777777777',
'BS Lê Ngọc Mai',
'mai@ctumedic.vn',
'Nhi khoa'),

('88888888-8888-8888-8888-888888888888',
'BS Võ Minh Khang',
'khang@ctumedic.vn',
'Da liễu');

INSERT INTO lichKham
(bacSiId,benhNhanId,ngayKham,gioKham,liDoKham,trangThai)
VALUES
(1,1,'2026-11-09','07:00:00','Khám tổng quát','COMPLETED'),
(2,2,'2026-11-09','08:00:00','Đau ngực','COMPLETED'),
(3,3,'2026-11-09','09:00:00','Khám cho trẻ em','COMPLETED'),
(4,4,'2026-11-09','10:00:00','Dị ứng da','COMPLETED'),
(1,2,'2026-11-09','13:00:00','Đau đầu kéo dài','COMPLETED'),
(2,3,'2026-11-09','14:00:00','Kiểm tra huyết áp','COMPLETED'),
(3,4,'2026-11-09','15:00:00','Cảm sốt','COMPLETED'),
(4,1,'2026-11-09','16:00:00','Nổi mẩn đỏ','COMPLETED');

INSERT INTO lichKham
(bacSiId,benhNhanId,ngayKham,gioKham,liDoKham,trangThai)
VALUES
(1,3,'2026-11-10','07:00:00','Khám sức khỏe','COMPLETED'),
(2,4,'2026-11-10','08:00:00','Khó thở nhẹ','COMPLETED'),
(3,1,'2026-11-10','09:00:00','Viêm họng','COMPLETED'),
(4,2,'2026-11-10','10:00:00','Nấm da','COMPLETED'),
(1,4,'2026-11-10','13:00:00','Rối loạn tiêu hóa','COMPLETED'),
(2,1,'2026-11-10','14:00:00','Tái khám tim mạch','COMPLETED'),
(3,2,'2026-11-10','15:00:00','Sốt nhẹ','COMPLETED'),
(4,3,'2026-11-10','16:00:00','Khô da','COMPLETED');

INSERT INTO lichKham
(bacSiId,benhNhanId,ngayKham,gioKham,liDoKham,trangThai)
VALUES
(1,1,'2026-11-11','07:00:00','Đau bụng','COMPLETED'),
(2,2,'2026-11-11','08:00:00','Theo dõi huyết áp','COMPLETED'),
(3,3,'2026-11-11','09:00:00','Cảm cúm','COMPLETED'),
(4,4,'2026-11-11','10:00:00','Mề đay','COMPLETED'),
(1,2,'2026-11-11','13:00:00','Thiếu ngủ','COMPLETED'),
(2,3,'2026-11-11','14:00:00','Đau tim nhẹ','COMPLETED'),
(3,4,'2026-11-11','15:00:00','Ho kéo dài','COMPLETED'),
(4,1,'2026-11-11','16:00:00','Viêm da cơ địa','COMPLETED');

INSERT INTO lichKham
(bacSiId,benhNhanId,ngayKham,gioKham,liDoKham,trangThai)
VALUES
(1,4,'2026-11-12','07:00:00','Khám tổng quát','CONFIRMED'),
(2,1,'2026-11-12','08:00:00','Kiểm tra tim mạch','CONFIRMED'),
(3,2,'2026-11-12','09:00:00','Sốt virus','CONFIRMED'),
(4,3,'2026-11-12','10:00:00','Viêm da','CONFIRMED'),
(1,3,'2026-11-12','13:00:00','Đầy bụng','CONFIRMED'),
(2,4,'2026-11-12','14:00:00','Theo dõi huyết áp','CONFIRMED'),
(3,1,'2026-11-12','15:00:00','Đau họng','CONFIRMED'),
(4,2,'2026-11-12','16:00:00','Dị ứng mỹ phẩm','CONFIRMED');

INSERT INTO lichKham
(bacSiId,benhNhanId,ngayKham,gioKham,liDoKham,trangThai)
VALUES
(1,2,'2026-11-13','07:00:00','Kiểm tra dạ dày','CONFIRMED'),
(2,3,'2026-11-13','08:00:00','Đau ngực','CONFIRMED'),
(3,4,'2026-11-13','09:00:00','Viêm họng','CONFIRMED'),
(4,1,'2026-11-13','10:00:00','Ngứa da','CONFIRMED'),
(1,1,'2026-11-13','13:00:00','Khám định kỳ','CONFIRMED'),
(2,2,'2026-11-13','14:00:00','Kiểm tra ECG','CONFIRMED'),
(3,3,'2026-11-13','15:00:00','Cảm lạnh','CONFIRMED'),
(4,4,'2026-11-13','16:00:00','Mụn dị ứng','CONFIRMED');

INSERT INTO lichKham
(bacSiId,benhNhanId,ngayKham,gioKham,liDoKham,trangThai)
VALUES
(1,3,'2026-11-14','07:00:00','Khám sức khỏe tổng quát','CONFIRMED'),
(2,4,'2026-11-14','08:00:00','Huyết áp cao','CONFIRMED'),
(3,1,'2026-11-14','09:00:00','Viêm amidan','CONFIRMED'),
(4,2,'2026-11-14','10:00:00','Nổi mụn','CONFIRMED'),
(1,4,'2026-11-14','13:00:00','Đau dạ dày','CONFIRMED'),
(2,1,'2026-11-14','14:00:00','Tái khám','CONFIRMED'),
(3,2,'2026-11-14','15:00:00','Sốt','CONFIRMED'),
(4,3,'2026-11-14','16:00:00','Viêm da tiếp xúc','CONFIRMED');

UPDATE lichKham
SET trangThai='CANCELLED',
    cancelledBy='PATIENT',
    cancelledAt='2026-11-08 10:15:00',
    cancellationType='CANCELLATION',
    cancellationReason='Bận công việc đột xuất'
WHERE id=28;

UPDATE lichKham
SET trangThai='CANCELLED',
    cancelledBy='DOCTOR',
    cancelledAt='2026-11-12 07:30:00',
    cancellationType='CANCELLATION',
    cancellationReason='Bác sĩ tham gia hội chẩn'
WHERE id=37;

UPDATE lichKham
SET trangThai='CANCELLED',
    cancelledBy='PATIENT',
    cancelledAt='2026-11-11 18:00:00',
    cancellationType='RESCHEDULE',
    cancellationReason='Có việc gia đình'
WHERE id=30;

INSERT INTO lichKham
(
    bacSiId,
    benhNhanId,
    ngayKham,
    gioKham,
    liDoKham,
    trangThai,
    rescheduledFromId
)
VALUES
(
    2,
    3,
    '2026-11-15',
    '09:00:00',
    'Đau ngực',
    'CONFIRMED',
    30
);