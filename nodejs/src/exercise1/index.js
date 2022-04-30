import puppeteer from 'puppeteer';
import { getTopics, writeResults } from './io.js';
import { setRating } from './ratings.js';

console.log('Initializing browser... \n');
const browser = await puppeteer.launch();

console.log('Scraping topics... \n');
const topics = await getTopics();
const promises = topics.map((topic) => scrape(browser, topic));
const results = await Promise.all(promises);

console.log('Writing results... \n');
await writeResults(results);

setRating(results);

results.sort((a, b) => b.rating - a.rating);
results.forEach((result) => {
  console.log(`${result.topic} 
    Occurrence: ${result.occurrence}
    Rating: ${result.rating.toFixed(2)}%`);
});

await browser.close();
process.exit(0);

function getTopicOccurrences(text) {
  const clearText = text.replace(/\s+/g, ' ').trim();
  const numberStr = clearText.split(' ')[2].replace(',', '');
  return +numberStr;
}

async function scrape(browser, topic) {
  try {
    const url = `https://github.com/topics/${topic}`;
    const page = await browser.newPage();

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
