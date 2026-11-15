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
-- ==========================

