import puppeteer from 'puppeteer';
import { writeChart, writeResults } from './io.js';
import open from 'open';

main();

async function main() {
  console.log('Scraping topics asociados a nodejs... \n');
  const associatedTopics = await scrape('nodejs');

  // Calculate the repetition of every associated topic
  const accumulator = associatedTopics.reduce((acc, topic) => {
    acc[topic] = acc[topic] ? acc[topic] + 1 : 1;
    return acc;
  }, {});

  const topics = Object.entries(accumulator);

  topics.sort((a, b) => b[1] - a[1]);

  topics.forEach(([name, occurrence]) => {
    console.log(`${name}: ${occurrence}`);
  });
  console.log('\nGuardando resultados ... \n');

  await writeResults(topics);
  
  console.log('Creando gráfico ... \n');

  const file = await writeChart(topics);

  console.log(`Gráfico creado en ${file}`);

  await open(file);

  process.exit(0);
}

async function scrape(topic) {
  let browser;
  try {
    browser = await puppeteer.launch();
    const url = `https://github.com/topics/${topic}`;

    const page = await browser.newPage();
    await page.goto(url);

    for (let index = 0; index < 10; index++) {
      const buttonLoadMore = await page.$('button.ajax-pagination-btn');
      buttonLoadMore && (await buttonLoadMore.click());
      await sleep(1000);
    }

    const elements = await page.$$(
      'article.border.rounded.color-shadow-small.color-bg-subtle.my-4'
    );
    console.log('Cantidad de elementos: ', elements.length);
    const texts = await Promise.all(elements.map(getAssociatedTopics));
    const topics = texts.flat().filter((text) => text && text !== topic);

    return topics;
  } catch (err) {
    console.log(`Error scraping ${topic}`);
    console.error(err);
  } finally {
    await browser.close();
  }
}

async function getAssociatedTopics(element) {
  const dateTime = await element.$eval('relative-time', (element) =>
    element.getAttribute('datetime')
  );

  const actualDate = new Date(dateTime);
  const now = new Date();
  const diff = now.getTime() - actualDate.getTime();
  const diffDays = Math.ceil(diff / (1000 * 3600 * 24));
  if (diffDays > 30) return null;

  const associatedTopics = await element.$$eval(
    'a.topic-tag.topic-tag-link.f6.mb-2',
    (nodes) => nodes.map((node) => node.innerText)
  );

  return associatedTopics;
}

async function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
