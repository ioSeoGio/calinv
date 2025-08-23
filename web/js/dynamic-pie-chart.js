function generateColors(count) {
    const colors = [];
    for (let i = 0; i < count; i++) {
        // Calculate hue (spread across 0-360 degrees)
        const hue = (i * 360 / count) % 360;
        // Use fixed saturation and lightness for vibrant, distinct colors
        const saturation = 70; // 70% saturation
        const lightness = 50;  // 50% lightness
        colors.push(`hsl(${hue}, ${saturation}%, ${lightness}%)`);
    }
    return colors;
}

// Generate colors based on the number of data points
const colors = generateColors(populationDensityConfig.data.length);

Highcharts.chart('population-density-container', {
    chart: {
        type: 'variablepie'
    },
    title: {
        text: populationDensityConfig.title
    },
    tooltip: {
        headerFormat: '',
        pointFormat: populationDensityConfig.pointFormat
    },
    series: [{
        minPointSize: 10,
        innerSize: '20%',
        zMin: 0,
        name: 'countries',
        borderRadius: 5,
        data: populationDensityConfig.data,
        colors: colors
    }]
});