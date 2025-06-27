// Create investment categories doughnut chart
function createCategoriesChart(categories, amounts, colors) {
    const ctx = document.getElementById('doughnutchart');
    if (!ctx) return;

    const canvas = ctx.getContext('2d');
    const hasData = amounts && amounts.length > 0 && amounts.some(amount => amount > 0);

    if (!hasData) {
        // Show empty state message
        showEmptyStateHTML(ctx, 'No investment categories yet', 'Start investing to see your portfolio breakdown');
        return;
    }

    new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: categories,
            datasets: [{
                label: 'Amount Invested ($k)',
                data: amounts,
                backgroundColor: colors,
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return `${context.label}: $${context.parsed.toFixed(2)}k`;
                        }
                    }
                }
            }
        }
    });
}

// Create investment status doughnut chart
function createInvestmentStatusChart(categoryData) {
    const ctx = document.getElementById('investmentChart');
    if (!ctx) return;

    // Calculate total amounts for each status across all categories
    const statusTotals = {
        running: 0,
        completed: 0,
        liquidated: 0,
        cancelled: 0
    };

    // Sum up all amounts by status
    Object.keys(categoryData).forEach(category => {
        statusTotals.running += categoryData[category].running || 0;
        statusTotals.completed += categoryData[category].completed || 0;
        statusTotals.liquidated += categoryData[category].liquidated || 0;
        statusTotals.cancelled += categoryData[category].cancelled || 0;
    });

    // Check if there's any data
    const hasData = Object.values(statusTotals).some(value => value > 0);

    if (!hasData) {
        // Show empty state message
        showEmptyStateHTML(ctx, 'No investments yet', 'Create your first investment to see the status breakdown');
        return;
    }

    // Prepare data for doughnut chart
    const labels = [];
    const data = [];
    const backgroundColor = [];
    const borderColor = [];

    // Only include statuses that have data
    if (statusTotals.running > 0) {
        labels.push('Running');
        data.push(statusTotals.running);
        backgroundColor.push('rgba(40, 167, 69, 0.8)');
        borderColor.push('rgba(40, 167, 69, 1)');
    }
    if (statusTotals.completed > 0) {
        labels.push('Completed');
        data.push(statusTotals.completed);
        backgroundColor.push('rgba(0, 123, 255, 0.8)');
        borderColor.push('rgba(0, 123, 255, 1)');
    }
    if (statusTotals.liquidated > 0) {
        labels.push('Liquidated');
        data.push(statusTotals.liquidated);
        backgroundColor.push('rgba(255, 193, 7, 0.8)');
        borderColor.push('rgba(255, 193, 7, 1)');
    }
    if (statusTotals.cancelled > 0) {
        labels.push('Cancelled');
        data.push(statusTotals.cancelled);
        backgroundColor.push('rgba(220, 53, 69, 0.8)');
        borderColor.push('rgba(220, 53, 69, 1)');
    }

    new Chart(ctx.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: backgroundColor,
                borderColor: borderColor,
                borderWidth: 2,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Investment Distribution by Status',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    padding: 20
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: $${value.toFixed(2)}k (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '60%',
            elements: {
                arc: {
                    borderWidth: 0
                }
            }
        }
    });
}

// Function to show empty state for charts
function showEmptyStateHTML(canvasElement, title, message) {
    const container = canvasElement.parentElement;

    // Hide canvas
    canvasElement.style.display = 'none';

    // Create empty state HTML
    const emptyStateHTML = `
        <div class="empty-state text-center py-5" style="min-height: 280px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
            <div class="empty-state-icon mb-3">
                <svg width="118" height="118" viewBox="0 0 24 24" fill="none" stroke="#dee2e6" stroke-width="1.5" class="lucide lucide-pie-chart">
                    <path d="M21.21 15.89A10 10 0 1 1 8 2.83"/>
                    <path d="m22 12-8.5-5v9.5z"/>
                </svg>
            </div>
            <h5 class="text-muted mb-2">${title}</h5>
            <p class="text-muted small mb-0">${message}</p>
        </div>
    `;

    // Check if empty state already exists
    let emptyStateElement = container.querySelector('.empty-state');
    if (!emptyStateElement) {
        container.insertAdjacentHTML('beforeend', emptyStateHTML);
    }
}

// Sort functionality for dropdown
function initializeSortSelect() {
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            const selected = this.value;
            const url = new URL(window.location.href);
            if (selected) {
                url.searchParams.set('sort', selected);
            } else {
                url.searchParams.delete('sort');
            }
            window.location.href = url.toString();
        });
    }
}

// Initialize all dashboard functionality
function initializeInvestmentDashboard(dashboardData) {
    // Initialize sort functionality
    initializeSortSelect();

    // Create charts if data is provided
    if (dashboardData) {
        // Categories chart
        if (dashboardData.categories && dashboardData.amounts && dashboardData.colors) {
            createCategoriesChart(dashboardData.categories, dashboardData.amounts, dashboardData.colors);
        } else {
            // Show empty state for categories chart
            const ctx = document.getElementById('doughnutchart');
            if (ctx) {
                showEmptyStateHTML(ctx, 'No investment categories yet', 'Start investing to see your portfolio breakdown');
            }
        }

        // Investment status chart
        if (dashboardData.categoryData) {
            createInvestmentStatusChart(dashboardData.categoryData);
        } else {
            // Show empty state for status chart
            const ctx = document.getElementById('investmentChart');
            if (ctx) {
                showEmptyStateHTML(ctx, 'No investments yet', 'Create your first investment to see the status breakdown');
            }
        }
    } else {
        // Show empty states for both charts when no data is available
        const categoriesCtx = document.getElementById('doughnutchart');
        const statusCtx = document.getElementById('investmentChart');

        if (categoriesCtx) {
            showEmptyStateHTML(categoriesCtx, 'No investment categories yet', 'Start investing to see your portfolio breakdown');
        }

        if (statusCtx) {
            showEmptyStateHTML(statusCtx, 'No investments yet', 'Create your first investment to see the status breakdown');
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Check if dashboard data exists in global scope (set by Laravel)
    if (typeof window.dashboardData !== 'undefined') {
        initializeInvestmentDashboard(window.dashboardData);
    } else {
        // Initialize without data (shows empty states)
        initializeInvestmentDashboard();
    }
});

// Export functions for manual initialization if needed
window.InvestmentDashboard = {
    initialize: initializeInvestmentDashboard,
};
