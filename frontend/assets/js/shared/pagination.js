export function initPagination({
    container,
    currentPage = 1,
    totalPages = 1,
    onPageChange
}) {
    const prevBtn = container.querySelector('[data-page-prev]');
    const nextBtn = container.querySelector('[data-page-next]');
    const pageInfo = container.querySelector('[data-page-info]');

    function render() {
        pageInfo.textContent = `Trang ${currentPage} / ${totalPages > 0 ? totalPages : 1}`;

        prevBtn.disabled = currentPage <= 1;
        nextBtn.disabled = currentPage >= totalPages;
    }

    prevBtn.onclick = async () => {
        if (currentPage <= 1) return;

        currentPage--;

        render();

        await onPageChange?.(currentPage);
    };

    nextBtn.onclick = async () => {
        if (currentPage >= totalPages) return;

        currentPage++;

        render();

        await onPageChange?.(currentPage);
    };

    render();

    return {
        update(newCurrentPage, newTotalPages) {
            currentPage = newCurrentPage;
            totalPages = newTotalPages;

            render();
        },

        getCurrentPage() {
            return currentPage;
        }
    };
}