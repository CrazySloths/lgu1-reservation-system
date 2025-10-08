// resources/js/analytics.js
import * as tf from '@tensorflow/tfjs';
import Chart from 'chart.js/auto'; 

const API_ENDPOINT = '/admin/api/usage-data';
const WINDOW_SIZE = 3;   // Using a 3-MONTH window for monthly forecast
const FORECAST_DAYS = 6; // Forecasting the next 6 MONTHS
const CHART_ELEMENT_ID = 'usageChart';

let minVal, maxVal; 

// --- 1. Main Function to Orchestrate ---
export async function startForecasting() {
    const statusElement = document.getElementById('chartStatus');
    statusElement.textContent = "Fetching data from API...";

    try {
        const response = await fetch(API_ENDPOINT);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const usageData = await response.json(); 
        
        // FINAL FIX: remove .map(Number) to preserve data integrity
        if (usageData.length === 0) {
            statusElement.textContent = "API returned successfully, but 0 data points were found. Please add 'approved' bookings to your database.";
            console.error("AI SYSTEM HALTED: API returned an empty array of usage data.");
            return;
        }

        if (usageData.length < WINDOW_SIZE + 1) { // put +1 to ensure at least one output
             statusElement.textContent = `Insufficient data (Found ${usageData.length}). Minimum required is ${WINDOW_SIZE + 1} for accurate windowing.`;
             return; 
        }

        // start training and forecasting
        const { historicalLabels, historicalData, forecastData, forecastLabels } = 
            await trainAndForecast(usageData, statusElement); 

        // Visualize the Results
        visualizeResults(historicalLabels, historicalData, forecastData, forecastLabels);
        statusElement.textContent = "Forecasting Complete. Scroll the chart to see the prediction.";

    } catch (error) {
        console.error("Forecasting Critical Error:", error);
        statusElement.textContent = "CRITICAL ERROR: Failed to fetch API data or start AI. See browser console (F12) for error.";
    }
}

// --- 2. Data Preparation and Model Training ---
async function trainAndForecast(rawData, statusElement) {
    // A. NORMALIZATION (0 to 1 range)
    minVal = Math.min(...rawData);
    maxVal = Math.max(...rawData);

    const normalizedData = rawData.map(val => (val - minVal) / (maxVal - minVal));

    // B. WINDOWING (Create sequences for training)
    let xs = [], ys = [];
    for (let i = 0; i < normalizedData.length - WINDOW_SIZE; i++) {
        xs.push(normalizedData.slice(i, i + WINDOW_SIZE));
        ys.push(normalizedData[i + WINDOW_SIZE]);
    }

    // C. CONVERT TO TENSORS
    const xsTensor = tf.tensor2d(xs, [xs.length, WINDOW_SIZE]).expandDims(-1);
    const ysTensor = tf.tensor2d(ys, [ys.length, 1]);

    // D. BUILD AND TRAIN LSTM MODEL
    const model = tf.sequential();
    model.add(tf.layers.lstm({
        units: 50,
        inputShape: [WINDOW_SIZE, 1], 
        returnSequences: false 
    }));
    model.add(tf.layers.dense({ units: 1 }));

    model.compile({
        optimizer: tf.train.adam(0.005),
        loss: 'meanSquaredError',
    });

    statusElement.textContent = "Training model (0/100 Epochs)...";
    await model.fit(xsTensor, ysTensor, {
        epochs: 100, 
        batchSize: 16,
        // ADDED: Progress Tracker to show the user the AI is working
        callbacks: { onEpochEnd: (epoch, log) => {
            statusElement.textContent = `Training model (${epoch + 1}/100 Epochs): Loss = ${log.loss.toFixed(4)}`;
        }}
    });
    
    // E. GENERATE FORECAST
    const forecastValues = generateFutureForecast(model, normalizedData.slice(-WINDOW_SIZE));
    
    // Prepare data for Chart
    const historyLength = rawData.length;
    // CRITICAL FIX: Updated to generate MONTHLY labels
    const allLabels = getMonthlyLabels(historyLength + FORECAST_DAYS);
    
    return { 
        historicalLabels: allLabels.slice(0, historyLength), 
        historicalData: rawData, 
        forecastData: forecastValues, 
        forecastLabels: allLabels.slice(historyLength)
    };
}

// --- 3. Forecasting Logic ---
function generateFutureForecast(model, lastWindow) {
    let currentWindow = [...lastWindow];
    let forecast = [];

    for (let i = 0; i < FORECAST_DAYS; i++) {
        const inputTensor = tf.tensor2d([currentWindow], [1, WINDOW_SIZE]).expandDims(-1);
        const normalizedPredictionTensor = model.predict(inputTensor);
        const normalizedPrediction = normalizedPredictionTensor.dataSync()[0];

        // De-normalize: Convert back to actual usage hours
        const actualPrediction = Math.max(0, normalizedPrediction * (maxVal - minVal) + minVal);
        
        forecast.push(actualPrediction);

        // Update window for next prediction
        currentWindow.shift();
        currentWindow.push(normalizedPrediction);
        
        inputTensor.dispose();
        normalizedPredictionTensor.dispose();
    }
    
    return forecast;
}

// --- 4. Visualization (Chart.js) ---
function visualizeResults(histLabels, histData, forecastData, forecastLabels) {
    const ctx = document.getElementById(CHART_ELEMENT_ID);
    
    const fullLabels = [...histLabels, ...forecastLabels];
    const forecastPoint = histData[histData.length - 1]; // Last actual point

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: fullLabels,
            datasets: [
                {
                    label: 'Actual Monthly Usage (Hours)', 
                    data: histData,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderWidth: 2,
                    pointRadius: 3, // Visible points for monthly data
                    tension: 0.1,
                    spanGaps: true
                },
                {
                    label: 'Forecasted Monthly Usage (Hours)', 
                    data: [
                        ...Array(histData.length - 1).fill(NaN),
                        forecastPoint,
                        ...forecastData
                    ],
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    pointRadius: 5,
                    tension: 0.1,
                    spanGaps: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Total Usage (Hours)' }
                },
                x: {
                    title: { display: true, text: 'Month (YYYY-MM)' },
                    ticks: {
                        // Display all monthly labels
                        callback: function(val, index) {
                            return this.getLabelForValue(val);
                        }
                    }
                }
            },
            plugins: {
                title: { display: true, text: 'Facility Monthly Usage History and 6-Month Forecast' }
            }
        }
    });
}

// --- Utility: Generate Monthly Labels ---
function getMonthlyLabels(totalMonths) {
    const months = [];
    const today = new Date();
    
    // Calculate the start month
    const startDate = new Date();
    startDate.setMonth(today.getMonth() - (totalMonths - FORECAST_DAYS)); 

    for (let i = 0; i < totalMonths; i++) {
        const date = new Date(startDate);
        date.setMonth(startDate.getMonth() + i);
        
        // Format to YYYY-MM
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        months.push(`${year}-${month}`);
    }
    return months;
}
window.startForecasting = startForecasting;