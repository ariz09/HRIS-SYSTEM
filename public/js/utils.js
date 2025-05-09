// resources/js/utils.js
export function formatDateToInput(dateString) {
    if (!dateString) return '';
    
    // Handle both date and datetime strings
    const datePart = dateString.toString().split(' ')[0];
    const date = new Date(datePart);
    
    if (isNaN(date.getTime())) return '';
    
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    
    return `${year}-${month}-${day}`;
}

export function initializeTabs() {
    const tabEls = document.querySelectorAll('button[data-bs-toggle="tab"]');
    tabEls.forEach(tabEl => {
        new bootstrap.Tab(tabEl);
    });
}