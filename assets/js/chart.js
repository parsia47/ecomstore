/**
 * ================================================================
 * CHART.JS CONFIGURATION - E-COMMERCE STORE
 * 
 * This file contains data visualization helper functions.
 * Creates charts for sales analysis, category distribution, etc.
 * ================================================================
 */

/**
 * Create a sales trend line chart
 * 
 * @param {string} elementId - ID of the canvas element
 * @param {Array} data - Array of {month, revenue} objects
 */
function createSalesChart(elementId, data) {
    const ctx = document.getElementById(elementId);
    if (!ctx) {
        console.warn('Canvas element not found:', elementId);
        return;
    }
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(function(item) { return item.month; }),
            datasets: [{
                label: 'Revenue',
                data: data.map(function(item) { return item.revenue; }),
                borderColor: '#ff6b35',
                backgroundColor: 'rgba(255, 107, 53, 0.1)',
                fill: true,
                tension: 0.4, // Smooth curve
                pointRadius: 5,
                pointBackgroundColor: '#ff6b35'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Revenue Trend',
                    font: {
                        size: 18,
                        weight: 'bold'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            }
        }
    });
}

/**
 * Create a category distribution doughnut chart
 * 
 * @param {string} elementId - ID of the canvas element
 * @param {Array} data - Array of {category, count} objects
 */
function createCategoryChart(elementId, data) {
    const ctx = document.getElementById(elementId);
    if (!ctx) {
        console.warn('Canvas element not found:', elementId);
        return;
    }
    
    // Color palette for different categories
    const colors = [
        '#ff6b35', // Orange
        '#2196F3', // Blue
        '#2e7d32', // Green
        '#e94560', // Red
        '#f39c12', // Yellow
        '#9b59b6'  // Purple
    ];
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.map(function(item) { return item.category; }),
            datasets: [{
                data: data.map(function(item) { return item.count; }),
                backgroundColor: colors.slice(0, data.length),
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            size: 13
                        },
                        padding: 20
                    }
                },
                title: {
                    display: true,
                    text: 'Sales by Category',
                    font: {
                        size: 18,
                        weight: 'bold'
                    }
                }
            },
            animation: {
                animateRotate: true,
                duration: 1500
            }
        }
    });
}

/**
 * Create a product rating distribution chart
 * 
 * @param {string} elementId - ID of the canvas element
 * @param {Array} data - Array of {rating, count} objects
 */
function createRatingChart(elementId, data) {
    const ctx = document.getElementById(elementId);
    if (!ctx) {
        console.warn('Canvas element not found:', elementId);
        return;
    }
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
            datasets: [{
                label: 'Number of Reviews',
                data: data.map(function(item) { return item.count; }),
                backgroundColor: [
                    'rgba(231, 76, 60, 0.7)',
                    'rgba(231, 76, 60, 0.5)',
                    'rgba(241, 196, 15, 0.7)',
                    'rgba(46, 204, 113, 0.7)',
                    'rgba(46, 204, 113, 0.9)'
                ],
                borderColor: [
                    '#e74c3c',
                    '#e74c3c',
                    '#f1c40f',
                    '#2ecc71',
                    '#2ecc71'
                ],
                borderWidth: 2,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Rating Distribution',
                    font: {
                        size: 18,
                        weight: 'bold'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            animation: {
                duration: 800,
                easing: 'easeOutQuart'
            }
        }
    });
}

// Export functions for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        createSalesChart,
        createCategoryChart,
        createRatingChart
    };
}