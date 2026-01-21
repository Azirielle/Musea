<script setup>
import { onMounted, ref, watch } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
    type: {
        type: String,
        required: true, // 'line', 'bar', 'pie', 'doughnut'
    },
    data: {
        type: Object,
        required: true,
    },
    options: {
        type: Object,
        default: () => ({}),
    },
    simple: {
        type: Boolean,
        default: false,
    }
});

const canvasRef = ref(null);
let chartInstance = null;

const renderChart = () => {
    if (chartInstance) {
        chartInstance.destroy();
    }

    if (canvasRef.value) {
        chartInstance = new Chart(canvasRef.value, {
            type: props.type,
            data: props.data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: props.options.scales || (props.type === 'line' || props.type === 'bar' ? {
                    x: { 
                        display: !props.simple, // Hide axis for simple charts
                        ticks: { color: document.documentElement.classList.contains('dark') ? '#ccc' : '#666' } 
                    },
                    y: { 
                        display: !props.simple, // Hide axis for simple charts
                        ticks: { color: document.documentElement.classList.contains('dark') ? '#ccc' : '#666' } 
                    }
                } : {}),
                plugins: {
                    legend: {
                        display: !props.simple, // Hide legend for simple charts
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#333'
                        }
                    },
                    tooltip: {
                        enabled: !props.simple // Optional: disable tooltips too if desired, usually keep them
                    }
                },
                elements: props.simple ? {
                    point: { radius: 0 } // Hide points for sparkline
                } : {},
                ...props.options
            }
        });
    }
};

onMounted(() => {
    renderChart();
});

watch(() => props.data, renderChart, { deep: true });
</script>

<template>
    <div class="w-full h-full">
        <canvas ref="canvasRef"></canvas>
    </div>
</template>
