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
                plugins: {
                    legend: {
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#333'
                        }
                    }
                },
                scales: props.type === 'line' || props.type === 'bar' ? {
                    x: { ticks: { color: document.documentElement.classList.contains('dark') ? '#ccc' : '#666' } },
                    y: { ticks: { color: document.documentElement.classList.contains('dark') ? '#ccc' : '#666' } }
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
