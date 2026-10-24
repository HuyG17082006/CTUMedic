
/*
 * DEMO DATA - CTUMedic
 *
 * TODO: XÓA FILE NÀY khi tích hợp API thật.
 * File chỉ dùng để xem thử bảng Lịch khám và Lịch sử khám.
 * Không truy vấn hoặc ghi dữ liệu vào MySQL.
 */

const demoAppointments = [
    {
        id: 1,
        code: "LK0001",
        patient: "Nguyễn Minh Anh",
        patientCode: "BN0001",
        doctor: "Trần Văn Bình",
        doctorCode: "BS0001",
        specialty: "Nội khoa",
        date: "2026-10-25",
        time: "08:00",
        reason: "Đau đầu kéo dài, thường xuyên chóng mặt.",
        status: "PENDING",
        createdAt: "2026-10-23 09:15:00"
    },
    {
        id: 2,
        code: "LK0002",
        patient: "Lê Hoàng Nam",
        patientCode: "BN0002",
        doctor: "Phạm Thu Hà",
        doctorCode: "BS0002",
        specialty: "Tim mạch",
        date: "2026-10-25",
        time: "09:00",
        reason: "Đau tức ngực khi vận động.",
        status: "CONFIRMED",
        createdAt: "2026-10-22 14:30:00"
    },
    {
        id: 3,
        code: "LK0003",
        patient: "Trần Ngọc Mai",
        patientCode: "BN0003",
        doctor: "Nguyễn Quốc Huy",
        doctorCode: "BS0003",
        specialty: "Da liễu",
        date: "2026-10-26",
        time: "10:00",
        reason: "Nổi mẩn đỏ và ngứa trên da.",
        status: "PENDING",
        createdAt: "2026-10-24 08:20:00"
    },
    {
        id: 4,
        code: "LK0004",
        patient: "Phạm Đức Long",
        patientCode: "BN0004",
        doctor: "Trần Văn Bình",
        doctorCode: "BS0001",
        specialty: "Nội khoa",
        date: "2026-10-26",
        time: "14:00",
        reason: "Khám định kỳ và kiểm tra sức khỏe.",
        status: "CANCELLED",
        createdAt: "2026-10-21 11:00:00"
    },
    {
        id: 5,
        code: "LK0005",
        patient: "Võ Thị Thanh",
        patientCode: "BN0005",
        doctor: "Phạm Thu Hà",
        doctorCode: "BS0002",
        specialty: "Tim mạch",
        date: "2026-10-27",
        time: "08:30",
        reason: "Tái khám sau điều trị.",
        status: "REJECTED",
        createdAt: "2026-10-24 10:45:00"
    },
    {
        id: 6,
        code: "LK0006",
        patient: "Nguyễn Minh Anh",
        patientCode: "BN0001",
        doctor: "Trần Văn Bình",
        doctorCode: "BS0001",
        specialty: "Nội khoa",
        date: "2026-10-20",
        time: "08:00",
        reason: "Kiểm tra sức khỏe tổng quát.",
        status: "COMPLETED",
        createdAt: "2026-10-15 09:00:00"
    },
    {
        id: 7,
        code: "LK0007",
        patient: "Lê Hoàng Nam",
        patientCode: "BN0002",
        doctor: "Phạm Thu Hà",
        doctorCode: "BS0002",
        specialty: "Tim mạch",
        date: "2026-10-18",
        time: "09:30",
        reason: "Theo dõi huyết áp định kỳ.",
        status: "COMPLETED",
        createdAt: "2026-10-12 15:20:00"
    },
    {
        id: 8,
        code: "LK0008",
        patient: "Trần Ngọc Mai",
        patientCode: "BN0003",
        doctor: "Nguyễn Quốc Huy",
        doctorCode: "BS0003",
        specialty: "Da liễu",
        date: "2026-10-16",
        time: "10:00",
        reason: "Tái khám tình trạng viêm da.",
        status: "COMPLETED",
        createdAt: "2026-10-10 13:10:00"
    },
    {
        id: 9,
        code: "LK0009",
        patient: "Phạm Đức Long",
        patientCode: "BN0004",
        doctor: "Trần Văn Bình",
        doctorCode: "BS0001",
        specialty: "Nội khoa",
        date: "2026-10-14",
        time: "14:00",
        reason: "Đánh giá kết quả điều trị.",
        status: "COMPLETED",
        createdAt: "2026-10-08 08:45:00"
    }
];

const demoStatusLabels = {
    PENDING: "Chờ xác nhận",
    CONFIRMED: "Đã xác nhận",
    REJECTED: "Bị từ chối",
    CANCELLED: "Đã hủy",
    COMPLETED: "Đã hoàn thành"
};

function demoFormatDate(date) {
    if (!date) return "—";

    const [year, month, day] = date.split("-");
    return `${day}/${month}/${year}`;
}

function demoCreateCell(value, className = "") {
    const td = document.createElement("td");
    td.textContent = value ?? "—";

    if (className) {
        td.className = className;
    }

    return td;
}

function demoRenderTable(table) {
    const tbody = table.querySelector("[data-appointment-list]");

    if (!tbody) return;

    const isHistory = table.dataset.tableType === "history";

    const records = isHistory
        ? demoAppointments.filter(item => item.status === "COMPLETED")
        : demoAppointments;

    const fragment = document.createDocumentFragment();

    if (records.length === 0) {
        const row = document.createElement("tr");
        const cell = demoCreateCell("Không có dữ liệu phù hợp.");

        cell.colSpan = table.querySelectorAll("thead th").length;
        cell.className = "appointment-table__empty";

        row.appendChild(cell);
        fragment.appendChild(row);
    }

    records.forEach(item => {
        const row = document.createElement("tr");

        row.dataset.appointmentRow = "";
        row.dataset.appointmentId = item.id;

        const columns = table.querySelectorAll("thead th");

        columns.forEach(column => {
            const key = column.dataset.column;
            let cell;

            switch (key) {
                case "code":
                    cell = demoCreateCell(item.code);
                    break;

                case "patient":
                    cell = demoCreateCell(item.patient);
                    break;

                case "doctor":
                    cell = demoCreateCell(item.doctor);
                    break;

                case "specialty":
                    cell = demoCreateCell(item.specialty);
                    break;

                case "date":
                    cell = demoCreateCell(demoFormatDate(item.date));
                    break;

                case "time":
                    cell = demoCreateCell(item.time);
                    break;

                case "reason":
                    cell = demoCreateCell(item.reason);
                    break;

                case "status": {
                    cell = document.createElement("td");

                    const badge = document.createElement("span");
                    badge.className =
                        `appointment-status appointment-status--${item.status.toLowerCase()}`;
                    badge.textContent =
                        demoStatusLabels[item.status] ?? item.status;

                    cell.appendChild(badge);
                    break;
                }

                case "actions": {
                    cell = document.createElement("td");

                    const button = document.createElement("button");
                    button.type = "button";
                    button.className = "appointment-row-action";
                    button.textContent = "Chi tiết";
                    button.dataset.appointmentId = item.id;

                    // Ngăn nút Chi tiết kích hoạt sự kiện nhấn hàng.
                    button.addEventListener("click", event => {
                        event.stopPropagation();
                        alert(`Dữ liệu demo: ${item.code}`);
                    });

                    cell.appendChild(button);
                    break;
                }

                default:
                    cell = demoCreateCell("—");
            }

            row.appendChild(cell);
        });

        fragment.appendChild(row);
    });

    tbody.replaceChildren(fragment);

    const page = table.closest(".appointment-page");
    const count = page?.querySelector("[data-result-count]");

    if (count) {
        count.textContent = `${records.length} lịch khám`;
    }
}

document.addEventListener("DOMContentLoaded", () => {
    document
        .querySelectorAll(".appointment-table")
        .forEach(demoRenderTable);
});
