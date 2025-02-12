define(['app', 'util', 'api'], function (app, util) {
    const BILLING = {};
    BILLING.start = 8; // September (0-based index, 8 = Sep)
    BILLING.month_due_date = 7; // 7th of the month

    BILLING.generateBillingMonths = function (type) {
        let billingMonths = [];
        let today = new Date();
        let currentYear = today.getFullYear();
        let startMonth = BILLING.start;

        // If the current month is before September, start from last year's September
        if (today.getMonth() < startMonth) {
            currentYear -= 1;
        }

        let year = currentYear;
        let month = startMonth;

        // Count the billing cycle from September to the current month
        let endYear = today.getFullYear();
        let endMonth = today.getMonth();

        // Check if today is in the last week of the month
        let lastDayOfMonth = new Date(endYear, endMonth + 1, 0);
        let lastWeekStart = new Date(lastDayOfMonth);
        lastWeekStart.setDate(lastDayOfMonth.getDate() - 6);

        // If today is in the last week of the month, include next month
        if (today >= lastWeekStart) {
            endMonth += 1;
            if (endMonth > 11) {
                endMonth = 0;
                endYear += 1;
            }
        }

        // Generate billing months dynamically
        while (year < endYear || (year === endYear && month <= endMonth)) {
            let dueDate = new Date(year, month, BILLING.month_due_date);
            let monthAbbrev = dueDate.toLocaleString('en-US', { month: 'short' }).toUpperCase();
            let formattedYear = dueDate.getFullYear();

            let id, name;

            if (type === 'BILL') {
                id = dueDate.toISOString().split('T')[0]; // Format YYYY-MM-DD
            } else if (type === 'SOA') {
                id = `${monthAbbrev}${formattedYear}`; // Format MONYYYY
            }

            name = `${monthAbbrev} ${formattedYear}`;

            billingMonths.push({ id, name });

            // Move to the next month
            month += 1;
            if (month > 11) {
                month = 0;
                year += 1;
            }
        }

        return billingMonths;
    };

    return BILLING;
});
