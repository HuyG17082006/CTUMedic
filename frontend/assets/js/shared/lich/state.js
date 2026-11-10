const currentUrlParams =
    new URL(window.location.href).searchParams;

export const params = {
    specialty:
        currentUrlParams.get('specialty') || '',

    doctorId:
        currentUrlParams.get('doctorId') || '',

    date:
        currentUrlParams.get('date') || '',

    status:
        currentUrlParams.get('status') || '',

    pageNumber:
        Number(currentUrlParams.get('pageNumber')) || 1,
};

export const changeUrl = () => {

    const url =
        new URL(window.location.href);

    Object.entries(params).forEach(
        ([key, value]) => {

            if (value)
                url.searchParams.set(
                    key,
                    value
                );
            else
                url.searchParams.delete(
                    key
                );
        }
    );

    window.history.replaceState(
        {},
        '',
        url.toString()
    );
};

export const resetParams = () => {

    state.specialty = '';
    state.doctorId = '';
    state.date = '';
    state.status = '';
    state.pageNumber = 1;
};