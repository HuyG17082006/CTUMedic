import { getAppointmentList } from "../../api/lichkham.js";
import { params, changeUrl } from "./state.js";
import { createAppointmentRow } from "./appointmentRow.js";
import { initPagination } from "../pagination.js";

const appointmentTable =
    document.querySelector('.appointment-table');

const appointmentBody =
    appointmentTable.querySelector(
        '[data-appointment-list]'
    );

const paginationElement =
    document.querySelector('[data-pagination]');

const appointmentColumns = [
    'maLichKham',
    'benhNhan',
    'bacSi',
    'chuyenKhoa',
    'ngayKham',
    'gioKham',
    'trangThai'
];

export const renderAppointmentList = async () => {

    changeUrl();

    const tableType =
        appointmentTable.dataset.tableType;

    const role =
        appointmentTable.dataset.role;

    const data =
        await getAppointmentList({
            ...params,
            tableType
        });

    const appointmentList =
        data.list ?? [];

    const columns =
        appointmentColumns.filter(
            checkExistColumn
        );

    const fragment =
        document.createDocumentFragment();

    if (appointmentList.length === 0) {

        const row =
            document.createElement('tr');

        const cell =
            document.createElement('td');

        cell.colSpan =
            columns.length + 1;

        cell.className =
            'appointment-table__empty';

        cell.textContent =
            'Chưa có dữ liệu lịch khám.';

        row.appendChild(cell);

        fragment.appendChild(row);

    } else {

        appointmentList.forEach(
            appointment => {

                fragment.appendChild(
                    createAppointmentRow(
                        appointment,
                        columns,
                        tableType,
                        role,
                        renderAppointmentList
                    )
                );
            }
        );
    }

    appointmentBody.replaceChildren(
        fragment
    );

    pagination.update(
        data.pagination.pageNumber,
        data.pagination.totalPages
    );
};

const checkExistColumn = key => {

    return (
        appointmentTable.querySelector(
            `thead th[data-column="${key}"]`
        ) !== null
    );
};

const pagination = initPagination({
    container: paginationElement,

    async onPageChange(page) {

        params.pageNumber = page;

        await renderAppointmentList();
    }
});
