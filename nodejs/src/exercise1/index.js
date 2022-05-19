import puppeteer from 'puppeteer';
import { getTopics, writeChart, writeResults } from './io.js';
import { setRating } from './ratings.js';
import open from 'open';

main();

async function main() {
  console.log('Inicializando... \n');
  const browser = await puppeteer.launch();
  const page = await browser.newPage();

  console.log('Scraping topics... \n');
  const topics = await getTopics();
  const results = [];
  for (const topic of topics) {
    const result = await scrape(page, topic);
    results.push(result);
  }

  setRating(results);

  results.sort((a, b) => b.rating - a.rating);
  results.forEach((result) => {
    console.log(`${result.topic} 
    Apariciones: ${result.occurrence}
    Rating: ${result.rating.toFixed(2)}%`);
  });

  console.log('Escribiendo los resultados... \n');
  await writeResults(results);

  console.log('Creando gráfico de barras... \n');
  const file = await writeChart(results);

  console.log(`Gráfico creado en ${file}`);

  await open(file);

  await browser.close();
  process.exit(0);
}

function getTopicOccurrences(text) {
  const clearText = text.replace(/\s+/g, ' ').trim();
  const numberStr = clearText.split(' ')[2].replace(',', '');
  return +numberStr;
}

async function scrape(page, topic) {
  try {
    const url = `https://github.com/topics/${topic}`;

    await page.goto(url);
    const element = await page.waitForSelector('h2.h3.color-fg-muted');
    const text = await page.evaluate((element) => element.textContent, element);

    const occurrence = getTopicOccurrences(text);

    return { topic, occurrence };
  } catch (err) {
    console.log(`Error scraping ${topic}`);
    console.error(err);
    return { topic, occurrence: -1 };
  }
}
