

export const normalizedTime = time => time.slice(0, 5);

export const getCurrentHour = () => {
    return new Date().getHours()
}

export const getDate = () => {
    const date = new Date();

    const year = date.getFullYear();
    const month = String(date.getMonth() + 1);
    const day = String(date.getDate());

    return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
};