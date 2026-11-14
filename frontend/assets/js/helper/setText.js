export const setText = (modal, selector, value) => {
    const element = modal.querySelector(selector);
    console.log(element)
    if (element) element.textContent = value ?? '—';
};

export const setFields = (modal, fields) =>
    Object.entries(fields).forEach(([selector, value]) =>
        setText(modal, selector, value)
    );