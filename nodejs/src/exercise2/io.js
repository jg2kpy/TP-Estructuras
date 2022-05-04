import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const mainDir = path.join(__dirname, '../exercise2');

export async function writeChart(results) {
  // Get the first 20 of results
  const first20 = results.slice(0, 20);
  const topicsString = first20.map(([name]) => `'${name}'`).join(',');
  const occurrencesString = first20
    .map(([, occurrence]) => occurrence)
    .join(',');
  const html = `<!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <title>Ejercicio 2</title>
  </head>
  <body>
    <canvas id="myChart" width="400" height="400"></canvas>
    <script>
      const ctx = document.getElementById('myChart').getContext('2d');
      const myChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: [${topicsString}],
              datasets: [{
                  label: 'Número de apariciones',
                  data: [${occurrencesString}],
                  borderWidth: 1
              }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: {
                  beginAtZero: true
              }
          }
      }
      });
      </script>
  </body>
  </html>`;

  const filePath = path.join(mainDir, 'chart.html');
  await fs.writeFile(filePath, html);
  return filePath;
}
