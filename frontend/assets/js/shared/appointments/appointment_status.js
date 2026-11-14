export const isWaitingForUpdate = (appointment) => {
    if (appointment.trangThai === 'WAITING_UPDATE')
        return true;

    if (appointment.trangThai !== 'CONFIRMED')
        return false;

    const appointmentTime = new Date(
        `${appointment.ngayKham}T${appointment.gioKham}`
    );

    appointmentTime.setMinutes(
        appointmentTime.getMinutes() + 40
    );

    return new Date() > appointmentTime;
};
