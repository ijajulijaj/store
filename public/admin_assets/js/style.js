// Chart.js Dashboard Scripts

// Store the monthly forecast data in a global variable
const monthlyForecastData = window.monthlyForecastData || {};
const growthData = window.growthData || {};

let growthChart;
let forecastChart;
let outletGrowthChart;

document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM loaded - initializing charts');
    initChart();
    initForecastChart();
    initOutletChart();
});

function initChart() {
    const ctx = document.getElementById('orderGrowthChart');
    if (!ctx) {
        console.error('Canvas element not found!');
        return;
    }

    const chartData = {
        labels: growthData.labels || [],
        datasets: [
            {
                label: 'Pending Orders',
                data: growthData.pending || [],
                borderColor: 'rgb(21, 93, 252)',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            },
            {
                label: 'Completed Orders',
                data: growthData.completed || [],
                borderColor: 'rgba(28, 200, 138, 1)',
                backgroundColor: 'rgba(28, 200, 138, 0.05)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            },
            {
                label: 'Canceled Orders',
                data: growthData.canceled || [],
                borderColor: 'rgba(231, 74, 59, 1)',
                backgroundColor: 'rgba(231, 74, 59, 0.05)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }
        ]
    };

    growthChart = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            maintainAspectRatio: false,
            responsive: true,
            interaction: { intersect: false, mode: 'index' },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    title: {
                        display: true,
                        text: 'Number of Orders',   // ✅ Y-axis label
                        font: { size: 14, weight: 'bold' }
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Days',   // ✅ X-axis label (you can change to Weeks/Months if needed)
                        font: { size: 14, weight: 'bold' }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return context.dataset.label + ': ' + context.raw;
                        }
                    }
                }
            }
        }
    });

}

function initForecastChart() {
    const ctx = document.getElementById('monthlyForecastChart');
    if (!ctx) {
        console.error('Forecast chart canvas not found');
        return;
    }

    const labels = monthlyForecastData.labels || [];
    const actualData = monthlyForecastData.monthlyData?.actual || [];
    const projectedData = monthlyForecastData.monthlyData?.projected || [];

    const chartData = {
        labels: labels,
        datasets: [
            {
                label: 'Actual Orders',
                data: actualData,
                backgroundColor: 'rgba(78, 115, 223, 0.7)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1
            },
            {
                label: 'Projected Orders',
                data: projectedData,
                backgroundColor: 'rgba(28, 200, 138, 0.7)',
                borderColor: 'rgba(28, 200, 138, 1)',
                borderWidth: 1
            }
        ]
    };

    forecastChart = new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    title: {
                        display: true,
                        text: 'Number of Orders',  // ✅ Y-axis title
                        font: { size: 14, weight: 'bold' }
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Months',  // ✅ X-axis title
                        font: { size: 14, weight: 'bold' }
                    }
                }
            }
        }
    });

}

function updateChart(days) {
    const chartElement = document.getElementById('orderGrowthChart');
    chartElement.style.opacity = '0.5';

    fetch(window.routes.orderStatistics, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ days: days })
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === '1') {
                growthChart.data.labels = data.payload.labels;
                growthChart.data.datasets[0].data = data.payload.pending;
                growthChart.data.datasets[1].data = data.payload.completed;
                growthChart.data.datasets[2].data = data.payload.canceled;
                growthChart.update();
                document.querySelector('.card-header h6').textContent = `Order Trends (Last ${days} Days)`;
            }
        })
        .catch(error => {
            console.error('Error fetching chart data:', error);
        })
        .finally(() => {
            chartElement.style.opacity = '1';
        });
}

function updateForecast(months) {
    const chartElement = document.getElementById('monthlyForecastChart');
    chartElement.style.opacity = '0.5';

    fetch(window.routes.monthlyForecast, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        },
        body: JSON.stringify({ months: months })
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === '1') {
                forecastChart.data.labels = data.payload.labels;
                forecastChart.data.datasets[0].data = data.payload.monthlyData.actual;
                forecastChart.data.datasets[1].data = data.payload.monthlyData.projected;
                forecastChart.update();
            }
        })
        .catch(error => {
            console.error('Error fetching forecast data:', error);
        })
        .finally(() => {
            chartElement.style.opacity = '1';
        });
}

function initOutletChart() {
    const ctx = document.getElementById('outletOrderGrowthChart');
    if (!ctx) return;

    const labels = window.outletGrowthData?.labels || [];
    const outlets = window.outletGrowthData?.outlets || {};

    const datasets = [];
    Object.keys(outlets).forEach((outlet, idx) => {
        datasets.push({
            label: `Outlet ${outlet} - Pending`,
            data: outlets[outlet].pending || [],
            borderColor: 'rgb(21, 93, 252)',
            borderWidth: 2,
            borderDash: [5, 5],
            fill: false,
            tension: 0.3
        });
        datasets.push({
            label: `Outlet ${outlet} - Completed`,
            data: outlets[outlet].completed || [],
            borderColor: `hsl(${idx * 60}, 70%, 40%)`,
            borderWidth: 2,
            fill: false,
            tension: 0.3
        });
        datasets.push({
            label: `Outlet ${outlet} - Canceled`,
            data: outlets[outlet].canceled || [],
            borderColor: `hsl(${idx * 60}, 70%, 30%)`,
            borderWidth: 2,
            borderDash: [10, 5],
            fill: false,
            tension: 0.3
        });
    });

    outletGrowthChart = new Chart(ctx, {
        type: 'bar',
        data: { labels, datasets },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            interaction: { intersect: false, mode: 'index' },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    title: {
                        display: true,
                        text: 'Number of Orders',   // ✅ Y-axis label
                        font: { size: 14, weight: 'bold' }
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Days',   // ✅ X-axis label
                        font: { size: 14, weight: 'bold' }
                    }
                }
            },
            plugins: {
                legend: { display: false },

                // ⬇️ Scrollable external tooltip
                tooltip: {
                    enabled: false, // disable canvas tooltip
                    external: function (context) {
                        const { chart, tooltip } = context;

                        // Create container once
                        let el = document.getElementById('outlet-scroll-tooltip');
                        if (!el) {
                            el = document.createElement('div');
                            el.id = 'outlet-scroll-tooltip';
                            el.style.position = 'absolute';
                            el.style.background = 'rgba(0,0,0,0.85)';
                            el.style.color = '#fff';
                            el.style.borderRadius = '6px';
                            el.style.boxShadow = '0 6px 18px rgba(0,0,0,0.35)';
                            el.style.padding = '8px';
                            el.style.fontSize = '12px';
                            el.style.maxWidth = '300px';
                            el.style.zIndex = '9999';
                            el.style.pointerEvents = 'auto'; // allow scrolling

                            const title = document.createElement('div');
                            title.id = 'outlet-scroll-tooltip-title';
                            title.style.fontWeight = '600';
                            title.style.marginBottom = '6px';
                            el.appendChild(title);

                            const body = document.createElement('div');
                            body.id = 'outlet-scroll-tooltip-body';
                            body.style.maxHeight = '170px';   // ✅ height cap
                            body.style.overflowY = 'auto';    // ✅ scrollable
                            body.style.whiteSpace = 'normal';
                            el.appendChild(body);

                            // keep open while mouse is over tooltip so you can scroll
                            el._hover = false;
                            el.addEventListener('mouseenter', () => (el._hover = true));
                            el.addEventListener('mouseleave', () => {
                                el._hover = false;
                                el.style.opacity = 0;
                            });

                            document.body.appendChild(el);
                        }

                        // Hide when not active (unless we’re hovering the tooltip)
                        if (tooltip.opacity === 0) {
                            if (!el._hover) el.style.opacity = 0;
                            return;
                        }

                        // Title (e.g., "Aug 19")
                        el.querySelector('#outlet-scroll-tooltip-title').textContent =
                            (tooltip.title || []).join(' • ');

                        // Body lines
                        const bodyEl = el.querySelector('#outlet-scroll-tooltip-body');
                        bodyEl.innerHTML = '';
                        const points = tooltip.dataPoints || [];
                        points.forEach((pt) => {
                            const row = document.createElement('div');
                            row.style.display = 'flex';
                            row.style.alignItems = 'center';
                            row.style.margin = '2px 0';

                            const color =
                                (Array.isArray(pt.dataset.borderColor) ? pt.dataset.borderColor[0] : pt.dataset.borderColor) ||
                                (Array.isArray(pt.dataset.backgroundColor) ? pt.dataset.backgroundColor[0] : pt.dataset.backgroundColor) ||
                                '#999';

                            const dot = document.createElement('span');
                            dot.style.display = 'inline-block';
                            dot.style.width = '10px';
                            dot.style.height = '10px';
                            dot.style.borderRadius = '2px';
                            dot.style.marginRight = '6px';
                            dot.style.background = color;

                            const text = document.createElement('span');
                            text.textContent = `${pt.dataset.label}: ${pt.formattedValue}`;

                            row.appendChild(dot);
                            row.appendChild(text);
                            bodyEl.appendChild(row);
                        });

                        // Position near caret
                        const rect = chart.canvas.getBoundingClientRect();
                        el.style.left =
                            rect.left + window.pageXOffset + tooltip.caretX + 12 + 'px';
                        el.style.top =
                            rect.top + window.pageYOffset + tooltip.caretY - 10 + 'px';
                        el.style.opacity = 1;
                    }
                }
            }
        }
    });

}

function updateOutletChart(days) {
    const chartElement = document.getElementById('outletOrderGrowthChart');
    chartElement.style.opacity = '0.5';

    fetch(window.routes.outletWiseOrderStatistics, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ days: days })
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === '1') {
                const labels = data.payload.labels || [];
                const outlets = data.payload.outlets || {};
                const datasets = [];

                Object.keys(outlets).forEach((outlet, idx) => {
                    datasets.push({
                        label: `Outlet ${outlet} - Pending`,
                        data: outlets[outlet].pending || [],
                        borderColor: 'rgb(21, 93, 252)',
                        borderDash: [5, 5],
                        borderWidth: 2,
                        fill: false,
                        tension: 0.3
                    });
                    datasets.push({
                        label: `Outlet ${outlet} - Completed`,
                        data: outlets[outlet].completed || [],
                        borderColor: `hsl(${idx * 60}, 70%, 40%)`,
                        borderWidth: 2,
                        fill: false,
                        tension: 0.3
                    });
                    datasets.push({
                        label: `Outlet ${outlet} - Canceled`,
                        data: outlets[outlet].canceled || [],
                        borderColor: `hsl(${idx * 60}, 70%, 30%)`,
                        borderDash: [10, 5],
                        borderWidth: 2,
                        fill: false,
                        tension: 0.3
                    });
                });

                outletGrowthChart.data.labels = labels;
                outletGrowthChart.data.datasets = datasets;
                outletGrowthChart.update();
                document.querySelector('#outletChartTitle').textContent = `Outlet Wise Order Trends (Last ${days} Days)`;
            }
        })
        .finally(() => {
            chartElement.style.opacity = '1';
        });
}

function initTopOutletChart(data) {
    const ctx = document.getElementById('topOutletPerformanceChart');
    if (!ctx) return;

    if (!data || data.length === 0) {
        ctx.parentNode.innerHTML = '<p class="text-center mt-4">No outlet performance data available for this month.</p>';
        return;
    }

    const labels = data.map(d => d.outlet_code || 'Unknown');
    const pending = data.map(d => parseFloat(d.total_pending_orders || 0));
    const completed = data.map(d => parseFloat(d.total_completed_orders || 0));

    new Chart(ctx, {
        type: 'bar',
        data: { labels, datasets: [
            { label: 'Pending Order', data: pending, backgroundColor: 'rgba(255,0,0,0.6)' },
            { label: 'Completed Order', data: completed, backgroundColor: 'rgba(28, 200, 138, 0.7)' }
        ]},
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Total Orders' } },
                x: { title: { display: true, text: 'Top 10 Outlets' } }
            }
        }
    });
}

// Call after window.topOutletPerformance is set
initTopOutletChart(window.topOutletPerformance || []);