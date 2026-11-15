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
        'COMPLETED',
        'NO_SHOW'
    ) NOT NULL DEFAULT 'CONFIRMED',

    cancelledBy ENUM(
        'PATIENT',
        'DOCTOR',
        'MANAGER'
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
-- Password mặc định: 123456
-- ==========================
INSERT INTO taiKhoan (id, username, passwordHash, role) VALUES
('550e8400-e29b-41d4-a716-446655440001', 'manager01', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'manager'),
('550e8400-e29b-41d4-a716-446655440003', 'patient01', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'patient'),
('550e8400-e29b-41d4-a716-446655440011', 'doctor01', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'doctor'),
('550e8400-e29b-41d4-a716-446655440012', 'doctor02', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'doctor'),
('550e8400-e29b-41d4-a716-446655440013', 'doctor03', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'doctor'),
('550e8400-e29b-41d4-a716-446655440014', 'doctor04', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'doctor'),
('550e8400-e29b-41d4-a716-446655440015', 'doctor05', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'doctor');
-- ==========================
-- THONG TIN BAC SI
-- ==========================
INSERT INTO bacSi (taiKhoanId, hoTen, email, chuyenKhoa) VALUES
(
    '550e8400-e29b-41d4-a716-446655440011',
    'Nguyễn Minh Tuấn',
    'doctor01@ctumedic.vn',
    'Tim mạch'
),
(
    '550e8400-e29b-41d4-a716-446655440012',
    'Trần Thị Hương',
    'doctor02@ctumedic.vn',
    'Nội khoa'
),
(
    '550e8400-e29b-41d4-a716-446655440013',
    'Lê Hoàng Nam',
    'doctor03@ctumedic.vn',
    'Nhi khoa'
),
(
    '550e8400-e29b-41d4-a716-446655440014',
    'Phạm Quốc Bảo',
    'doctor04@ctumedic.vn',
    'Da liễu'
),
(
    '550e8400-e29b-41d4-a716-446655440015',
    'Võ Thanh Tùng',
    'doctor05@ctumedic.vn',
    'Nội tổng quát'
);

-- ==========================
-- THONG TIN BENH NHAN
-- ==========================
INSERT INTO benhNhan (
    taiKhoanId,
    hoTen,
    ngaySinh,
    email,
    SDT,
    CCCD
)
VALUES (
    '550e8400-e29b-41d4-a716-446655440003',
    'Trần Thanh Bình',
    '2000-05-15',
    'patient01@gmail.com',
    '0912345678',
    '079200001234'
);


-- 1. CONFIRMED (đã qua ngày khám)
INSERT INTO lichKham (
    bacSiId, benhNhanId, ngayKham, gioKham,
    liDoKham, trangThai
)
VALUES (
    1, 1,
    '2026-11-14', '08:00:00',
    'Khám tim định kỳ',
    'CONFIRMED'
);

-- 2. CONFIRMED (sắp tới)
INSERT INTO lichKham (
    bacSiId, benhNhanId, ngayKham, gioKham,
    liDoKham, trangThai
)
VALUES (
    2, 1,
    '2026-11-20', '09:00:00',
    'Đau dạ dày',
    'CONFIRMED'
);

-- 3. COMPLETED
INSERT INTO lichKham (
    bacSiId, benhNhanId, ngayKham, gioKham,
    liDoKham, trangThai
)
VALUES (
    3, 1,
    '2026-11-10', '14:00:00',
    'Khám nhi khoa',
    'COMPLETED'
);

-- 4. NO_SHOW
INSERT INTO lichKham (
    bacSiId, benhNhanId, ngayKham, gioKham,
    liDoKham, trangThai
)
VALUES (
    4, 1,
    '2026-11-11', '15:00:00',
    'Khám da liễu',
    'NO_SHOW'
);

-- 5. CANCELLED bởi PATIENT
INSERT INTO lichKham (
    bacSiId, benhNhanId, ngayKham, gioKham,
    liDoKham,
    trangThai,
    cancelledBy,
    cancelledAt,
    cancellationType,
    cancellationReason
)
VALUES (
    5, 1,
    '2026-11-22', '10:00:00',
    'Khám tổng quát',
    'CANCELLED',
    'PATIENT',
    '2026-11-18 08:15:00',
    'CANCELLATION',
    'Bận công tác'
);

-- 6. CANCELLED bởi DOCTOR
INSERT INTO lichKham (
    bacSiId, benhNhanId, ngayKham, gioKham,
    liDoKham,
    trangThai,
    cancelledBy,
    cancelledAt,
    cancellationType,
    cancellationReason
)
VALUES (
    1, 1,
    '2026-11-24', '08:30:00',
    'Tái khám tim mạch',
    'CANCELLED',
    'DOCTOR',
    '2026-11-22 07:00:00',
    'CANCELLATION',
    'Bác sĩ có lịch họp đột xuất'
);

-- 7. CANCELLED bởi MANAGER
INSERT INTO lichKham (
    bacSiId, benhNhanId, ngayKham, gioKham,
    liDoKham,
    trangThai,
    cancelledBy,
    cancelledAt,
    cancellationType,
    cancellationReason
)
VALUES (
    2, 1,
    '2026-11-26', '13:30:00',
    'Khám nội khoa',
    'CANCELLED',
    'MANAGER',
    '2026-11-24 09:00:00',
    'CANCELLATION',
    'Bảo trì hệ thống phòng khám'
);

-- 8. LỊCH GỐC BỊ DỜI
INSERT INTO lichKham (
    id,
    bacSiId,
    benhNhanId,
    ngayKham,
    gioKham,
    liDoKham,
    trangThai,
    cancelledBy,
    cancelledAt,
    cancellationType,
    cancellationReason
)
VALUES (
    100,
    3,
    1,
    '2026-11-28',
    '09:00:00',
    'Khám nhi tái khám',
    'CANCELLED',
    'DOCTOR',
    '2026-11-25 10:20:00',
    'RESCHEDULE',
    'Bác sĩ đổi lịch làm việc'
);

-- 9. LỊCH MỚI SAU KHI DỜI
INSERT INTO lichKham (
    bacSiId,
    benhNhanId,
    ngayKham,
    gioKham,
    liDoKham,
    trangThai,
    rescheduledFromId
)
VALUES (
    3,
    1,
    '2026-12-02',
    '09:00:00',
    'Khám nhi tái khám',
    'CONFIRMED',
    100
);